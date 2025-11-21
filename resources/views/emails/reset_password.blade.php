<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }
        .email-wrapper { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header { 
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white; 
            padding: 40px 30px; 
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="rgba(255,255,255,0.05)"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }
        .logo { 
            width: 80px; 
            height: 80px; 
            background: white;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: bold;
            color: #1e293b;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .header h1 { 
            font-size: 28px; 
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .header p { 
            font-size: 14px; 
            opacity: 0.9;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }
        .content { 
            padding: 40px 30px;
            background: #ffffff;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        .message {
            font-size: 15px;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 16px;
        }
        .info-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 24px 0;
        }
        .info-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .button { 
            display: inline-block; 
            padding: 16px 40px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 8px 20px rgba(30, 41, 59, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(30, 41, 59, 0.4);
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }
        .footer { 
            text-align: center; 
            padding: 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .footer p { 
            font-size: 13px; 
            color: #64748b;
            margin: 8px 0;
        }
        .footer-brand {
            font-weight: 700;
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background: #e2e8f0;
            border-radius: 50%;
            margin: 0 6px;
            line-height: 36px;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background: #1e293b;
            color: white;
            transform: translateY(-2px);
        }
        @media only screen and (max-width: 600px) {
            body { padding: 20px 10px; }
            .header { padding: 30px 20px; }
            .header h1 { font-size: 24px; }
            .content { padding: 30px 20px; }
            .greeting { font-size: 18px; }
            .message { font-size: 14px; }
            .button { padding: 14px 32px; font-size: 15px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="logo">AK</div>
            <h1>Reset Password</h1>
            <p>Sistem Anugrah Mandiri Kasir</p>
        </div>
        <div class="content">
            <p class="greeting">Halo! 👋</p>
            
            <p class="message">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
            </p>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">
                    🔐 Reset Password
                </a>
            </div>
            
            <div class="info-box">
                <p>
                    <strong>⚠️ Penting:</strong><br>
                    Link reset password ini akan kadaluarsa dalam 60 menit. Silakan segera reset password Anda.
                </p>
            </div>
            
            <div class="divider"></div>
            
            <p class="message">
                <strong>ℹ️ Informasi Penting:</strong>
            </p>
            <p class="message">
                • Link ini hanya dapat digunakan satu kali<br>
                • Jika Anda tidak meminta reset password, abaikan email ini<br>
                • Password Anda tidak akan berubah sampai Anda membuat password baru
            </p>
            
            <div class="divider"></div>
            
            <p class="message">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
            </p>
            <p class="message" style="word-break: break-all; color: #0ea5e9; font-size: 13px;">
                {{ $resetUrl }}
            </p>
        </div>
        <div class="footer">
            <p class="footer-brand">AMKAS</p>
            <p>Sistem Kasir Modern untuk Bisnis Anda</p>
            <p style="margin-top: 16px;">© {{ date('Y') }} Anugrah Mandiri. All rights reserved.</p>
            <p style="font-size: 12px; color: #94a3b8; margin-top: 12px;">
                Email ini dikirim secara otomatis. Mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
