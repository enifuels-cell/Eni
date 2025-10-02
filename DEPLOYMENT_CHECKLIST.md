# ✅ ENI Investment Platform - Deployment Checklist

**Date**: _____________  
**Deployed By**: _____________  
**Environment**: □ Staging  □ Production

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### 1. Environment Configuration ⚠️ CRITICAL

- [ ] Copy `.env.production` to server as `.env`
- [ ] Update `APP_URL` to production domain (e.g., `https://eni.com`)
- [ ] Verify `APP_ENV=production`
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `LOG_LEVEL=error`
- [ ] Generate unique `APP_KEY` if not already set: `php artisan key:generate`
- [ ] Configure `MAIL_PASSWORD` with actual Gmail App Password
- [ ] Set `SESSION_SECURE_COOKIE=true` (if using HTTPS)
- [ ] Verify `SESSION_DOMAIN` is set correctly (or null)

### 2. Database Preparation

- [ ] Backup existing database (if upgrading)
- [ ] Test migrations on staging environment first
- [ ] Verify database credentials in `.env`
- [ ] Ensure database user has proper permissions

### 3. Code Preparation

- [ ] All debug routes are gated with environment check
- [ ] No sensitive data hardcoded in code
- [ ] All dependencies up to date: `composer update`
- [ ] Frontend assets compiled: `npm run build`
- [ ] Git repository is clean (committed all changes)
- [ ] Tagged release version: `git tag v1.0.0`

### 4. Security Review

- [ ] All forms have `@csrf` directive
- [ ] All sensitive routes have authentication middleware
- [ ] File upload validation is in place
- [ ] SQL injection prevention (using Eloquent/Query Builder)
- [ ] XSS protection (Blade escapes by default)
- [ ] Rate limiting configured for sensitive endpoints
- [ ] Strong password requirements enforced
- [ ] PIN hashing implemented correctly

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Server Preparation

- [ ] SSH into production server
- [ ] Navigate to project directory: `cd /var/www/your-project`
- [ ] Create backup directory: `mkdir -p backups/$(date +%Y%m%d)`
- [ ] Backup current installation:

  ```bash
  cp -r . backups/$(date +%Y%m%d)/
  cp .env backups/$(date +%Y%m%d)/.env.backup
  cp database/database.sqlite backups/$(date +%Y%m%d)/database.sqlite.backup
  ```

### Step 2: Code Deployment

- [ ] Pull latest code: `git pull origin main` (or upload files via FTP)
- [ ] Copy `.env.production` to `.env`: `cp .env.production .env`
- [ ] Install dependencies: `composer install --no-dev --optimize-autoloader`
- [ ] Install npm packages (if needed): `npm ci --production`
- [ ] Build frontend assets: `npm run build`

### Step 3: Directory Permissions

- [ ] Set storage permissions: `chmod -R 775 storage`
- [ ] Set bootstrap cache permissions: `chmod -R 775 bootstrap/cache`
- [ ] Set owner to web server user:

  ```bash
  chown -R www-data:www-data storage bootstrap/cache
  # Or for Apache: chown -R apache:apache storage bootstrap/cache
  ```

### Step 4: Database Migration

- [ ] Backup database again (extra safety)
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Verify migration status: `php artisan migrate:status`
- [ ] Seed investment packages (first install only): `php artisan db:seed --class=InvestmentPackageSeeder`

### Step 5: Laravel Optimization

