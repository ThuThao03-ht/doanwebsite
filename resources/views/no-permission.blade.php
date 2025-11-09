<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không có quyền truy cập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-center py-5">

    <div class="container">
        <h1 class="text-danger mb-4">🚫 Bạn không có quyền truy cập trang này</h1>
        <p class="mb-4">Vui lòng đăng nhập bằng tài khoản có quyền phù hợp.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Quay lại đăng nhập</a>
    </div>

</body>

</html>