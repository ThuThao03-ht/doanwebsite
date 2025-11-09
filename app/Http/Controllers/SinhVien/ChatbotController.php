<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $prompt = $request->input('message');
        if (!$prompt) {
            return response()->json(['error' => 'Vui lòng nhập câu hỏi.'], 400);
        }

        // 🔹 Lấy thông tin sinh viên (nếu đã đăng nhập)
        $userId = Auth::id();
        $sv = null;
        if ($userId) {
            $sv = DB::table('sinhvien')
                ->where('user_id', $userId)
                ->first();
        }

        // 🔹 Lấy danh sách vị trí thực tập
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

        // 🔹 Kiểm tra xem người dùng hỏi cụ thể về vị trí nào không
        $vitri_timduoc = $vitri->first(function ($v) use ($prompt) {
            return Str::contains(Str::lower($prompt), Str::lower($v->ten_vitri));
        });

        // 🔹 Nếu người dùng hỏi cụ thể về 1 vị trí → phản hồi chi tiết HTML
        if ($vitri_timduoc) {
            $slots = max(0, $vitri_timduoc->soluong - $vitri_timduoc->so_luong_da_dangky);
            $linkChiTiet = route('sinhvien.vitri_sinhvien.xem', ['id' => $vitri_timduoc->vitri_id]);
            $linkDangKy = route('sinhvien.vitri_sinhvien.list');

            $reply = "
            📍 <strong>Thông tin vị trí bạn hỏi:</strong><br>
            <strong>Vị trí:</strong> {$vitri_timduoc->ten_vitri}<br>
            <strong>Doanh nghiệp:</strong> {$vitri_timduoc->ten_dn}<br>
            <strong>Yêu cầu:</strong> {$vitri_timduoc->yeu_cau}<br>
            <strong>Mô tả:</strong> {$vitri_timduoc->mo_ta}<br>
            <strong>Slots còn lại:</strong> {$slots}<br><br>
            🔗 <a href='{$linkChiTiet}' target='_blank'>Xem chi tiết</a><br>
            📝 <a href='{$linkDangKy}' target='_blank'>Đăng ký vị trí thực tập</a>
            ";

            return response()->json(['reply' => $reply]);
        }

        // 🔹 Nếu không hỏi cụ thể → gợi ý tổng quan bằng AI
        $dataSummary = "Danh sách vị trí thực tập hiện có:\n";
        foreach ($vitri as $v) {
            $slots = max(0, $v->soluong - $v->so_luong_da_dangky);
            $dataSummary .= "- {$v->ten_vitri} tại {$v->ten_dn} (Còn {$slots} slot, yêu cầu: {$v->yeu_cau})\n";
        }

        $inputPrompt = "
        Bạn là trợ lý ảo giúp sinh viên chọn vị trí thực tập.
        Hồ sơ sinh viên:
        - Tên: {$sv->ho_ten}
        - Ngành học: {$sv->nganh}
        - Lớp: {$sv->lop}
        Câu hỏi của sinh viên: {$prompt}
        Dữ liệu vị trí hiện có:
        {$dataSummary}

        Hãy trả lời bằng tiếng Việt thân thiện, ngắn gọn và gợi ý vị trí phù hợp.
        Nếu sinh viên muốn xem chi tiết, hướng dẫn họ nhấn vào link xem chi tiết hoặc đăng ký.
        ";

        // 🔹 Gọi API Gemini
        $apiKey  = env('GEMINI_API_KEY');
        $model   = env('GEMINI_MODEL');
        $baseUrl = env('GEMINI_API_URL');

        $url = "{$baseUrl}/{$model}:generateContent?key={$apiKey}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $inputPrompt]]
                ]
            ]
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Không thể kết nối đến AI.'], 500);
        }

        $data = $response->json();
        $reply = $data['candidates'][0]['content']['parts'][0]['text']
            ?? 'Xin lỗi, tôi chưa thể trả lời câu hỏi này.';

        return response()->json(['reply' => nl2br(e($reply))]); // Giữ HTML an toàn
    }
}