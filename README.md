# BookEase — Service Booking Platform

A modern, multi-role appointment booking system built with Laravel 11. Clients discover and book services across any category, providers manage their schedules, and administrators control the entire platform through a clean, role-driven interface.

---

## Table of Contents

- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Advanced Laravel Features](#advanced-laravel-features)
- [Demo Credentials](#demo-credentials)
- [Installation & Setup](#installation--setup)
- [API Documentation](#api-documentation)
- [Project Structure](#project-structure)
- [Testing](#testing)
- [Team](#team)

---

## Key Features

| Feature | Description |
|---|---|
| **Authentication** | Registration, login, and password reset via Laravel Breeze |
| **Role-Based Access** | Three roles — Admin, Provider, Client — enforced via Gates & Policies |
| **Service Catalogue** | Browse and filter services by category or keyword |
| **Smart Booking** | Overlap-safe scheduling enforced at the database level |
| **Appointment Lifecycle** | Book → Reschedule → Cancel → Complete |
| **Provider Dashboard** | Providers view their upcoming schedule and assigned services |
| **Admin Panel** | Full CRUD for users, providers, services, and categories; all-appointments view with search and status filters |
| **Email Confirmations** | Queued confirmation emails fired via Event/Listener on every booking |
| **REST API** | Sanctum-authenticated JSON endpoints documented with Swagger/OpenAPI |
| **Map Integration** | Leaflet.js displays service locations on an interactive map |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 (PHP 8.3+) |
| Database | PostgreSQL |
| Authentication | Laravel Breeze + Laravel Sanctum (API) |
| Frontend | Blade · Tailwind CSS · Alpine.js |
| Maps | Leaflet.js |
| Queue Driver | Database (async email delivery) |
| API Docs | L5-Swagger (OpenAPI 3.0) |
| Testing | PestPHP + PHPUnit |
| Dev Environment | Docker Compose |

---

## Advanced Laravel Features

| Feature | Implementation |
|---|---|
| **Repository / Service Pattern** | `AppointmentRepository`, `ServiceRepository`, `CategoryRepository` with interfaces bound via the service container in `AppServiceProvider` |
| **Dependency Injection** | `AppointmentRepositoryInterface` resolved automatically throughout the application |
| **Events & Listeners** | `AppointmentBooked` event triggers `SendAppointmentConfirmation` listener |
| **Queues & Jobs** | `SendAppointmentReminderJob` queued for reminder delivery; `SendAppointmentConfirmation` listener implements `ShouldQueue` for async confirmation emails |
| **Pivot Tables (Many-to-Many)** | `service_user` pivot connects providers to multiple services — `User::services()` ↔ `Service::users()` with `withTimestamps()` |
| **Policies** | `AppointmentPolicy` and `ServicePolicy` registered in `AppServiceProvider` |
| **Gates** | `admin`, `provider`, and `client` gates enforce route-level access control |
| **API Resources** | `ServiceResource`, `AppointmentResource`, `AppointmentCalendarSlotResource` |
| **Form Requests** | 8 dedicated request classes with fine-grained validation rules |
| **Enums** | `UserRole` and `AppointmentStatus` as PHP 8.1 backed enums |
| **Custom Exceptions** | `SlotUnavailableException`, `CategoryHasServicesException` |
| **Mailable** | `AppointmentConfirmationMail` with a Blade email template |

---

## Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@bookease.kz | password |

> Log in as Admin to create providers, categories, and services. Providers and clients can self-register or be created through the admin panel.

---

## Installation & Setup

### Requirements

- PHP 8.3+
- Composer
- PostgreSQL
- Node.js 18+

### Steps

```bash
# 1. Clone the repository
git clone <repo-url> bookease
cd bookease

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies and compile assets
npm install && npm run build

# 4. Set up environment
cp .env.example .env
php artisan key:generate
```

Update `.env` with your PostgreSQL credentials:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bookease
DB_USERNAME=postgres
DB_PASSWORD=secret

QUEUE_CONNECTION=database
MAIL_MAILER=log
```

```bash
# 5. Run migrations and seed the admin account
php artisan migrate --seed

# 6. Generate Swagger API documentation
php artisan l5-swagger:generate

# 7. Start the queue worker (for email confirmations)
php artisan queue:work

# 8. Start the development server
php artisan serve
```

The application will be available at **http://localhost:8000**.

### Docker (Alternative)

```bash
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan l5-swagger:generate
```

---

## API Documentation

Interactive Swagger UI:

```
http://localhost:8000/api/documentation
```

### Endpoint Summary

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/services` | Public | List all services (`?search=`, `?category_id=`) |
| `GET` | `/api/user` | Sanctum | Authenticated user details |
| `GET` | `/api/appointments` | Client | List the current client's appointments |
| `POST` | `/api/appointments` | Client | Book a new appointment |
| `DELETE` | `/api/appointments/{id}` | Client | Cancel an appointment |

### Example Request — Book an Appointment

```bash
curl -X POST http://localhost:8000/api/appointments \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "service_id": 1,
    "scheduled_at": "2026-06-15 14:00:00",
    "notes": "First visit"
  }'
```

**Response `201 Created`:**

```json
{
  "data": {
    "id": 1,
    "service": {
      "id": 1,
      "name": "Deep Tissue Massage",
      "price": "85.00"
    },
    "scheduled_at": "2026-06-15T14:00:00.000000Z",
    "ends_at": "2026-06-15T15:00:00.000000Z",
    "status": "booked"
  }
}
```

---

## Project Structure

```
app/
├── Contracts/Repositories/     # Repository interfaces
├── Enums/                      # UserRole, AppointmentStatus
├── Events/                     # AppointmentBooked
├── Exceptions/                 # SlotUnavailableException, CategoryHasServicesException
├── Http/
│   ├── Controllers/Api/        # API controllers
│   ├── Controllers/            # Web controllers
│   ├── Requests/               # 8 Form Request classes
│   └── Resources/              # API Resource transformers
├── Listeners/                  # SendAppointmentConfirmation
├── Mail/                       # AppointmentConfirmationMail
├── Models/                     # User, Category, Service, Appointment
├── Policies/                   # AppointmentPolicy, ServicePolicy
├── Providers/                  # AppServiceProvider, EventServiceProvider
├── Repositories/               # Eloquent implementations
└── Services/                   # AppointmentService (business logic)
```

### Core Database Schema

```
users           id, name, email, phone, bio, role, category_id, password
categories      id, name
services        id, name, description, duration_minutes, price,
                category_id, provider_id, creator_user_id,
                address, latitude, longitude
appointments    id, user_id, service_id, scheduled_at, ends_at, status, notes
service_user    service_id, user_id, created_at, updated_at   (pivot — many-to-many)
```

### Entity Relationships

| Relationship | Type | Description |
|---|---|---|
| `Category` → `Service` | one-to-many | A category groups many services |
| `Category` → `User` | one-to-many | A category groups many providers |
| `User (client)` → `Appointment` | one-to-many | A client books many appointments |
| `User (provider)` → `Service` | one-to-many | The lead provider of a service (`provider_id`) |
| `User` ↔ `Service` | **many-to-many** | Multiple staff assigned to multiple services via `service_user` pivot |
| `Service` → `Appointment` | one-to-many | A service has many bookings |

---

## Testing

```bash
php artisan test
```

| File | Coverage |
|---|---|
| `tests/Feature/AvailabilityEngineTest.php` | Slot overlap detection, back-to-back bookings, rescheduling, API conflict response |
| `tests/Feature/ProviderAuthorizationTest.php` | Role-gated route access, provider data isolation, policy enforcement |
| `tests/Unit/AppointmentServiceTest.php` | End-time calculation, repository delegation, mocked overlap detection |





