#!/bin/bash

# Manual Check & Fix Deployment Script
# Jalankan ini di VPS jika deployment tidak update

PROJECT_PATH="/var/www/Kasir-Sistem"

echo "🔍 Checking deployment status..."

cd $PROJECT_PATH

# Check git status
echo ""
echo "📁 Current files:"
ls -lah

# Check last modification
echo ""
echo "🕐 Last modified files:"
find . -type f -name "*.php" -mmin -30 | head -10

# Check if files are actually updated
echo ""
echo "📝 Recent commits:"
git log --oneline -5 || echo "Not a git repository"

# Clear all caches manually
echo ""
echo "🧹 Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Rebuild cache
echo ""
echo "⚡ Building cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check permissions
echo ""
echo "🔒 Checking permissions:"
ls -l storage/
ls -l bootstrap/cache/

# Restart services
echo ""
echo "🔄 Restarting services..."
sudo systemctl restart php8.3-fpm || sudo systemctl restart php-fpm
sudo systemctl reload nginx

echo ""
echo "✅ Done! Check your website now."
