<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $prompt = trim($request->input('message'));
        if (!$prompt) {
            return response()->json(['error' => 'Vui lòng nhập câu hỏi.'], 400);
        }

        // ===== Chuẩn hóa dữ liệu =====
        $promptLower = Str::lower($prompt);
        $promptNoAccent = Str::slug($promptLower, ' ');

        $userId = Auth::id();
        $sv = $userId ? DB::table('sinhvien')->where('user_id', $userId)->first() : null;

        // ===== Lấy danh sách vị trí thực tập =====
        $vitri = DB::table('vitri_thuctap')
            ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
            ->select(
                'vitri_thuctap.vitri_id',
                'vitri_thuctap.ten_vitri',
                'vitri_thuctap.mo_ta',
                'vitri_thuctap.yeu_cau',
                'vitri_thuctap.soluong',
                'vitri_thuctap.so_luong_da_dangky',
                'doanhnghiep.ten_dn'
            )
            ->where('vitri_thuctap.is_delete', 0)
            ->get();

        // ===== Nhận diện Intent =====
        $isChao = preg_match('/\b(chao|hello|hi|xin chao|hey)\b/u', $promptNoAccent);
        $isTuVan = preg_match('/(tu van|vi tri|thuc tap|goi y|phu hop)/u', $promptNoAccent);

        // =====  Lời chào =====
        if ($isChao && !$isTuVan) {
            $name = $sv->ho_ten ?? 'bạn';
            $reply = "👋 Xin chào {$name}! Rất vui được gặp bạn 😊<br>
            Tôi là trợ lý thực tập. Bạn có thể hỏi tôi về các vị trí thực tập phù hợp, 
            hoặc nói về sở trường của bạn để tôi tư vấn nhé!";
            return response()->json(['reply' => $reply]);
        }

        // =====  Chuẩn bị mảng kỹ năng =====
        $skillKeywords = [
            'lap trinh' => [
                'lap trinh', 'php', 'laravel', 'react', 'code', 'oop',
                'html', 'css', 'javascript', 'java', 'python', 'backend', 'frontend'
            ],
            'design' => [
                'design', 'deisgn', 'thiet ke', 'ui', 'ux', 'graphic', 'photoshop',
                'figma', 'illustrator', 'banner', 'poster'
            ],
            'marketing' => [
                'marketing', 'seo', 'content', 'social media', 'quang cao', 'pr', 'sale'
            ]
        ];

        // =====  Nhận kỹ năng trong câu (cho phép sai chính tả nhẹ) =====
        $matchedSkills = [];
        foreach ($skillKeywords as $skill => $keywords) {
            foreach ($keywords as $kw) {
                // Fuzzy match nhẹ (levenshtein khoảng cách < 3)
                if (Str::contains($promptNoAccent, $kw) || levenshtein($promptNoAccent, $kw) < 3) {
                    $matchedSkills[] = $skill;
                    break;
                }
            }
        }
        $matchedSkills = array_unique($matchedSkills);

        // =====  Lọc vị trí phù hợp =====
        $goiY = $vitri->filter(function ($v) use ($sv, $matchedSkills, $skillKeywords) {
            $content = Str::slug(Str::lower($v->yeu_cau . ' ' . $v->ten_vitri . ' ' . $v->mo_ta), ' ');
            $match = false;

            // So với ngành
            if ($sv && $sv->nganh) {
                if (Str::contains($content, Str::slug(Str::lower($sv->nganh), ' '))) {
                    $match = true;
                }
            }

            // So với kỹ năng
            if (!$match && !empty($matchedSkills)) {
                foreach ($matchedSkills as $skill) {
                    foreach ($skillKeywords[$skill] as $kw) {
                        if (Str::contains($content, $kw)) {
                            $match = true;
                            break 2;
                        }
                    }
                }
            }

            return $match;
        });

        if ($goiY->isNotEmpty()) {
            $title = "💡 Chào bạn " . ($sv->ho_ten ?? 'bạn') . ", dưới đây là các vị trí thực tập phù hợp với bạn:";
            $html = $this->formatPositionsHTML($goiY, $title);
            return response()->json(['reply' => $html]);
        }

        // =====  Fallback: gọi Gemini AI =====
        $dataSummary = "Danh sách vị trí thực tập hiện có:\n";
        foreach ($vitri as $v) {
            $slots = max(0, $v->soluong - $v->so_luong_da_dangky);
            $dataSummary .= "- {$v->ten_vitri} tại {$v->ten_dn} (Còn {$slots} slot, yêu cầu: {$v->yeu_cau})\n";
        }

        $inputPrompt = "
        Bạn là chatbot tư vấn thực tập thân thiện.
        Hồ sơ sinh viên:
        - Tên: {$sv->ho_ten}
        - Ngành: {$sv->nganh}
        - Lớp: {$sv->lop}
        Câu hỏi: {$prompt}
        Dữ liệu vị trí:
        {$dataSummary}
        Hãy trả lời bằng tiếng Việt thân thiện, ngắn gọn, gợi ý các vị trí phù hợp.
        ";

        $apiKey  = env('GEMINI_API_KEY');
        $model   = env('GEMINI_MODEL');
        $baseUrl = env('GEMINI_API_URL');
        $url = "{$baseUrl}/{$model}:generateContent?key={$apiKey}";

        Log::info('=== Gemini Request ===', ['url' => $url, 'prompt' => $inputPrompt]);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [[
                        'role' => 'user',
                        'parts' => [['text' => $inputPrompt]]
                    ]]
                ]);

            if ($response->failed()) {
                Log::error('Gemini API Error', ['response' => $response->body()]);
                return response()->json(['error' => 'Không thể kết nối đến AI.'], 500);
            }

            $data = $response->json();
            Log::info('=== Gemini Response ===', ['data' => $data]);

            $reply = $data['candidates'][0]['content']['parts'][0]['text']
                ?? ($data['candidates'][0]['output'] ?? 'Xin lỗi, tôi chưa thể trả lời câu hỏi này.');

            return response()->json(['reply' => nl2br(e($reply))]);
        } catch (\Throwable $e) {
            Log::error('Gemini Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Lỗi khi kết nối đến AI: ' . $e->getMessage()], 500);
        }
    }

    // ===== Hàm hỗ trợ format HTML =====
    private function formatPositionsHTML($positions, $title)
    {
        $colors = ['#E8F0FE', '#FEF3E8', '#E8FEF5', '#FFF6E8', '#FEE8F0'];
        $html = "<p style='font-weight:bold;'>{$title}</p><ul style='list-style:none; padding:0; margin:0;'>";
        $i = 0;
        foreach ($positions as $v) {
            $slots = max(0, $v->soluong - $v->so_luong_da_dangky);
            $color = $colors[$i % count($colors)];
            $link = route('sinhvien.vitri_sinhvien.list', ['id' => $v->vitri_id]);
            $html .= "<li style='background-color:{$color}; padding:12px 16px; margin-bottom:10px; border-radius:12px;'>
                <strong style='font-size:1rem;'>{$v->ten_vitri} tại {$v->ten_dn}</strong> 
                (<span style='color:green; font-weight:bold;'>Còn {$slots} slot</span>)<br>
                <em style='font-size:0.9rem; color:#555;'>{$v->mo_ta}</em><br>
                <a href='{$link}' target='_blank' style='color:#2563EB; text-decoration:underline; font-size:0.9rem;'>Xem chi tiết</a>
            </li>";
            $i++;
        }
        $html .= "</ul>";
        return $html;
    }
}