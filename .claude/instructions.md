# Collect Resayil - Project Instructions

## Quick Reference

| Item | Value |
|------|-------|
| **Path** | ~/collect.resayil.io |
| **URL** | https://collect.resayil.io |

## Database
```bash
mysql -u resayili_resayil_user -p'Resayil2025!Gateway' resayili_resayil_gateway
```

## Skills to Read
1. myfatoorah-integration
2. laravel-resayil-gateway
3. resayil-whatsapp-api

## Quick Commands
```bash
# Clear caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# View logs
tail -50 storage/logs/laravel.log
```

## Test Payment
https://collect.resayil.io/pay/CR-CPWBPIJ6

## Logins
- Platform: soud@alphia.net / Resayil2026Admin
- Test Client: admin@flydubai.test / password
