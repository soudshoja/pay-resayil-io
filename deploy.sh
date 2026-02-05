#!/bin/bash
echo "🚀 Deploying..."
cd ~/collect.resayil.io
git pull origin main
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Done!"
