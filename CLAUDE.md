# Claude Code Project Instructions

## This Project
Collect Resayil Gateway - Multi-tenant KNET payment platform for travel agencies.
Laravel 11 + Tailwind + Alpine.js + MyFatoorah + WhatsApp automation.

## Skills to Read
Before making changes, read these skill files:
- ~/.claude/skills/laravel-resayil-gateway/SKILL.md (MAIN - read first)
- ~/.claude/skills/myfatoorah-integration/SKILL.md (payment gateway)
- ~/.claude/skills/resayil-whatsapp-api/SKILL.md (WhatsApp API)

## Credentials
All secrets in: ~/.secrets/resayil-gateway.env
NEVER hardcode credentials. NEVER commit secrets to Git.

## Key Rules
1. Read laravel-resayil-gateway SKILL.md ENTIRELY before any changes
2. Check Laravel logs: tail -50 storage/logs/laravel.log
3. Test changes on ONE page before applying everywhere
4. Blade uses @extends pattern, NOT components - don't mix
5. SSH blocked by Cloudflare on prod - deploy via GitHub + cPanel
6. Last working commit: 358c07a

## Deploy to Production
```bash
# On cPanel Terminal:
cd ~/collect.resayil.io
git pull origin main
php artisan view:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear
```

## MCP Tools
- context7: "use context7" for Laravel/Tailwind live docs
- playwright: "test login with playwright" for browser testing
- figma: paste Figma link + "implement this design"
- github: manage PRs, issues, branches

## Tech Stack
- Backend: Laravel 11, PHP 8.2+, MySQL 8.0
- Frontend: Blade + Tailwind CSS (CDN) + Alpine.js (CDN)
- Theme: Dark (#0a0a0f), purple/pink gradients, glass morphism
- Fonts: Space Grotesk (LTR), Tajawal (RTL)
- APIs: MyFatoorah KNET, Resayil WhatsApp, n8n

## SSH Access to Production (cPanel)
- Alias: `ssh cpanel` (passwordless)
- Real IP: 152.53.86.223 (bypasses Cloudflare)
- User: resayili
- Project path on cPanel: ~/collect.resayil.io
- Deploy: `~/deploy-resayil.sh` (push to GitHub + pull on cPanel + clear caches)
- Check logs: `ssh cpanel 'tail -50 ~/collect.resayil.io/storage/logs/laravel.log'`
- Run artisan: `ssh cpanel 'cd ~/collect.resayil.io && php artisan migrate:status'`
