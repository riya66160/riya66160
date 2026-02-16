# UrbanStep eCommerce (Production-Ready PHP + MySQL)

## 1) Folder Structure

```text
urbanstep/
├── .env.example
├── .htaccess
├── app/
│   ├── config/ (env + app config)
│   ├── core/ (DB, auth, CSRF, view, logger)
│   ├── controllers/ (home, products, cart, auth, checkout, dashboard, admin)
│   └── views/ (layout + pages)
├── config/
│   ├── apache-vhost.conf
│   └── nginx.conf
├── database/
│   └── schema.sql
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── robots.txt
│   ├── sitemap.xml
│   └── assets/
│       ├── css/styles.css
│       ├── js/app.js
│       └── uploads/
└── storage/logs/
```

## 2) Setup (.env)

1. Copy `.env.example` to `.env`.
2. Set strong DB credentials, app URL, and random `APP_KEY`.
3. Keep `APP_DEBUG=false` in production.

## 3) Localhost Deployment (XAMPP)

1. Place project in `htdocs/urbanstep`.
2. Enable Apache modules: `rewrite`, `headers`, `deflate`, `expires`.
3. Create DB and import `database/schema.sql`.
4. Update `.env` DB credentials.
5. Open `http://localhost/urbanstep`.

## 4) Shared Hosting Deployment

1. Upload all files; set web root to `public/` (preferred).
2. If host root cannot change, keep root `.htaccess` redirect enabled.
3. Create MySQL DB/user and import schema.
4. Set `.env`, disable debug, validate writable `storage/logs` and `public/assets/uploads`.
5. Add SSL cert in hosting panel and force HTTPS.

## 5) VPS Deployment (Ubuntu example)

1. Install stack: `nginx` or `apache2`, `php8.2-fpm`, `mysql-server`.
2. Clone to `/var/www/urbanstep`.
3. Use `config/nginx.conf` or `config/apache-vhost.conf` (edit domain/path).
4. `sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com` (or `--apache`).
5. Import schema and set `.env`.
6. Set permissions:
   - `storage/logs` writable by web user
   - `public/assets/uploads` writable for image uploads

## 6) Security + Production Optimization Included

- Environment secrets via `.env`
- PDO prepared statements
- CSRF token protection
- Session hardening (`httponly`, `samesite`, secure cookie on HTTPS)
- Password hashing using bcrypt
- Security headers (CSP, X-Frame-Options, nosniff)
- Error logging to `storage/logs/app.log`
- Gzip compression
- Browser caching headers
- robots.txt + sitemap.xml

## 7) SSL Setup Notes

- Use Let's Encrypt via Certbot.
- Auto-renew with cron/systemd timer.
- Redirect HTTP -> HTTPS (provided in Nginx config).

## 8) Performance Tips

- Use OPcache in php.ini.
- Enable HTTP/2 + Brotli (if available).
- Serve images as WebP and compress media.
- Add Redis for sessions/cart at higher scale.
- Move static assets to CDN.
- Add DB indexes based on real query plans.

## 9) Admin Access

Set a user `is_admin = 1` directly in DB after registration:

```sql
UPDATE users SET is_admin = 1 WHERE email='admin@example.com';
```
