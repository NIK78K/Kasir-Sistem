# Cara Memastikan Perubahan Langsung Terlihat

## Masalah yang Terjadi:
Deploy berhasil tapi website menampilkan versi lama karena **cache**.

## Solusi:

### 1. **Clear Browser Cache** (Paling Penting!)
Saat mengakses website setelah deploy, gunakan:

**Windows/Linux:**
- Chrome/Edge: `Ctrl + Shift + R` atau `Ctrl + F5`
- Firefox: `Ctrl + Shift + R`

**Mac:**
- Chrome/Edge: `Cmd + Shift + R`
- Safari: `Cmd + Option + R`

**Atau Hard Refresh:**
1. Buka Developer Tools (`F12`)
2. Klik kanan tombol refresh
3. Pilih "Empty Cache and Hard Reload"

### 2. **Incognito/Private Mode**
Buka website di mode incognito untuk bypass cache:
- Chrome: `Ctrl + Shift + N`
- Firefox: `Ctrl + Shift + P`

### 3. **Clear Cache di Browser Settings**
Chrome/Edge:
1. Settings → Privacy and Security
2. Clear browsing data
3. Pilih "Cached images and files"
4. Time range: "All time"
5. Clear data

### 4. **Cek di Terminal (Pastikan file benar-benar berubah)**
```bash
ssh -i "/c/ssh-key/server-kunci.pem" amkas6@103.126.117.143

# Cek timestamp file
cd /var/www/Kasir-Sistem/resources/views
ls -lh welcome.blade.php

# Lihat isi file
tail -20 welcome.blade.php

# Cek PHP-FPM status
sudo systemctl status php8.3-fpm
```

### 5. **Force Clear Semua Cache Manual**
Jika masih tidak berubah, jalankan di VPS:
```bash
cd /var/www/Kasir-Sistem

# Clear semua cache
php artisan optimize:clear
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/data/*
rm -rf bootstrap/cache/*.php

# Rebuild
php artisan optimize

# Hard restart PHP-FPM
sudo systemctl stop php8.3-fpm
sudo pkill -9 php-fpm
sudo systemctl start php8.3-fpm

# Reload Nginx
sudo systemctl reload nginx
```

## Tips Deployment:

### Workflow yang Benar:
1. Edit code di local
2. Commit & push ke GitHub
3. Tunggu GitHub Actions selesai (lihat di tab Actions)
4. **Tunggu 5-10 detik** setelah deployment selesai
5. Buka website dengan **Hard Refresh** (`Ctrl + Shift + R`)

### Cek Deployment Berhasil:
Di GitHub Actions, pastikan ada log:
```
✅ Deployment completed successfully!
✅ Version: 20251123_xxxxx
```

### Jangan Lupa:
- **Selalu Hard Refresh** browser setelah deployment
- **Gunakan Incognito** untuk testing
- **Cek timestamp file** di VPS jika ragu
- **Restart PHP-FPM** sudah otomatis di CI/CD sekarang

## Debugging:

Jika perubahan tetap tidak terlihat:

```bash
# 1. Cek apakah file benar-benar ter-update
ssh -i "/c/ssh-key/server-kunci.pem" amkas6@103.126.117.143
cd /var/www/Kasir-Sistem
git log -1 --oneline
date -r resources/views/welcome.blade.php

# 2. Test dengan menambahkan timestamp
echo "<!-- Updated: $(date) -->" >> resources/views/welcome.blade.php
cat resources/views/welcome.blade.php | tail -5

# 3. Clear PHP opcache
php -r "opcache_reset();" 2>/dev/null || true
sudo systemctl restart php8.3-fpm

# 4. Check nginx config
sudo nginx -t
sudo systemctl status nginx
```

## Sekarang Sudah Diperbaiki:

✅ PHP-FPM di-restart dengan `pkill -9` (force kill)
✅ Sleep delay untuk memastikan service restart complete
✅ View cache di-clear manual (delete files)
✅ Opcache pasti ter-reset

**Setelah deployment selesai, selalu gunakan `Ctrl + Shift + R` untuk hard refresh browser!**
