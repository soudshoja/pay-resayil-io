#!/bin/bash
#
# Collect Resayil.io - Phase 2 Deployment Script
# Multi-Tier Database Migration
#
# Run this on the server after Phase 1 deployment
#

set -e

echo "=========================================="
echo "  Phase 2: Multi-Tier Database Migration"
echo "=========================================="

# Configuration
DEPLOY_PATH="/home/resayili/collect.resayil.io"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Check if we're in the right directory
cd $DEPLOY_PATH || {
    print_error "Cannot access $DEPLOY_PATH"
    exit 1
}

if [ ! -f "artisan" ]; then
    print_error "artisan file not found. Not a Laravel project."
    exit 1
fi

# Step 1: Pull latest code
echo ""
echo "Step 1: Pulling latest code from GitHub..."
git pull origin main
print_status "Code updated"

# Step 2: Clear existing caches
echo ""
echo "Step 2: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_status "Caches cleared"

# Step 3: Run migrations
echo ""
echo "Step 3: Running database migrations..."
php artisan migrate --force
print_status "Migrations complete"

# Step 4: Run Multi-Tier Seeder
echo ""
echo "Step 4: Seeding multi-tier test data..."
php artisan db:seed --class=MultiTierSeeder --force
print_status "Multi-tier data seeded"

# Step 5: Rebuild caches
echo ""
echo "Step 5: Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "Caches rebuilt"

# Step 6: Set permissions
echo ""
echo "Step 6: Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
print_status "Permissions set"

echo ""
echo "=========================================="
echo "  Phase 2 Migration Complete!"
echo "=========================================="
echo ""
echo "New Database Structure:"
echo "  - clients (renamed from agencies)"
echo "  - agents (travel agencies)"
echo "  - agent_authorized_phones"
echo "  - whatsapp_keywords"
echo "  - transaction_notes"
echo ""
echo "Test Client: Fly Dubai"
echo "Test Agent: City Travelers (IATA12345)"
echo "Authorized Phone: +96599800027"
echo ""
echo "Test Accounts:"
echo "  Client Admin:  +96550000002 / password123"
echo "  Sales Person:  +96550000003 / password123"
echo "  Accountant:    +96550000004 / password123"
echo ""
print_status "Phase 2 deployment finished successfully!"
