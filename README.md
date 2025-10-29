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
3. Run database migrations (and optionally seeders) against the containerised MySQL instance:
   ```bash
   docker compose exec app php artisan migrate --force
   ```
4. Visit the application at [http://localhost:8080](http://localhost:8080).

Because the image prebuilds Vite assets, no frontend watcher is required. Any application code changes require rebuilding the image:

```bash
docker compose up -d --build
```

### Seed Data & Demo Workflows

Seed the database for a complete demo setup:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

Seeders create:
- `test@example.com` with the password `password` and `cash` balance of `600000.00`
- 100 truck records spanning the main brands (Volvo, Scania, MAN, Kenworth, etc.) with realistic VINs and prices capped at DKK 200,000 and marked as available for purchase

### Authentication & UI Flows

- **Login:** Visit `http://localhost:8080/login` and sign in with `test@example.com` / `password`. The page uses a Tailwind-based hero layout.
- **Dashboard:** `/vehicles` displays the logged-in user’s cash balance, fleet value, and a table of owned vehicles (role, assignment time, VIN, price). Flash messages confirm purchase/top-up actions.
- **Marketplace:** `/vehicles/marketplace` shows cards for every available truck with imagery, pricing, and a “Purchase” action that calls the API and updates the balance.

### Console Utility

Add funds to any user directly via Artisan for testing purposes:

```bash
docker compose exec app php artisan addCashForUser 1 15000
```

The command validates inputs, runs inside a transaction, and reports the new balance.

### API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| `GET`  | `/api/vehicles` | Paginated list of vehicles still available for purchase |
| `GET`  | `/api/users/{user}` | Fetch a single user with balance and owned vehicles |
| `POST` | `/api/users/{user}/vehicles` | Purchase/assign a vehicle; deducts cash, marks vehicle sold, and syncs the pivot role |

The Bruno collection (`collection/FleetForge/`) ships with:
- `List Vehicles.bru` – `GET /api/vehicles` (pagination params included)
- `Get User.bru` – `GET /api/users/{id}`
- `Purchase Vehicle.bru` – `POST /api/users/{id}/vehicles`

Point the collection at
- `http://localhost:8080` when using the Docker stack (nginx behind PHP-FPM)
- `http://127.0.0.1:8000` if you run `php artisan serve` locally

Update the `vehicle_id` payload as needed.

> **Note:** Authentication/authorization has intentionally been left out of the scope for this project. The endpoints are left open to keep the focus on architecture, transactions, and data-flow patterns and to save time. Auth should definitely be added and some sort of Policy checking for production readiness.

### Testing

This is how you run tests when using the production-oriented Docker setup (which omits dev dependencies):

```bash
composer install     # ensure dev dependencies like PHPUnit are available
php artisan test
```

Run these commands on your host machine (or inside a container that was built with `composer install` instead of `composer install --no-dev`). Coverage includes vehicle purchase success/failure paths, vehicle listings, and the cash top-up command. All tests use the in-memory database via `RefreshDatabase`.
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
