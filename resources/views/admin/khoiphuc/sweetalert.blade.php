<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const success = document.querySelector('meta[name="success-message"]').getAttribute('content');
    const error = document.querySelector('meta[name="error-message"]').getAttribute('content');

    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: success,
            timer: 2000,
            showConfirmButton: false
        });
    }
    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: error,
            timer: 2000,
            showConfirmButton: false
        });
    }
});
</script>