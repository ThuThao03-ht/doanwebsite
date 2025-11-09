<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu - Hệ thống Quản lý Thực tập Doanh nghiệp</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        padding: 40px 20px;
        line-height: 1.6;
    }

    /* --- Khung chính quanh toàn bộ body --- */
    .body-frame {
        max-width: 720px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.08);
        border: 3px solid rgba(42, 82, 152, 0.4);
        border-radius: 24px;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.25), inset 0 0 20px rgba(42, 82, 152, 0.15);
        padding: 18px;
        backdrop-filter: blur(4px);
    }

    .email-wrapper {
        max-width: 650px;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 40px 30px;
        text-align: center;
        position: relative;
    }

    .header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .header h1 {
        color: #ffffff;
        font-size: 24px;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .content {
        padding: 40px 40px 30px;
    }

    .greeting {
        font-size: 20px;
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .message {
        color: #4a5568;
        font-size: 15px;
        margin-bottom: 15px;
    }

    .info-box {
        background: linear-gradient(135deg, #e6f2ff 0%, #d1e8ff 100%);
        border-left: 4px solid #2a5298;
        border-radius: 12px;
        padding: 20px;
        margin: 25px 0;
    }

    .info-box-title {
        color: #1e3c72;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .password-display {
        background: #ffffff;
        border: 2px solid #2a5298;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
    }

    .password-label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .password-text {
        font-size: 28px;
        font-weight: 700;
        color: #1e3c72;
        letter-spacing: 3px;
        font-family: 'Courier New', monospace;
    }

    .warning-box {
        background: #fff5f5;
        border-left: 4px solid #f56565;
        border-radius: 12px;
        padding: 16px;
        margin: 20px 0;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .warning-text {
        color: #742a2a;
        font-size: 14px;
    }

    .btn-container {
        text-align: center;
        margin: 30px 0 20px;
    }

    .btn {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff !important;
        padding: 14px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: inline-block;
        transition: 0.3s;
    }

    .btn:hover {
        background: linear-gradient(135deg, #2a5298 0%, #3b6bc6 100%);
        transform: translateY(-2px);
    }

    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 30px 0;
    }

    .help-section {
        background: #f7fafc;
        border-radius: 12px;
        padding: 20px;
    }

    .help-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #2d3748;
    }

    .footer {
        background: #f7fafc;
        padding: 30px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }

    .footer-text {
        color: #a0aec0;
        font-size: 13px;
        line-height: 1.6;
    }
    </style>
</head>

<body>
    <div class="body-frame">
        <div class="email-wrapper">
            <div class="header">
                <h1>Hệ thống Quản lý Thực tập Doanh nghiệp</h1>
            </div>

            <div class="content">
                <div class="greeting">Xin chào {{ $name ?? 'Sinh viên' }} 👋</div>

                <p class="message">
                    Chúng tôi đã nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn. Để đảm bảo an toàn, hệ
                    thống đã tạo một mật khẩu mới cho bạn.
                </p>

                <div class="info-box">
                    <div class="info-box-title">
                        <svg width="18" height="18" fill="#1e3c72" viewBox="0 0 24 24">
                            <path d="M14 3a5 5 0 0 0-4.9 6H2v4h4v4h4v4h4l1-1v-3h3l4-4a5 5 0 0 0-4-8h-2z" />
                        </svg>
                        <span>Mật khẩu mới của bạn</span>
                    </div>
                    <div class="password-display">
                        <div class="password-label">Mật khẩu tạm thời</div>
                        <div class="password-text">{{ $newPassword }}</div>
                    </div>
                </div>

                <div class="warning-box">
                    <svg width="22" height="22" fill="#f56565" viewBox="0 0 24 24">
                        <path d="M12 2 1 21h22L12 2zm1 14h-2v2h2v-2zm0-6h-2v4h2V10z" />
                    </svg>
                    <div class="warning-text">
                        Vui lòng đổi mật khẩu ngay sau khi đăng nhập để bảo vệ tài khoản
                        của bạn. Không chia sẻ mật khẩu này với bất kỳ ai.
                    </div>
                </div>

                <div class="btn-container">
                    <a href="{{ $loginUrl ?? url('/login') }}" class="btn">Đăng nhập ngay →</a>
                </div>

                <div class="divider"></div>

                <div class="help-section">
                    <div class="help-title">📞 Cần hỗ trợ?</div>
                    <p>Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng liên hệ với chúng tôi ngay lập tức để bảo vệ
                        tài khoản của bạn.
                        Bạn có thể liên hệ qua email hoặc số điện thoại hỗ trợ của trường.</p>
                </div>
            </div>

            <div class="footer">
                <div class="footer-text">
                    Đây là email tự động từ hệ thống, vui lòng không trả lời email này.<br>
                    © {{ date('Y') }} Hệ thống Quản lý Thực tập Doanh nghiệp<br>
                    Trường Đại học Đồng Tháp - Số 783, Phạm Hữu Lầu, Phường 6, TP. Cao Lãnh
                </div>
            </div>
        </div>
    </div>
</body>

</html>