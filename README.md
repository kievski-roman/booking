# 🧾 Booking API (Laravel 12 · RESTful Backend)

A **role-based booking & CRM API** built with **Laravel 12**, **MySQL**, and **Sanctum** authentication.  
Designed as an **API-first** backend running in a **Docker / Laravel Sail** environment.

> ⚙️ **Stack:** PHP 8.x · Laravel 12 · MySQL · Sanctum · Docker (Compose/Sail) · PHPUnit  

---

## ✨ Features

- 🔐 Authentication & Authorization with Laravel Sanctum  
- 👥 Role System: **Admin**, **Master**, **Client**  
- 📅 Appointment, Schedule & Service management (CRUD endpoints)  
- 👤 Profile & User management  
- 🧾 Request validation (FormRequest) & Resource Controllers  
- 🌱 Database seeding with factories  
- 🧪 Unit & Feature testing with PHPUnit  
- 🐳 Dockerized stack (MySQL + PHP + Nginx)

---

## 📘 API Documentation

🔗 **[View full interactive documentation on Postman →](https://documenter.getpostman.com/view/45661278/2sB3QNqogu)**  

The documentation includes:
- All endpoints grouped by module (Auth, Profile, Masters, Services, etc.)
- Example requests and responses  
- Role-based permissions  
- Validation rules and error responses  
- Authentication & usage notes  

> _Auto-generated and maintained through a live Postman Collection._

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
