<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminDashboardController;    
use App\Http\Controllers\Admin\SinhVienController;
use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\GiangVienController;
use App\Http\Controllers\Admin\DoanhNghiepController;
use App\Http\Controllers\Admin\VitriThuctapController;
use App\Http\Controllers\Admin\DangkyThuctapController;
use App\Http\Controllers\Admin\TienDoController;
use App\Http\Controllers\Admin\BaoCaoThucTapController;
use App\Http\Controllers\Admin\GiangVienDanhGiaController;
use App\Http\Controllers\Admin\PhanCongGiangVienController;
use App\Http\Controllers\Admin\DoanhNghiepDanhGiaController;
use App\Http\Controllers\Admin\ThongBaoController;
use App\Http\Controllers\Admin\KhoiPhucController;
use App\Http\Controllers\Admin\HoSoController;
use App\Http\Controllers\SinhVien\AppDashboardController;
use App\Http\Controllers\SinhVien\ViTriThucTapSVController;
use App\Http\Controllers\SinhVien\DangKyThucTapSVController;
use App\Http\Controllers\SinhVien\TienDoThucTapSVController;
use App\Http\Controllers\SinhVien\BaoCaoThucTapSVController;
use App\Http\Controllers\SinhVien\DanhGiaSVController;
use App\Http\Controllers\SinhVien\HoSoSVController;
use App\Http\Controllers\GiangVien\QLDangKyThucTapTQController;
use App\Http\Controllers\GiangVien\DashBoardTQController;
use App\Http\Controllers\GiangVien\QLDanhGiaThucTapTQController;
use App\Http\Controllers\GiangVien\QLTienDoThucTapTQController;
use App\Http\Controllers\GiangVien\HoSoTQController;
use App\Http\Controllers\SinhVien\ChatbotController;


use Illuminate\Support\Facades\Auth;


