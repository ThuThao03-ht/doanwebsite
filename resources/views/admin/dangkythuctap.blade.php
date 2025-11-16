@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-theme mb-0" style="color: #4A7FA7">
            <i class="bi bi-journal-check me-2" style="color:#4A7FA7;"></i>Danh sách đăng ký thực tập
        </h3>

        <div class="d-flex align-items-center">

            {{-- Form lọc --}}
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2 fw-semibold" style="color:#4A7FA7;">
                    <i class="bi bi-funnel-fill me-1"></i>Lọc trạng thái:
                </label>
                <select name="trang_thai" class="form-select me-2 shadow-sm rounded-2"
                    style="width: 200px; border-color:#4A7FA7;">
                    <option value="">Tất cả</option>
                    <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt
                    </option>
                    <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt
                    </option>
                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                    <option value="dang_thuctap" {{ request('trang_thai') == 'dang_thuctap' ? 'selected' : '' }}>Đang
                        thực tập</option>
                    <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành
                    </option>
                </select>
                <button type="submit" class="btn btn-sm text-white" style="background-color:#4A7FA7;">
                    Lọc
                </button>
            </form>

            {{-- Button duyệt hàng loạt --}}
            <button type="button" id="btnBulkDuyet" class="btn btn-success ms-3">
                <i class="bi bi-check2-all"></i> Duyệt hàng loạt
            </button>
        </div>
    </div>

    <hr class="my-3 border-2 opacity-50">

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header fw-semibold text-white" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i>Bảng đăng ký thực tập
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #e9f1f7;" class="text-center">
                        <tr>
                            <th>
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th>#</th>
                            <th>Sinh viên</th>
                            <th>Vị trí</th>
                            <th>Ngày đăng ký</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($dangky as $dk)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="checkItem" value="{{ $dk->dk_id }}">
                            </td>
                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                            <td>{{ $dk->sinhVien->ho_ten ?? '—' }}</td>
                            <td>{{ $dk->viTriThucTap->ten_vitri ?? '—' }}</td>
                            <td class="text-center">{{ $dk->ngay_dangky }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ 
                                    $dk->trang_thai == 'cho_duyet' ? 'secondary' :
                                    ($dk->trang_thai == 'da_duyet' ? 'success' :
                                    ($dk->trang_thai == 'tu_choi' ? 'danger' : 'info'))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $dk->trang_thai)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalChiTiet">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-success me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalDuyet">
                                    <i class="bi bi-check2-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-warning me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalCapNhat">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-id="{{ $dk->dk_id }}" data-bs-toggle="modal"
                                    data-bs-target="#modalHuy">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- ============ Modal Chi tiết ============ --}}
            <div class="modal fade" id="modalChiTiet" tabindex="-1">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0 rounded-3">
                        <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                            <h5 class="modal-title fw-semibold"><i class="fas fa-file-alt me-2"></i>Chi tiết đăng ký
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 py-3" id="chiTietContent">
                            <div class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...
                            </div>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-end">
                            <button type="button" class="btn text-white px-4" data-bs-dismiss="modal"
                                style="background-color: #4a7fa7;">
                                <i class="fas fa-times-circle me-1"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ Modal duyệt đơn lẻ ============ --}}
            <div class="modal fade" id="modalDuyet" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form method="POST" id="formDuyet">@csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                                <h5 class="modal-title fw-semibold"><i class="bi bi-check-circle-fill me-2"></i>Duyệt
                                    đăng ký</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                <label class="form-label fw-semibold text-secondary"><i
                                        class="bi bi-toggle2-on me-2"></i>Chọn trạng thái:</label>
                                <select name="trang_thai" class="form-select shadow-sm rounded-2">
                                    <option value="da_duyet">Duyệt</option>
                                    <option value="tu_choi">Từ chối</option>
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button class="btn text-white" style="background-color: #4a7fa7;" type="submit">Cập
                                    nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============ Modal cập nhật trạng thái ============ --}}
            <div class="modal fade" id="modalCapNhat" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form method="POST" id="formCapNhat">@csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                                <h5 class="modal-title fw-semibold"><i class="bi bi-arrow-repeat me-2"></i>Cập nhật
                                    trạng thái</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                <label class="form-label fw-semibold text-secondary">Trạng thái mới:</label>
                                <select name="trang_thai" class="form-select shadow-sm rounded-2">
                                    <option value="dang_thuctap">Đang thực tập</option>
                                    <option value="hoan_thanh">Hoàn thành</option>
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button class="btn text-white" style="background-color: #4a7fa7;" type="submit">Cập
                                    nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============ Modal hủy đơn lẻ ============ --}}
            <div class="modal fade" id="modalHuy" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" id="formHuy">@csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-semibold"><i
                                        class="bi bi-exclamation-octagon-fill me-2"></i>Xác nhận hủy đăng ký</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Bạn có chắc chắn muốn
                                    <strong>hủy</strong> đăng ký này không?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Xác nhận</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============ Modal duyệt hàng loạt ============ --}}
            <div class="modal fade" id="modalBulkDuyet" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form method="POST" id="formBulkDuyet" action="/admin/dangkythuctap/duyet-bulk">@csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-semibold">
                                    <i class="bi bi-check2-square me-2"></i>Duyệt hàng loạt
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>Bạn muốn chuyển trạng thái của các đăng ký đã chọn?</p>

                                <select name="trang_thai" id="bulk_trang_thai" class="form-select mb-2">
                                    <option value="da_duyet">Duyệt (chờ duyệt → đã duyệt)</option>
                                    <option value="dang_thuctap">Chuyển sang đang thực tập</option>
                                    <option value="hoan_thanh">Hoàn thành thực tập</option>
                                </select>

                                <input type="hidden" name="ids" id="bulk_ids">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-success">Xác nhận</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            {{-- SweetAlert --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
            document.addEventListener("DOMContentLoaded", function() {

                // {
                //     {
                //         --Thông báo--
                //     }
                // }
                var successMessage = "{{ session('success') }}";
                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: successMessage,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                // {
                //     {
                //         -- === === === === === == LOAD CHI TIẾT === === === === === == --
                //     }
                // }
                document.getElementById('modalChiTiet').addEventListener('show.bs.modal', function(event) {
                    const id = event.relatedTarget.getAttribute('data-id');
                    const chiTietContent = document.getElementById('chiTietContent');
                    chiTietContent.innerHTML = `<div class="text-center text-muted py-3">
                            <i class="bi bi-arrow-repeat spin me-2"></i>Đang tải dữ liệu...
                        </div>`;

                    fetch(`/admin/dangkythuctap/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            chiTietContent.innerHTML = `
                                    <div class="p-2">
                                        <div class="py-2 border-bottom"><strong>Sinh viên:</strong> ${data.sinh_vien.ho_ten}</div>
                                        <div class="py-2 border-bottom"><strong>Vị trí:</strong> ${data.vi_tri_thuc_tap.ten_vitri}</div>
                                        <div class="py-2 border-bottom"><strong>Ngày đăng ký:</strong> ${data.ngay_dangky}</div>
                                        <div class="py-2"><strong>Trạng thái:</strong> ${data.trang_thai}</div>
                                    </div>`;
                        })
                        .catch(() => {
                            chiTietContent.innerHTML = `<div class="text-danger text-center py-3">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Lỗi khi tải dữ liệu.
                                </div>`;
                        });
                });

                // {
                //     {
                //         -- === === === === === === CẬP NHẬT ACTION === === === === = --
                //     }
                // }
                // document.getElementById('modalDuyet').addEventListener('show.bs.modal', e => {
                //     let id = e.relatedTarget.getAttribute('data-id');
                //     document.getElementById('formDuyet').action = `/admin/dangkythuctap/duyet/${id}`;
                // });
                document.getElementById('modalDuyet').addEventListener('show.bs.modal', e => {
                    let btn = e.relatedTarget;
                    let id = btn.getAttribute('data-id');
                    let row = btn.closest("tr");

                    let status = row.querySelector("span.badge").innerText
                        .trim()
                        .toLowerCase()
                        .replace(" ", "_");

                    let select = document.querySelector('#formDuyet select[name="trang_thai"]');

                    // reset trạng thái option
                    select.querySelector('option[value="da_duyet"]').disabled = false;

                    // Nếu đang "từ chối", không cho chọn duyệt
                    if (status === "tu_choi") {
                        select.value = "tu_choi"; // ép chọn từ chối
                        select.querySelector('option[value="da_duyet"]').disabled = true;
                    }

                    document.getElementById('formDuyet').action = `/admin/dangkythuctap/duyet/${id}`;
                });


                document.getElementById('modalCapNhat').addEventListener('show.bs.modal', e => {
                    let id = e.relatedTarget.getAttribute('data-id');
                    document.getElementById('formCapNhat').action =
                        `/admin/dangkythuctap/capnhat/${id}`;
                });

                document.getElementById('modalHuy').addEventListener('show.bs.modal', e => {
                    let id = e.relatedTarget.getAttribute('data-id');
                    document.getElementById('formHuy').action = `/admin/dangkythuctap/huy/${id}`;
                });

                // {
                //     {
                //         -- === === === === == CHECK ALL === === === === == --
                //     }
                // }
                document.getElementById("checkAll").addEventListener("change", function() {
                    let items = document.querySelectorAll(".checkItem");
                    items.forEach(chk => chk.checked = this.checked);
                });



            });
            // =======================
            // KIỂM TRA TRẠNG THÁI HỢP LỆ CHO BULK
            // =======================
            document.getElementById("btnBulkDuyet").addEventListener("click", function() {
                let selected = [...document.querySelectorAll(".checkItem:checked")].map(i => i.value);

                if (selected.length === 0) {
                    return Swal.fire({
                        icon: "warning",
                        title: "Chưa chọn!",
                        text: "Hãy chọn ít nhất một đăng ký.",
                    });
                }

                // Lấy trạng thái của từng dòng
                let trangThaiList = selected.map(id => {
                    let row = document.querySelector(`.checkItem[value="${id}"]`).closest("tr");
                    return row.querySelector("span.badge").innerText.trim().toLowerCase().replace(" ",
                        "_");
                });

                // Nếu có trạng thái không hợp lệ → CHỈ hiện cảnh báo, KHÔNG mở modal
                // Danh sách trạng thái không được phép duyệt hàng loạt
                const invalid = ["tu_choi", "hoan_thanh"];

                // Kiểm tra nếu TẤT CẢ các đăng ký đều thuộc danh sách không hợp lệ
                let allInvalid = trangThaiList.every(t => invalid.includes(t));

                if (allInvalid) {
                    return Swal.fire({
                        icon: "error",
                        title: "Không hợp lệ!",
                        text: "Tất cả các đăng ký được chọn đều có trạng thái không thể cập nhật hàng loạt.",
                    });
                }


                // Hợp lệ → mở modal duyệt hàng loạt
                document.getElementById("bulk_ids").value = selected.join(',');
                new bootstrap.Modal(document.getElementById("modalBulkDuyet")).show();
            });
            </script>
            <script>
            document.getElementById("formBulkDuyet").addEventListener("submit", function(e) {
                e.preventDefault();

                let newStatus = document.getElementById("bulk_trang_thai").value;
                let ids = document.getElementById("bulk_ids").value.split(",");

                const validTransitions = {
                    "cho_duyet": ["da_duyet"],
                    "da_duyet": ["dang_thuctap"],
                    "dang_thuctap": ["hoan_thanh"]
                };

                let validIds = [];
                let invalidItems = [];

                ids.forEach(id => {
                    let row = document.querySelector(`.checkItem[value="${id}"]`).closest("tr");
                    let oldStatus = row.querySelector("span.badge")
                        .innerText.trim().toLowerCase().replace(" ", "_");

                    // Hợp lệ → đưa vào danh sách thực sự cập nhật
                    if (validTransitions[oldStatus] && validTransitions[oldStatus].includes(
                            newStatus)) {
                        validIds.push(id);
                    }
                    // Không hợp lệ → chỉ lưu để hiển thị cảnh báo
                    else {
                        invalidItems.push({
                            id,
                            from: oldStatus,
                            to: newStatus
                        });
                    }
                });

                // Nếu tất cả đều không hợp lệ → báo lỗi + STOP
                if (validIds.length === 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Không có đăng ký hợp lệ!",
                        text: "Không có bản ghi nào đáp ứng quy tắc chuyển trạng thái.",
                    });
                    return;
                }

                // ✔ Nếu có bản ghi hợp lệ nhưng cũng có vài bản ghi không hợp lệ → cảnh báo nhẹ
                if (invalidItems.length > 0) {
                    let msg = "Một số đăng ký sẽ KHÔNG được cập nhật:\n\n";
                    invalidItems.forEach(i => {
                        msg += `• ${i.from} → ${i.to} (không hợp lệ)\n`;
                    });

                    Swal.fire({
                        icon: "warning",
                        title: "Một số đăng ký bị bỏ qua",
                        footer: `<pre style="text-align:left">${msg}</pre>`,
                        confirmButtonText: "Tiếp tục cập nhật"
                    }).then(() => {
                        submitValid(validIds);
                    });

                    return;
                }

                // Không có lỗi nào → submit trực tiếp
                submitValid(validIds);

            });

            // Hàm submit chỉ những ID hợp lệ
            function submitValid(validIds) {
                document.getElementById("bulk_ids").value = validIds.join(",");
                document.getElementById("formBulkDuyet").submit();
            }
            </script>

        </div>
    </div>
</div>
@endsection