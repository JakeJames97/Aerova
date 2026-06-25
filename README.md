# Xpedition

A small full-stack application for planning trips, built to demonstrate a Laravel API working with a Vue 3 single-page frontend and a relational database.

---

## Stack

**Backend**
- Laravel (PHP 8.5), served via Laravel Sail (Docker)
- MySQL Database
- Sanctum token authentication
- Saloon for the 3rd party exchange-rate integration

**Frontend**
- Vue 3 / TypeScript
- Pinia for state management
- Vue Router
- VeeValidate + Yup for form validation
- Vite
- Axios for data fetching from the API
- Sass for styling

**Tooling**
- PHPUnit (feature + unit tests)
- Larastan / PHPStan (static analysis)
- Pint / Eslint (code style)

---

## Application Setup

This project runs entirely through Laravel Sail, so the only host requirement is Docker.

```bash
# 1. Clone
git clone <REPO_URL>
cd xpedition

# 2. Environment
cp .env.example .env

# 3. Composer Install
composer install

# 4. Start the containers
./vendor/bin/sail up -d

# 5. App key, database, seed data
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed

# 6. Frontend
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# 7. Running the test suite
./vendor/bin/sail artisan test
```
