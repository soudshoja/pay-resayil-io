# Collect Resayil.io - Multi-Tenant Payment Collection Platform

## Project Overview
- **Name:** collect.resayil.io
- **Type:** Laravel 11 Multi-Tenant SaaS
- **Purpose:** Travel agency payment collection platform with WhatsApp automation
- **Stack:** Laravel 11, MySQL, MyFatoorah KNET, Resayil WhatsApp API
- **Deployment:** cPanel at resayil.io
- **UI:** Bold & Modern dark theme with purple/pink gradients

## Quick Start

```bash
# Install dependencies
composer install

# Copy environment file (already configured)
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed

# Start development server
php artisan serve
```

## Database Credentials
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=resayili_resayil_gateway
DB_USERNAME=resayili_resayil_user
DB_PASSWORD=Resayil2025!Gateway
```

## API Credentials

### Resayil WhatsApp API
```
RESAYIL_BASE_URL=https://wa.resayil.io/api/v1
RESAYIL_API_KEY=f0bd277a312a53381db25d5af1e3a5c23f5dc869a8d4d667aab54a53293334adc4764ce2dadcfe87
```

### MyFatoorah (Test Mode)
```
MYFATOORAH_BASE_URL=https://apitest.myfatoorah.com
MYFATOORAH_API_KEY=SK_KWT_wI6G2SOOeAogGyudgauY0dAEQAneSTSkSGMT8s49yyZzcKIK8MMw2bU9cj6VpiCo
MYFATOORAH_TEST_MODE=true
```

## Test Accounts (after seeding)

| Role | Phone | Password | Agency |
|------|-------|----------|--------|
| Super Admin | +96500000000 | admin123 | - |
| Admin | +96511111111 | password123 | City Tours |
| Accountant | +96522222222 | password123 | City Tours |
| Agent | +96533333333 | password123 | City Tours |

## Platform Owner
| Email | Password |
|-------|----------|
| soud@alphia.net | (set during setup) |

## API Endpoints

### n8n Webhooks
```
POST /api/n8n/incoming-message    # Handle WhatsApp incoming
POST /api/n8n/generate-payment    # Generate payment link
GET  /api/n8n/payment/{id}/status # Get payment status
```

### MyFatoorah Webhooks
```
GET  /api/myfatoorah/callback     # Payment callback
POST /api/myfatoorah/webhook      # Server webhook
```

## User Roles
- **super_admin:** All access, manage agencies
- **admin:** Manage agency, team, settings
- **accountant:** View payments, receive notifications
- **agent:** Create payments only

## Platform Admin URLs
- Login: https://collect.resayil.io/platform/login
- Dashboard: https://collect.resayil.io/platform/dashboard
- Agencies: https://collect.resayil.io/platform/agencies
- Users: https://collect.resayil.io/platform/users
- Payments: https://collect.resayil.io/platform/payments
- Settings: https://collect.resayil.io/platform/settings

## Build Date
- Started: 2026-02-03
- Status: Complete