- [ ] Clear all caches:

  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  php artisan view:clear
  ```

- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Create storage link: `php artisan storage:link`

### Step 6: Web Server Configuration

- [ ] Configure virtual host for domain
- [ ] Point document root to `/public` directory
- [ ] Enable mod_rewrite (Apache) or configure try_files (Nginx)
- [ ] Configure SSL/HTTPS certificate
- [ ] Restart web server:

  ```bash
  # Apache
  sudo systemctl restart apache2
  
  # Nginx
  sudo systemctl restart nginx
  ```

### Step 7: Cron Job Setup (Scheduled Tasks)

- [ ] Open crontab: `crontab -e`
- [ ] Add Laravel scheduler:

  ```bash
  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
  ```

- [ ] Verify cron is running: `crontab -l`

---

## 🧪 POST-DEPLOYMENT TESTING

### Critical User Flows

- [ ] **Homepage loads** without errors
- [ ] **User Registration**:
  - [ ] Register new account
  - [ ] Receive welcome email (if configured)
  - [ ] Account created in database
- [ ] **User Login**:
  - [ ] Login with email/password
  - [ ] Redirected to dashboard
  - [ ] Session persists across page refreshes
- [ ] **PIN Authentication**:
  - [ ] Set up PIN in Profile Settings
  - [ ] Logout and login with PIN
  - [ ] PIN validation works correctly
- [ ] **Investment Package**:
  - [ ] View available packages
  - [ ] Select package and invest
  - [ ] Create bank transfer deposit
  - [ ] View receipt
  - [ ] Slots decrement correctly
- [ ] **Admin Functions**:
  - [ ] Login as admin
  - [ ] View pending deposits
  - [ ] Approve bank transfer
  - [ ] Investment becomes active
  - [ ] User balance updates
- [ ] **CSRF Protection**:
  - [ ] Forms submit successfully
  - [ ] No 419 errors on normal usage
  - [ ] Token refreshes automatically
- [ ] **Session Management**:
  - [ ] Session persists for 12 hours
  - [ ] Logout works correctly
  - [ ] Session invalidated on logout

### Security Tests

- [ ] Debug routes **NOT accessible** in production:
  - [ ] `/test` returns 404
  - [ ] `/debug-investment` returns 404
  - [ ] `/debug-packages` returns 404
  - [ ] `/prod-debug` returns 404
  - [ ] `/debug-auth` returns 404
  - [ ] `/session-test` returns 404
- [ ] Error pages **DO NOT** show stack traces:
  - [ ] Trigger 404 error - should show custom page
  - [ ] Trigger 500 error - should show generic error page
  - [ ] No database query information exposed
- [ ] HTTPS is enforced (HTTP redirects to HTTPS)
- [ ] File uploads are validated (type, size)
- [ ] Rate limiting works on sensitive endpoints

### Performance Tests

- [ ] Page load time under 3 seconds
- [ ] No PHP errors in logs: `tail -f storage/logs/laravel.log`
- [ ] No web server errors:

  ```bash
  # Apache
  tail -f /var/log/apache2/error.log
  
  # Nginx
  tail -f /var/log/nginx/error.log
  ```

- [ ] Database queries optimized (check query log if enabled)

### Email Tests

- [ ] Password reset email sends successfully
- [ ] Welcome email sends (if configured)
- [ ] Investment notification emails send
- [ ] Admin notification emails send

---

## 📊 MONITORING SETUP

### Application Monitoring

- [ ] Configure error logging service (e.g., Sentry, Bugsnag)
- [ ] Set up log rotation:

  ```bash
  # Add to /etc/logrotate.d/laravel
  /path-to-project/storage/logs/*.log {
      daily
      missingok
      rotate 14
      compress
      notifempty
      create 0640 www-data www-data
      sharedscripts
  }
  ```

- [ ] Set up disk space monitoring
- [ ] Configure uptime monitoring (UptimeRobot, Pingdom, etc.)

### Database Monitoring

- [ ] Set up automated database backups:

  ```bash
  # Add to crontab
  0 2 * * * cp /path-to-project/database/database.sqlite /backups/db-$(date +\%Y\%m\%d).sqlite
  ```

- [ ] Monitor database size growth
- [ ] Set up retention policy for old backups

### Performance Monitoring

- [ ] Configure APM tool (New Relic, Datadog, etc.)
- [ ] Set up query monitoring
- [ ] Monitor server resources (CPU, RAM, disk)

---

## 🔄 ROLLBACK PLAN

If critical issues occur after deployment:

### Quick Rollback (5 minutes)

1. [ ] Stop web server: `sudo systemctl stop apache2` or `nginx`
2. [ ] Restore code from backup:

   ```bash
   cp -r backups/$(date +%Y%m%d)/* .
   ```

3. [ ] Restore `.env`: `cp backups/$(date +%Y%m%d)/.env.backup .env`
4. [ ] Restore database:

   ```bash
   cp backups/$(date +%Y%m%d)/database.sqlite.backup database/database.sqlite
   ```

5. [ ] Clear caches:

   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

6. [ ] Start web server: `sudo systemctl start apache2` or `nginx`
7. [ ] Verify site is working

### Database Rollback (if migrations failed)

1. [ ] Rollback specific migration:

   ```bash
   php artisan migrate:rollback --step=1
   ```

2. [ ] Or restore from backup:

   ```bash
   cp backups/$(date +%Y%m%d)/database.sqlite.backup database/database.sqlite
   ```

---

## 📞 EMERGENCY CONTACTS

| Role | Name | Phone | Email |
|------|------|-------|-------|
| Primary Developer | ___________ | ___________ | ___________ |
| DevOps Engineer | ___________ | ___________ | ___________ |
| System Administrator | ___________ | ___________ | ___________ |
| Project Manager | ___________ | ___________ | ___________ |

---

## 📝 POST-DEPLOYMENT NOTES

**Deployment Date**: _____________  
**Deployment Duration**: _____________  
**Issues Encountered**:

_____________________________________________

_____________________________________________

_____________________________________________

**Resolution**:

_____________________________________________

_____________________________________________

_____________________________________________

**Final Status**: □ Success  □ Partial  □ Rolled Back

---

## 🎯 NEXT STEPS

After successful deployment:

- [ ] Update project documentation with production details
- [ ] Document any configuration changes made during deployment
- [ ] Schedule post-deployment review meeting
- [ ] Monitor application for 24-48 hours
- [ ] Collect user feedback
- [ ] Plan next release/updates

---

**Deployment Completed By**: _____________  
**Sign-off Date**: _____________  
**Signature**: _____________
