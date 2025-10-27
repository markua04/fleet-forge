<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Fleet Forge

Laravel 12 application scaffolded for production-style deployments. The repository includes a multi-stage Docker build that compiles frontend assets (Vue + Tailwind), installs production PHP dependencies, and serves the app through PHP-FPM behind nginx with a MySQL database.

### Prerequisites

- Docker Engine 24+ (or Docker Desktop on macOS/Windows)
- Docker Compose V2 (bundled with recent Docker installations)
- Open ports `8080` (HTTP) and `3306` (MySQL) on your host machine

### Quick Start

1. Copy the environment template and fill in secrets if needed:
   ```bash
   cp .env.example .env
   ```
2. Start the stack (builds the image, runs PHP-FPM, nginx, and MySQL):
   ```bash
   docker compose up -d --build
   ```
3. Ensure the app key exists (skip if `APP_KEY` is already set in `.env`):
   ```bash
   docker compose exec app php artisan key:generate --force
   ```
4. Run database migrations (and optionally seeders) against the containerised MySQL instance:
   ```bash
   docker compose exec app php artisan migrate --force
   # docker compose exec app php artisan db:seed --force
   ```
5. Visit the application at [http://localhost:8080](http://localhost:8080).

Because the image prebuilds Vite assets, no frontend watcher is required. Any application code changes require rebuilding the image:

```bash
docker compose up -d --build
```

### Seed Data

Run the migrations and seeders to load a starter user and a catalogue of trucks:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Seeders create:
- `test@example.com` with `cash` balance `600000.00`
- Ten truck records (Volvo, Scania, Kenworth, etc.) with realistic VIN/price data

### API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| `GET`  | `/api/users/{user}` | Fetch a single user with balance and assigned vehicles |
| `POST` | `/api/users/{user}/vehicles` | Purchase/assign a vehicle to the user; deducts cash if the user can afford it |

The Bruno collection under `collection/FleetForge/` includes ready-made requests (`Get User.bru`, `Purchase Vehicle.bru`) pointing at `http://127.0.0.1:8000`.

### Useful Commands

- Follow logs: `docker compose logs -f nginx` (web) or `docker compose logs -f app` (PHP)
- Run Artisan/Tinker: `docker compose exec app php artisan tinker`
- Open a shell inside the PHP container: `docker compose exec app bash`
- Stop services: `docker compose down`
- Stop and remove data volumes (including MySQL data): `docker compose down --volumes`

### Database Credentials (defaults)

- Host: `localhost` (from the host) / `mysql` (from other containers)
- Port: `3306`
- Database: `fleet_forge`
- Username: `laravel`
- Password: `secret`
- Root password: `rootpass` (override via `MYSQL_ROOT_PASSWORD` in `.env`)

These values come from `.env`; adjust them there to suit your environment.

### Local Development (WSL2 / bare metal)

If you prefer running without Docker:

1. Install PHP 8.2+, Composer, Node 20, and MySQL inside WSL2.
2. Configure `.env` to point at the local MySQL instance (`DB_HOST=127.0.0.1`) and ensure writable permissions on `storage` and `bootstrap/cache`.
3. Run `composer install`, `npm install`, then `npm run dev -- --host localhost --port 5173` for Vite and `php artisan serve --host=0.0.0.0 --port=8000` for Laravel.
4. For production-style assets, run `npm run build` and delete `public/hot` so the app serves from `public/build`.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