Route::get('/api/dangkythuctap/count', [DangKyThucTapController::class, 'countDangKy']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
// Route::get('/', function () { return redirect()->route('login');});
Route::get('/', function () {
    if (Auth::check()) {
        $role_id = Auth::user()->role_id;

        if ($role_id == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role_id == 2) {
            return redirect()->route('sinhvien.dashboard');
        } elseif ($role_id == 3 || $role_id == 4) {
            return redirect()->route('giangvien.dashboard');
        } else {
            return redirect()->route('no-permission');
        }
    }
    return redirect()->route('login');
});








Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', CheckAdmin::class]) // <--- gán middleware ở đây
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles.index');
            Route::post('/', [RoleController::class, 'store'])->name('roles.store');
            Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });
    Route::prefix('sinhvien')->group(function () {
        Route::get('/', [SinhVienController::class, 'index'])->name('sinhvien.index');
        Route::post('/', [SinhVienController::class, 'store'])->name('sinhvien.store');
        Route::put('/{id}', [SinhVienController::class, 'update'])->name('sinhvien.update');
        Route::delete('/{id}', [SinhVienController::class, 'destroy'])->name('sinhvien.destroy');
        Route::get('/export', [SinhVienController::class, 'export'])->name('sinhvien.export');
        Route::post('/import', [SinhVienController::class, 'import'])->name('sinhvien.import');
    });
    // Quản lý Tài khoản
    Route::prefix('taikhoan')->group(function () {
        Route::get('/', [TaiKhoanController::class, 'index'])->name('taikhoan.index');          // danh sách
        Route::get('/{id}', [TaiKhoanController::class, 'show'])->name('taikhoan.show');       // xem chi tiết (AJAX)
        Route::post('/toggle/{id}', [TaiKhoanController::class, 'toggleStatus'])->name('taikhoan.toggle'); // khóa/mở khóa
    });
    // 🔹 Quản lý giảng viên
    Route::prefix('giangvien')->group(function () {
        Route::get('/', [GiangVienController::class, 'index'])->name('giangvien.index');     // Hiển thị danh sách
        Route::post('/', [GiangVienController::class, 'store'])->name('giangvien.store');    // Thêm mới
        Route::put('/{id}', [GiangVienController::class, 'update'])->name('giangvien.update'); // Cập nhật
        Route::delete('/{id}', [GiangVienController::class, 'destroy'])->name('giangvien.destroy'); // Xóa
        Route::get('/{id}', [GiangVienController::class, 'show'])->name('giangvien.show');   // Xem chi tiết (AJAX)
    });
    // 🔹 Quản lý doanh nghiệp
    Route::prefix('doanhnghiep')->group(function () {
        Route::get('/', [DoanhNghiepController::class, 'index'])->name('doanhnghiep.index');
        Route::post('/', [DoanhNghiepController::class, 'store'])->name('doanhnghiep.store');
        Route::put('/{id}', [DoanhNghiepController::class, 'update'])->name('doanhnghiep.update');
        Route::delete('/{id}', [DoanhNghiepController::class, 'destroy'])->name('doanhnghiep.destroy');
        Route::get('/{id}', [DoanhNghiepController::class, 'show'])->name('doanhnghiep.show');
    });
    Route::prefix('vitrithuctap')->group(function () {
        Route::get('/', [VitriThuctapController::class, 'index'])->name('vitrithuctap.index');
        Route::post('/', [VitriThuctapController::class, 'store'])->name('vitrithuctap.store');
        Route::put('/{id}', [VitriThuctapController::class, 'update'])->name('vitrithuctap.update');
        Route::delete('/{id}', [VitriThuctapController::class, 'destroy'])->name('vitrithuctap.destroy');
        Route::get('/export', [VitriThuctapController::class, 'export'])->name('vitrithuctap.export');
        Route::get('/{id}', [VitriThuctapController::class, 'show'])->name('vitrithuctap.show'); 
        Route::post('/import', [VitriThuctapController::class, 'import'])->name('vitrithuctap.import');
    });
    // Đăng ký thực tập
     Route::prefix('dangkythuctap')->group(function () {
        Route::get('/', [DangKyThucTapController::class, 'index'])->name('dangkythuctap.index');
        Route::get('/{id}', [DangKyThucTapController::class, 'show'])->name('dangkythuctap.show');
        Route::post('/duyet/{id}', [DangKyThucTapController::class, 'duyet'])->name('dangkythuctap.duyet');
        Route::post('/capnhat/{id}', [DangKyThucTapController::class, 'capNhatTrangThai'])->name('dangkythuctap.capnhat');
        Route::post('/huy/{id}', [DangKyThucTapController::class, 'huyDangKy'])->name('dangkythuctap.huy');
        Route::post('/duyet-bulk', [DangKyThucTapController::class, 'duyetBulk']);

    });
    // 🔹 Quản lý tiến độ thực tập
    Route::prefix('tiendo')->group(function () {
        Route::get('/', [TienDoController::class, 'index'])->name('tiendo.index');
        Route::get('/{id}', [TienDoController::class, 'show'])->name('tiendo.show');
    });
    Route::prefix('baocaothuctap')->name('baocaothuctap.')->group(function () {
        Route::get('/', [BaoCaoThucTapController::class, 'index'])->name('index');
        Route::get('/{id}', [BaoCaoThucTapController::class, 'show'])->name('show');
        Route::get('/view-file/{id}', [BaoCaoThucTapController::class, 'viewFile'])->name('viewFile');
        Route::get('/download/{id}', [BaoCaoThucTapController::class, 'downloadFile'])->name('downloadFile');
    });
    Route::prefix('giangviendanhgia')->name('giangviendanhgia.')->group(function () {
        Route::get('/', [GiangVienDanhGiaController::class, 'index'])->name('index');
        Route::get('/{id}', [GiangVienDanhGiaController::class, 'show'])->name('show'); // xem chi tiết
    });
    // 🔹 Quản lý phân công giảng viên
    Route::prefix('phanconggiangvien')->name('phanconggiangvien.')->group(function () {
        Route::get('/', [PhanCongGiangVienController::class, 'index'])->name('index');
        Route::get('/{id}', [PhanCongGiangVienController::class, 'show'])->name('show'); // Xem chi tiết (AJAX)
        Route::post('/', [PhanCongGiangVienController::class, 'store'])->name('store'); // Thêm mới
        Route::put('/{id}', [PhanCongGiangVienController::class, 'update'])->name('update'); // Sửa



    });

    
    // 🔹 Quản lý đánh giá doanh nghiệp
    Route::prefix('doanhnghiepdanhgia')->name('doanhnghiepdanhgia.')->group(function () {
        Route::get('/', [DoanhNghiepDanhGiaController::class, 'index'])->name('index');
        Route::get('/{id}', [DoanhNghiepDanhGiaController::class, 'show'])->name('show'); // xem chi tiết (AJAX)
    });
    // Quản lý thông báo
    Route::prefix('thongbao')->name('thongbao.')->group(function () {
        Route::get('/', [ThongBaoController::class, 'index'])->name('index');
        Route::post('/store', [ThongBaoController::class, 'store'])->name('store');
        Route::put('/update/{id}', [ThongBaoController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ThongBaoController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('khoiphuc')->name('khoiphuc.')->group(function () {
        Route::get('/sinhvien', [KhoiPhucController::class, 'sinhvien'])->name('sinhvien');
        Route::post('/sinhvien/{id}', [KhoiPhucController::class, 'khoiPhucSinhVien'])->name('sinhvien.restore');

        Route::get('/giangvien', [KhoiPhucController::class, 'giangvien'])->name('giangvien');
        Route::post('/giangvien/{id}', [KhoiPhucController::class, 'khoiPhucGiangVien'])->name('giangvien.restore');

        Route::get('/doanhnghiep', [KhoiPhucController::class, 'doanhnghiep'])->name('doanhnghiep');
        Route::post('/doanhnghiep/{id}', [KhoiPhucController::class, 'khoiPhucDoanhNghiep'])->name('doanhnghiep.restore');

        Route::get('/vitri', [KhoiPhucController::class, 'vitri'])->name('vitri');
        Route::post('/vitri/{id}', [KhoiPhucController::class, 'khoiPhucViTri'])->name('vitri.restore');

        Route::get('/dangky', [KhoiPhucController::class, 'dangky'])->name('dangky');
        Route::post('/dangky/{id}', [KhoiPhucController::class, 'khoiPhucDangKy'])->name('dangky.restore');
    });
        Route::prefix('hoso')->name('hoso.')->group(function () {
        Route::get('/', [HoSoController::class, 'index'])->name('index');
      
        Route::post('/capnhat', [HoSoController::class, 'update'])->name('update');
        Route::post('/capnhat-avatar', [HoSoController::class, 'updateAvatar'])->name('updateAvatar');
    // Route đổi mật khẩu
        Route::get('/doimatkhau', [HoSoController::class, 'showDoiMatKhauForm'])->name('doimatkhau'); // hiển thị form
        Route::post('/doimatkhau', [HoSoController::class, 'doiMatKhau'])->name('doiMatKhau'); // xử lý form
    });
// Các route admin khác...
    });


  

Route::prefix('sinhvien')
    ->name('sinhvien.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [AppDashboardController::class, 'index'])->name('dashboard');
        // API đếm số thông báo chưa đọc
        Route::get('/thongbao/chua-doc', [AppDashboardController::class, 'countThongBaoMoi'])
            ->name('thongbao.chua-doc');
       // Xem chi tiết thông báo (và đánh dấu đã đọc)
        Route::get('/thongbao/{id}', [AppDashboardController::class, 'xemThongBao'])
            ->name('thongbao.xem');

        // Trang danh sách thông báo (đã đọc + chưa đọc)
        Route::get('/thongbao', [AppDashboardController::class, 'tatCaThongBao'])
            ->name('thongbao.danhsach');

        //  Hồ sơ sinh viên
        Route::get('/hoso', [HoSoSVController::class, 'index'])->name('hoso');
        Route::post('/hoso/update', [HoSoSVController::class, 'update'])->name('hoso.update');
        Route::post('/hoso/avatar', [HoSoSVController::class, 'updateAvatar'])->name('hoso.updateAvatar');
         //  Đổi mật khẩu (Sinh viên)
        Route::get('/hoso/doi-mat-khau', [HoSoSVController::class, 'showDoiMatKhauForm'])
            ->name('hoso.doimatkhau');

        Route::post('/hoso/doi-mat-khau', [HoSoSVController::class, 'doiMatKhau'])
            ->name('hoso.doiMatKhau');

        // web.php
        Route::get('/vitri/{id}', [AppDashboardController::class, 'xemViTri'])->name('vitri.xem');
        Route::post('/vitri/dangky', [AppDashboardController::class, 'dangKyViTri'])->name('vitri.dangky');
        // Xem chi tiết đăng ký (hiển thị modal)
        Route::get('/dangky/{id}', [AppDashboardController::class, 'xemDangKy'])
            ->name('dangky.xem');

        // Hủy đăng ký
        Route::delete('/dangky/{id}', [AppDashboardController::class, 'huyDangKy'])
            ->name('dangky.huy');

            
         // ====== CỦA SINH VIÊN ======
        Route::get('/vitrithuctap', [ViTriThucTapSVController::class, 'vitrithuctap'])
            ->name('vitri_sinhvien.list');

        Route::get('/vitri-sv/{id}', [ViTriThucTapSVController::class, 'xemChiTietViTri'])
            ->name('vitri_sinhvien.xem');

            
        Route::post('/vitri-sv/dangky', [ViTriThucTapSVController::class, 'dangKyViTriThucTap'])
            ->name('vitri_sinhvien.dangky');

            // Trang danh sách đăng ký thực tập của sinh viên
        Route::get('/dangky-thuctap', [DangKyThucTapSVController::class, 'index'])
            ->name('dangkythuctap.index');

        // Xem chi tiết đăng ký (hiển thị modal)
        Route::get('/dangky/{id}', [DangKyThucTapSVController::class, 'xemDangKy'])
            ->name('dangky.xem');

        // Hủy đăng ký
        Route::delete('/dangky/{id}', [DangKyThucTapSVController::class, 'huyDangKy'])
            ->name('dangky.huy');

                    // ===== TIẾN ĐỘ THỰC TẬP =====
        Route::get('/tiendo-thuctap', [TienDoThucTapSVController::class, 'index'])
            ->name('tiendo.index');

        Route::get('/tiendo/{id}', [TienDoThucTapSVController::class, 'xemChiTiet'])
            ->name('tiendo.xem');

        Route::get('/tiendo/{id}/file', [TienDoThucTapSVController::class, 'xemFile'])
            ->name('tiendo.xemfile');

        Route::get('/tiendo/{id}/download', [TienDoThucTapSVController::class, 'taiFile'])
            ->name('tiendo.tai');

        Route::post('/tiendo', [TienDoThucTapSVController::class, 'store'])
            ->name('tiendo.store');

        Route::post('/tiendo/{id}', [TienDoThucTapSVController::class, 'update'])
            ->name('tiendo.update');
        Route::delete('/tiendo/{id}', [TienDoThucTapSVController::class, 'destroy'])
            ->name('tiendo.destroy');


        Route::get('/baocao', [BaoCaoThucTapSVController::class, 'index'])->name('baocao.index');
        Route::post('/baocao', [BaoCaoThucTapSVController::class, 'store'])->name('baocao.store');
        Route::get('/baocao/{id}', [BaoCaoThucTapSVController::class, 'xemChiTiet'])->name('baocao.show');
        Route::get('/baocao/{id}/file', [BaoCaoThucTapSVController::class, 'xemFile'])->name('baocao.viewfile');
        Route::get('/baocao/{id}/download', [BaoCaoThucTapSVController::class, 'taiFile'])->name('baocao.download');
        Route::post('/baocao/{id}', [BaoCaoThucTapSVController::class, 'update'])->name('baocao.update');
        Route::delete('/baocao/{id}', [BaoCaoThucTapSVController::class, 'destroy'])->name('baocao.destroy');
        Route::get('/danhgia', [DanhGiaSVController::class, 'index'])->name('danhgia.index');
        Route::get('/danhgia/{dk_id}', [DanhGiaSVController::class, 'show'])->name('danhgia.show');
        Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    });

    Route::prefix('giangvien')
    ->name('giangvien.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [DashBoardTQController::class, 'index'])
            ->name('dashboard');
             Route::get('/thongbao', [DashBoardTQController::class, 'tatCaThongBao'])
            ->name('thongbao');

        Route::get('/thongbao/{id}', [DashBoardTQController::class, 'chiTietThongBao'])
            ->name('thongbao.xem');
             Route::get('/qldangkythuctap', [QLDangKyThucTapTQController::class, 'index'])
            ->name('qldangkythuctap');
             Route::get('/qltiendothuctap', [QLTienDoThucTapTQController::class, 'index'])
            ->name('qltiendothuctap');
            Route::get('/qldanhgiathuctap', [QLDanhGiaThucTapTQController::class, 'index'])
            ->name('qldanhgiathuctap');
           
        Route::post('/qldanhgiathuctap/danhgia', [QLDanhGiaThucTapTQController::class, 'danhgia'])->name('qldanhgiathuctap.danhgia');
        // Hồ sơ giảng viên
        Route::get('/hoso', [HoSoTQController::class, 'index'])->name('hoso');
        Route::post('/hoso/update', [HoSoTQController::class, 'update'])->name('hoso.update');
        Route::post('/hoso/update-avatar', [HoSoTQController::class, 'updateAvatar'])->name('hoso.updateAvatar');
        Route::get('/hoso/doi-mat-khau', [HoSoTQController::class, 'showDoiMatKhauForm'])->name('hoso.doimatkhau');
        Route::post('/hoso/doi-mat-khau', [HoSoTQController::class, 'doiMatKhau'])->name('hoso.doiMatKhau');
    });