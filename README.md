# BloodLink

Premium Laravel + Blade platform for emergency blood requests and donor coordination.

Features
- Server-rendered Laravel 12 backend
- Tailwind CSS + Alpine.js frontend (Blade templates)
- OAuth sign-in (Google)
- Phone OTP donor verification (Twilio)
- MongoDB (Atlas) data store

Quick Start (local - backend)

1. Copy environment file and set secrets:

```bash
cp backend/.env.example backend/.env
# Edit backend/.env: APP_URL, CLIENT_URL, DB_URI, GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, JWT_SECRET, TWILIO_*
```

2. Install PHP dependencies and run tests:

```bash
cd backend
composer install
php artisan key:generate
php artisan test
```

Quick Start (local - frontend)

```bash
cd frontend
npm install
npm run dev    # development
npm run build  # production build
```

Build and publish frontend to backend (recommended)

```bash
cd frontend
npm ci
npm run build
# copy build artifacts to backend public (example)
rm -rf ../backend/public/spa
mkdir -p ../backend/public/spa
cp -r dist/* ../backend/public/spa/
```

Environment variables (important)
- `APP_URL` = https://yourdomain.com (or http://127.0.0.1:8000 for local)
- `CLIENT_URL` = same as `APP_URL`
- `DB_URI` = MongoDB connection string
- `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` = Google OAuth credentials
- `JWT_SECRET` = application JWT secret
- `TWILIO_ACCOUNT_SID`, `TWILIO_AUTH_TOKEN`, `TWILIO_PHONE_NUMBER` = Twilio creds

Google OAuth notes
- Register authorized redirect URI in Google Cloud Console exactly as:
  `https://yourdomain.com/api/auth/google/callback` (use `127.0.0.1:8000` for local)
- Add `https://yourdomain.com` to Authorized JavaScript origins.

Running in production (summary)
- Provision a VPS (Ubuntu 22.04) or use Render/Fly
- Install PHP-FPM, Composer, Node, Nginx
- Clone repo, install composer/node deps, build frontend
- Configure Nginx to point to `backend/public`
- Obtain SSL (Certbot)
- Ensure Google redirect and Twilio credentials point to production domain

Helpful artisan commands
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

Developer notes
- UI is implemented in Blade components under `backend/resources/views/components`
- Dashboard fetches live requests from `/api/requests` and uses browser geolocation; use manual city input if location denied

Contributing
- Open a PR to `main`. Run `composer test` and `npm run build` when changing frontend code.

License
- MIT
