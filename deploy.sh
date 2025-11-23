#!/bin/bash

# Deploy Script for Laravel Application
# Jalankan script ini di VPS Bizznet Anda

# ====================================
# KONFIGURASI - Sesuaikan dengan setup Anda
# ====================================

PROJECT_PATH="/var/www/Kasir-Sistem"  # Ganti dengan path project Anda
REPO_URL="https://github.com/NIK78K/Kasir-Sistem.git"
BRANCH="main"

# ====================================
# DEPLOYMENT PROCESS
# ====================================

echo "🚀 Starting deployment..."

# Masuk ke directory project
cd $PROJECT_PATH || exit

# Enable maintenance mode
echo "📦 Enabling maintenance mode..."
php artisan down || true

# Pull latest code from repository
echo "📥 Pulling latest code..."
git fetch origin $BRANCH
git reset --hard origin/$BRANCH

# Install composer dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies and build assets
echo "📦 Installing NPM dependencies..."
npm ci

echo "🔨 Building assets..."
npm run build

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and optimize cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Restart queue workers (jika menggunakan queue)
# php artisan queue:restart

# Disable maintenance mode
echo "✅ Disabling maintenance mode..."
php artisan up

# Clear OPcache (so PHP doesn't keep serving old compiled files)
echo "🧠 Clearing OPcache (if enabled)..."
php -r "if(function_exists('opcache_reset')){ opcache_reset(); echo 'OPcache reset\n'; } else { echo 'No opcache present or function unavailable\n'; }"

# Try restarting PHP-FPM so opcode cache and file changes are picked up
echo "🔁 Restarting PHP-FPM (if service detected)..."
if command -v systemctl >/dev/null 2>&1; then
	for svc in php8.3-fpm php8.2-fpm php8.1-fpm php8.0-fpm php7.4-fpm php-fpm; do
		if systemctl list-units --full -all | grep -q "${svc}.service"; then
			echo "Restarting ${svc}..."
			systemctl restart ${svc}.service 2>/dev/null || sudo systemctl restart ${svc}.service 2>/dev/null || true
			echo "${svc} restarted (or attempted)."
			break
		fi
	done
fi

echo "🎉 Deployment completed successfully!"
echo "⏰ Deployed at: $(date)"
