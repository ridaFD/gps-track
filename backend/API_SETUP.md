# API Setup Guide

This guide will help you set up and test the REST API for the Curtains application.

## Prerequisites

- PHP >= 8.1
- Composer
- Laravel 10.x
- MySQL/PostgreSQL Database

## Installation Steps

### 1. Install Dependencies

If Laravel Sanctum is not already installed:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Configure Environment

Make sure your `.env` file has the correct settings:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=curtains
DB_USERNAME=root
DB_PASSWORD=

# CORS Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:8000,localhost:3000
```

### 3. Run Migrations

Ensure all database migrations are run:

```bash
php artisan migrate
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 5. Generate Application Key (if needed)

```bash
php artisan key:generate
```

## Testing the API

### Using cURL

#### 1. Register a User

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### 2. Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Save the token from the response.

#### 3. Get User Information

```bash
curl -X GET http://localhost:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

#### 4. Create a Blind

```bash
curl -X POST http://localhost:8000/api/blinds \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "color": "Brown",
    "color_code": "#8B4513",
    "description": "Premium brown blinds",
    "stock_qty": 50,
    "low_stock_threshold": 10,
    "is_active": true
  }'
```

#### 5. Get All Blinds

```bash
curl -X GET http://localhost:8000/api/blinds \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

#### 6. Create an Order

```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "customer_name": "John Doe",
    "customer_phone": "+1234567890",
    "customer_first_name": "John",
    "customer_last_name": "Doe",
    "customer_address": "123 Main St",
    "customer_city": "New York",
    "notes": "Handle with care"
  }'
```

#### 7. Get All Orders

```bash
curl -X GET http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

#### 8. Add Line to Order

```bash
curl -X POST http://localhost:8000/api/orders/1/lines \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "width_mm": 1500,
    "height_mm": 2000,
    "label": "Living Room"
  }'
```

#### 9. Get Stock Summary

```bash
curl -X GET http://localhost:8000/api/blinds/stock/summary \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### Using Postman

1. Import the following collection or manually create requests
2. Set up environment variables:
   - `base_url`: http://localhost:8000/api
   - `token`: (leave empty, will be set after login)

3. Request flow:
   - Register or Login
   - Copy the token from response
   - Set token as Bearer token in Authorization header
   - Make authenticated requests

### Using PHPUnit Tests

Create tests in `tests/Feature/Api/`:

```php
<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user',
                     'token',
                     'token_type'
                 ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user',
                     'token',
                     'token_type'
                 ]);
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/auth/user');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'name',
                     'email',
                 ]);
    }
}
```

Run tests:

```bash
php artisan test
```

## Troubleshooting

### Issue: 401 Unauthorized

**Solution:** Make sure you're including the Bearer token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Issue: 419 CSRF Token Mismatch

**Solution:** Make sure you're using the `/api` prefix and including proper headers:
```
Content-Type: application/json
Accept: application/json
```

### Issue: 500 Server Error

**Solution:** Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

### Issue: CORS Errors

**Solution:** Configure CORS in `config/cors.php` and ensure your client origin is allowed.

### Issue: Token Not Working

**Solution:** 
1. Check if token has expired (default: doesn't expire)
2. Verify Sanctum is properly configured
3. Ensure User model has `HasApiTokens` trait

## API Rate Limiting

The API is rate limited to 60 requests per minute per user/IP address. If you exceed this limit, you'll receive a 429 Too Many Requests response.

## Security Notes

1. **Never commit** your `.env` file
2. Use HTTPS in production
3. Rotate API keys regularly
4. Implement proper password policies
5. Monitor API usage for suspicious activity
6. Keep Laravel and dependencies updated

## Production Deployment

### Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Sanitize Domains
SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

### Steps

1. Run `php artisan config:cache`
2. Run `php artisan route:cache`
3. Run `php artisan view:cache`
4. Set up proper web server (Nginx/Apache)
5. Configure SSL certificate
6. Set up database backups
7. Configure log rotation
8. Set up monitoring

## Next Steps

1. Review `API_DOCUMENTATION.md` for full endpoint documentation
2. Review `FLUTTER_API_CLIENT.md` for Flutter integration examples
3. Test all endpoints with your Flutter app
4. Implement additional features as needed

## Support

For issues or questions:
1. Check the Laravel documentation
2. Review API logs
3. Check this documentation
4. Contact your development team

