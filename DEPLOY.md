# Collect Resayil.io - Deployment Guide

## Quick Deployment Steps

### Option 1: cPanel File Manager (Easiest)

1. **Login to cPanel**: https://resayil.io:2083
   - Username: `resayili`
   - Password: `Resayil00+`

2. **Upload ZIP File**:
   - Go to **File Manager**
   - Navigate to `/home/resayili/`
   - Create folder: `collect.resayil.io`
   - Upload `collect-resayil-io.zip` to `/home/resayili/collect.resayil.io/`
   - Right-click → **Extract**

3. **Configure Subdomain**:
   - Go to **Subdomains**
   - Create: `collect.resayil.io`
   - Document Root: `/home/resayili/collect.resayil.io/public`

4. **Run Setup via Terminal**:
   - Go to **Terminal** in cPanel
   - Run these commands:

```bash
cd ~/collect.resayil.io

# Install dependencies
composer install --optimize-autoloader --no-dev

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework

echo "Deployment complete!"
```

### Option 2: SSH Terminal Deployment

1. **Connect via SSH**:
```bash
ssh resayili@resayil.io
# Password: Resayil00+
```

2. **Run deployment script**:
```bash
# Create directory
mkdir -p ~/collect.resayil.io
cd ~/collect.resayil.io

# Clone from GitHub
git clone https://github.com/soudshoja/collect-resayil-io.git .

# OR upload zip and extract manually

# Install dependencies
composer install --optimize-autoloader --no-dev

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure .env (edit database credentials)
nano .env

# Run migrations
php artisan migrate --force
php artisan db:seed --force

# Cache and permissions
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 755 storage bootstrap/cache
```

### Option 3: Git Deployment (After GitHub Setup)

1. **Create GitHub Personal Access Token**:
   - Go to: https://github.com/settings/tokens/new
   - Note: `collect-resayil-io`
   - Scopes: `repo` (full access)
   - Generate and copy token

2. **Create Repository** (run locally):
```bash
curl -H "Authorization: token YOUR_TOKEN" \
     -d '{"name":"collect-resayil-io","private":true}' \
     https://api.github.com/user/repos
```

3. **Push Code**:
```bash
cd ~/projects/pay-resayil-io
git remote set-url origin https://github.com/soudshoja/collect-resayil-io.git
git push -u origin master
```

4. **Clone on Server**:
```bash
ssh resayili@resayil.io
cd ~
git clone https://github.com/soudshoja/collect-resayil-io.git collect.resayil.io
cd collect.resayil.io
composer install --optimize-autoloader --no-dev
# ... continue with setup
```

---

## Post-Deployment Verification

### 1. Check Application
- Visit: https://collect.resayil.io
- Should see login page with dark theme

### 2. Test Login
After seeding, test accounts:
| Role | Phone | Password |
|------|-------|----------|
| Super Admin | +96500000000 | admin123 |
| Admin | +96511111111 | password123 |

### 3. Verify Database
```bash
php artisan tinker
>>> App\Models\User::count()
>>> App\Models\Agency::count()
```

### 4. Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

## Environment Configuration

The `.env` file is pre-configured with:

```
DB_DATABASE=resayili_resayil_gateway
DB_USERNAME=resayili_resayil_user
DB_PASSWORD=Resayil2025!Gateway

RESAYIL_API_KEY=f0bd277a312a53381db25d5af1e3a5c23f5dc869a8d4d667aab54a53293334adc4764ce2dadcfe87
MYFATOORAH_API_KEY=SK_KWT_wI6G2SOOeAogGyudgauY0dAEQAneSTSkSGMT8s49yyZzcKIK8MMw2bU9cj6VpiCo
```

If database credentials differ, update `.env` accordingly.

---

## SSL Certificate

1. Go to cPanel → **SSL/TLS Status**
2. Select `collect.resayil.io`
3. Click **Run AutoSSL**

---

## Troubleshooting

### 500 Error
```bash
chmod -R 775 storage bootstrap/cache
php artisan cache:clear
```

### Database Connection Error
- Verify database exists in cPanel → MySQL Databases
- Check credentials in `.env`
- Ensure user has privileges on database

### Composer Memory Error
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

---

## Files Location

- **ZIP Archive**: `C:\Users\User\projects\collect-resayil-io.zip`
- **Project**: `C:\Users\User\projects\pay-resayil-io\` (local dev folder)
- **Server Path**: `/home/resayili/collect.resayil.io/`
- **Document Root**: `/home/resayili/collect.resayil.io/public/`
