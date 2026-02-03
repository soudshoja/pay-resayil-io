#!/bin/bash
#
# Collect Resayil.io - Automated Deployment Script
# Run this on the cPanel server after uploading files
#

set -e

echo "=========================================="
echo "  Collect Resayil.io - Deployment Script"
echo "=========================================="

# Configuration
DEPLOY_PATH="/home/resayili/collect.resayil.io"
DOMAIN="collect.resayil.io"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "Error: artisan file not found. Please run from Laravel root directory."
    echo "Expected path: $DEPLOY_PATH"
    exit 1
fi

# Step 1: Install Composer Dependencies
echo ""
echo "Step 1: Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev --no-interaction
    print_status "Composer dependencies installed"
else
    php -d memory_limit=-1 /usr/local/bin/composer install --optimize-autoloader --no-dev --no-interaction
    print_status "Composer dependencies installed (with memory override)"
fi

# Step 2: Setup Environment
echo ""
echo "Step 2: Setting up environment..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    print_status "Environment file created from .env.example"
else
    print_warning ".env file already exists, skipping copy"
fi

# Step 3: Generate Application Key
echo ""
echo "Step 3: Generating application key..."
php artisan key:generate --force
print_status "Application key generated"

# Step 4: Run Migrations
echo ""
echo "Step 4: Running database migrations..."
php artisan migrate --force
print_status "Database migrations complete"

# Step 5: Seed Database
echo ""
echo "Step 5: Seeding database with test data..."
php artisan db:seed --force
print_status "Database seeded"

# Step 6: Cache Configuration
echo ""
echo "Step 6: Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "Configuration cached"

# Step 7: Set Permissions
echo ""
echo "Step 7: Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
print_status "Permissions set"

# Step 8: Create storage link
echo ""
echo "Step 8: Creating storage link..."
php artisan storage:link 2>/dev/null || print_warning "Storage link may already exist"
print_status "Storage link created"

# Step 9: Clear any existing caches
echo ""
echo "Step 9: Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan config:cache
print_status "Caches cleared and rebuilt"

echo ""
echo "=========================================="
echo "  Deployment Complete!"
echo "=========================================="
echo ""
echo "Next Steps:"
echo "1. Configure subdomain in cPanel:"
echo "   - Subdomain: collect"
echo "   - Document Root: $DEPLOY_PATH/public"
echo ""
echo "2. Enable SSL:"
echo "   - Go to cPanel → SSL/TLS Status"
echo "   - Run AutoSSL for $DOMAIN"
echo ""
echo "3. Test the application:"
echo "   - Visit: https://$DOMAIN"
echo "   - Login: +96500000000 / admin123"
echo ""
echo "4. Check logs if issues:"
echo "   tail -f storage/logs/laravel.log"
echo ""
print_status "Deployment script finished successfully!"
