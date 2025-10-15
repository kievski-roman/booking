# Booking API (Laravel 12, RESTful Backend)

A role-based booking and CRM backend built with **Laravel 12**, **MySQL**, and **Sanctum** authentication.  
Designed as an **API-first** application running in a **Docker / Laravel Sail** environment.

> **Stack:** PHP 8.x · Laravel 12 · MySQL · Sanctum · Docker (Compose/Sail) · PHPUnit

---

## ✨ Features

- 🔐 Authentication & Authorization using Laravel Sanctum
- 👥 User Roles: **Admin**, **Master**, **Client**
- 📅 Appointment, Schedule & Service management (CRUD endpoints)
- 👤 Profile and User management
- 🧾 Request validation (FormRequest) & resource controllers
- 🌱 Database seeding with factories
- 🧪 Unit & feature tests (PHPUnit)
- 🐳 Docker-based development stack (MySQL + PHP + Nginx)

---

## 📡 API Endpoints (v1)

### 🩺 Health Check
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/health` | Check API availability |

### 🔑 Authentication
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `POST` | `/api/v1/register` | Register a new user |
| `POST` | `/api/v1/login` | Obtain access token |
| `POST` | `/api/v1/logout` | Revoke token and log out (requires auth) |

### 👤 Profile
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/profile` | Get current user's profile |
| `POST` | `/api/v1/profile` | Update current user's profile |

### 👮 Admin
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/admin/profiles` | List all profiles |
| `DELETE` | `/api/v1/admin/users/{user}` | Delete a user |

### 🧑 Masters
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/masters` | List all masters |
| `GET` | `/api/v1/masters/{master}` | Show a specific master |

### 🛎️ Services
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/services` | List services |
| `GET` | `/api/v1/services/{service}` | Show a service |
| `POST` | `/api/v1/services` | Create a service |
| `PUT` | `/api/v1/services/{service}` | Update a service |
| `DELETE` | `/api/v1/services/{service}` | Delete a service |

### 🗓️ Schedules
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/schedules` | List schedules |
| `GET` | `/api/v1/schedules/{schedule}` | Show schedule details |
| `POST` | `/api/v1/schedules` | Create schedule |
| `PUT` | `/api/v1/schedules/{schedule}` | Update schedule |
| `DELETE` | `/api/v1/schedules/{schedule}` | Delete schedule |

### 📅 Appointments
| Method | Endpoint | Description |
|:-------|:----------|:-------------|
| `GET` | `/api/v1/appointments` | List all appointments |
| `GET` | `/api/v1/appointments/{appointment}` | Show appointment details |
| `POST` | `/api/v1/appointments` | Create new appointment |
| `PATCH` | `/api/v1/appointments/{appointment}` | Update appointment |

---

## 🚀 Quick Start

### 🐳 Option A – Docker (Compose)
```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

### ⚙️ Option B – Laravel Sail
```bash
cp .env.example .env
composer install
php artisan key:generate
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

---

## ⚙️ Example `.env` Configuration

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=booking
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=database
SESSION_DOMAIN=localhost
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false

SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173

CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

---

## 🔐 Authentication

After a successful login, use the returned token in your requests:

```
Authorization: Bearer <your_token>
Accept: application/json
```

---

## 🧪 Testing

```bash
docker compose exec app php artisan test
# or
./vendor/bin/sail artisan test
```

---


