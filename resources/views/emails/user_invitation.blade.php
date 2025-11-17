<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Aktivasi Akun</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #6f6e7aff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #e5e7eb; }
        .button { display: inline-block; padding: 10px 20px; background-color: #6f6e7aff; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Undangan Aktivasi Akun</h1>
        </div>
        <div class="content">
            <p>Halo {{ $user->name }},</p>
            <p>Anda telah diundang untuk bergabung sebagai {{ ucfirst($user->role) }} di Sistem Kasir kami.</p>
            <p>Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
            <a href="{{ $activationUrl }}" class="button text-white" style="display:inline-block;padding:10px 20px;background-color:#6f6e7aff;color:#ffffff !important;text-decoration:none;border-radius:5px;margin:20px 0;">Aktifkan Akun</a>
            <p>Setelah mengklik link aktivasi, Anda akan diminta untuk membuat password dan akun Anda akan langsung aktif.</p>
            <p>Jika Anda tidak meminta undangan ini, abaikan email ini.</p>
            <p>Terima kasih,</p>
            <p>Owner sekaligus admin Sistem Anugrah Mandiri Kasir</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
        </div>
    </div>
</body>
</html>
