# Laravel API

A clean, Laravel API with versioning, authentication, and best practices.

## Features

- ✅ API Route Versioning (v1)
- ✅ JWT Authentication (Laravel Sanctum)
- ✅ Public & Private Routes
- ✅ Clean Architecture (Repository Pattern, Service Layer)
- ✅ Comprehensive Error Handling
- ✅ PostgreSQL Database
- ✅ API Resources & Collections
- ✅ Form Request Validation
- ✅ Custom Exception Handler
- ✅ Rate Limiting
- ✅ CORS Configuration

## Tech Stack

- Laravel 12.x (latest)
- PHP 8.3+
- PostgreSQL
- Redis
- Laravel Sanctum (API Authentication)

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── v1/          # API Version 1 Controllers
│   ├── Middleware/
│   ├── Requests/            # Form Requests
│   └── Resources/           # API Resources
├── Models/                  # Eloquent Models
├── Repositories/            # Repository Pattern
├── Services/                # Business Logic Layer
├── Exceptions/              # Custom Exceptions
└── Traits/                  # Reusable Traits
```

## Installation

### Prerequisites

- PHP 8.3 or higher
- Composer
- PostgreSQL
- Redis
- Git

### 1. Clone the Repository

```bash
git clone <your-repo-url>
cd laravel-api-boilerplate
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database (.env)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Seeders (Optional)

```bash
php artisan db:seed
```

## API Documentation

### Authentication Endpoints

#### Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### Teams Endpoints

```http
GET    /api/v1/teams                 # List all teams
POST   /api/v1/teams                 # Create team
GET    /api/v1/teams/{id}            # Show team
PUT    /api/v1/teams/{id}            # Update team
DELETE /api/v1/teams/{id}            # Delete team
POST   /api/v1/teams/{id}/comments   # Create comment
GET    /api/v1/teams/{id}/comments   # Show comment
```

### Tasks Endpoints

```http
GET    /api/v1/tasks                 # List all tasks
POST   /api/v1/tasks                 # Create task
GET    /api/v1/tasks/{id}            # Show task
PUT    /api/v1/tasks/{id}            # Update task
DELETE /api/v1/tasks/{id}            # Delete task
POST   /api/v1/tasks/{id}/comments   # Create comment
GET    /api/v1/tasks/{id}/comments   # Show comment
```

### Comments Endpoints

```http
GET    /api/v1/comments           # List all comments
PUT    /api/v1/comments/{id}      # Update comment
DELETE /api/v1/comments/{id}      # Delete comment
```

## Error Responses

All errors follow this format:

```json
{
    "success": false,
    "message": "Error message",
    "errors": {},
    "code": 400
}
```

## Success Responses

All successful responses follow this format:

```json
{
    "success": true,
    "message": "Success message",
    "data": {}
}
```

## Testing

```bash
php artisan test
```

## Rate Limiting

- API routes: 60 requests per minute
- Authentication routes: 5 requests per minute

## License

MIT License
