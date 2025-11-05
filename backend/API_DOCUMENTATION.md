# API Documentation

This document provides comprehensive documentation for the Curtains API, designed for use with Flutter mobile applications.

## Base URL

```
http://your-domain.com/api
```

## Authentication

The API uses Laravel Sanctum for token-based authentication. Include the bearer token in the Authorization header for protected routes.

### Headers

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## Endpoints

### Authentication

#### Register
`POST /auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2025-11-03T12:00:00.000000Z",
    "updated_at": "2025-11-03T12:00:00.000000Z"
  },
  "token": "1|xxxxx",
  "token_type": "Bearer"
}
```

#### Login
`POST /auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:** Same as Register

#### Get Current User
`GET /auth/user` (Protected)

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": null,
  "created_at": "2025-11-03T12:00:00.000000Z",
  "updated_at": "2025-11-03T12:00:00.000000Z"
}
```

#### Logout
`POST /auth/logout` (Protected)

**Response:**
```json
{
  "message": "Successfully logged out"
}
```

#### Logout from All Devices
`POST /auth/logout-all` (Protected)

**Response:**
```json
{
  "message": "Successfully logged out from all devices"
}
```

---

### Blinds

#### List Blinds
`GET /blinds` (Protected)

**Query Parameters:**
- `is_active` (boolean): Filter by active status
- `color` (string): Filter by color name
- `low_stock` (boolean): Filter for low stock items
- `out_of_stock` (boolean): Filter for out of stock items
- `has_details` (boolean): Filter by has details
- `per_page` (integer): Items per page (default: 20)
- `page` (integer): Page number

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "color": "Brown",
      "color_code": "#8B4513",
      "description": "Premium brown blinds",
      "image_path": "blinds/brown.jpg",
      "image_url": "http://your-domain.com/storage/blinds/brown.jpg",
      "primary_image": {
        "id": 1,
        "url": "http://your-domain.com/storage/blinds/brown.jpg"
      },
      "images": [
        {
          "id": 1,
          "url": "http://your-domain.com/storage/blinds/brown.jpg",
          "sort": 0
        }
      ],
      "stock": {
        "quantity": 50,
        "low_stock_threshold": 10,
        "status": "in_stock",
        "is_low_stock": false,
        "is_out_of_stock": false
      },
      "has_details": true,
      "is_active": true,
      "created_at": "2025-11-03T12:00:00.000000Z",
      "updated_at": "2025-11-03T12:00:00.000000Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

#### Get Blind
`GET /blinds/{id}` (Protected)

**Response:** Single Blind object (same structure as in list)

#### Create Blind
`POST /blinds` (Protected)

**Request Body:**
```json
{
  "color": "Black",
  "color_code": "#000000",
  "description": "Premium black blinds",
  "stock_qty": 25,
  "low_stock_threshold": 10,
  "has_details": true,
  "is_active": true
}
```

**Response:** Single Blind object

#### Update Blind
`PUT /blinds/{id}` (Protected)

**Request Body:** Same as Create (all fields optional)

**Response:** Single Blind object

#### Delete Blind
`DELETE /blinds/{id}` (Protected)

**Response:** 204 No Content

#### Stock Summary
`GET /blinds/stock/summary` (Protected)

**Response:**
```json
{
  "total": 100,
  "active": 95,
  "in_stock": 80,
  "low_stock": 10,
  "out_of_stock": 5
}
```

---

### Orders

#### List Orders
`GET /orders` (Protected)

**Query Parameters:**
- `status` (string): Filter by order status
- `customer_phone` (string): Filter by customer phone
- `reference` (string): Filter by order reference
- `created_from` (date): Filter from date
- `created_to` (date): Filter to date
- `per_page` (integer): Items per page (default: 20)
- `page` (integer): Page number

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "reference": "ORD-XXXXXXX",
      "customer": {
        "name": "John Doe",
        "first_name": "John",
        "last_name": "Doe",
        "phone": "+1234567890",
        "address": "123 Main St",
        "city": "New York"
      },
      "pick_up_in_store": false,
      "shipping_cost": "15.00",
      "notes": "Handle with care",
      "status": "draft",
      "blind": {
        "width_cm": "150.00",
        "height_cm": "200.00",
        "image_path": "orders/blind.jpg",
        "image_url": "http://your-domain.com/storage/orders/blind.jpg"
      },
      "calculator": {
        "multiplier": 10,
        "extra_charge": "5.00",
        "total_amount": "300.00"
      },
      "lines": [
        {
          "id": 1,
          "order_id": 1,
          "position": 0,
          "dimensions": {
            "width_mm": "1500.00",
            "height_mm": "2000.00"
          },
          "label": "Living Room",
          "image_path": null,
          "image_url": null,
          "created_at": "2025-11-03T12:00:00.000000Z",
          "updated_at": "2025-11-03T12:00:00.000000Z"
        }
      ],
      "blinds": [],
      "created_at": "2025-11-03T12:00:00.000000Z",
      "updated_at": "2025-11-03T12:00:00.000000Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

#### Get Order
`GET /orders/{id}` (Protected)

**Response:** Single Order object (same structure as in list)

#### Create Order
`POST /orders` (Protected)

**Request Body:**
```json
{
  "customer_name": "John Doe",
  "customer_first_name": "John",
  "customer_last_name": "Doe",
  "customer_phone": "+1234567890",
  "customer_address": "123 Main St",
  "customer_city": "New York",
  "pick_up_in_store": false,
  "shipping_cost": "15.00",
  "notes": "Handle with care",
  "blind_width_cm": "150.00",
  "blind_height_cm": "200.00",
  "calc_multiplier": 10,
  "extra_charge": "5.00",
  "total_amount": "300.00"
}
```

**Response:** Single Order object

#### Update Order
`PUT /orders/{id}` (Protected)

**Request Body:** Same as Create (all fields optional)

**Response:** Single Order object

#### Delete Order
`DELETE /orders/{id}` (Protected)

**Response:** 204 No Content

---

### Order Lines

#### List Order Lines
`GET /orders/{order_id}/lines` (Protected)

**Response:**
```json
[
  {
    "id": 1,
    "order_id": 1,
    "position": 0,
    "dimensions": {
      "width_mm": "1500.00",
      "height_mm": "2000.00"
    },
    "label": "Living Room",
    "image_path": null,
    "image_url": null,
    "created_at": "2025-11-03T12:00:00.000000Z",
    "updated_at": "2025-11-03T12:00:00.000000Z"
  }
]
```

#### Get Order Line
`GET /orders/{order_id}/lines/{line_id}` (Protected)

**Response:** Single OrderLine object

#### Create Order Line
`POST /orders/{order_id}/lines` (Protected)

**Request Body:**
```json
{
  "width_mm": "1500.00",
  "height_mm": "2000.00",
  "label": "Living Room",
  "position": 0
}
```

**Response:** Single OrderLine object

#### Update Order Line
`PUT /orders/{order_id}/lines/{line_id}` (Protected)

**Request Body:** Same as Create (all fields optional)

**Response:** Single OrderLine object

#### Delete Order Line
`DELETE /orders/{order_id}/lines/{line_id}` (Protected)

**Response:** 204 No Content

#### Upload Line Image
`POST /orders/{order_id}/lines/{line_id}/image` (Protected)

**Request:** Multipart form data
- `image` (file): Image file (jpg, jpeg, png, webp, max 8MB)

**Response:** Single OrderLine object with updated image

#### Reorder Lines
`PUT /orders/{order_id}/lines/reorder` (Protected)

**Request Body:**
```json
{
  "order": [
    { "id": 1, "position": 0 },
    { "id": 2, "position": 1 }
  ]
}
```

**Response:** Array of OrderLine objects

---

### Order Blinds

#### List Order Blinds
`GET /orders/{order_id}/blinds` (Protected)

**Response:** Array of OrderBlind objects

#### Get Order Blind
`GET /orders/{order_id}/blinds/{blind_id}` (Protected)

**Response:** Single OrderBlind object

#### Create Order Blind
`POST /orders/{order_id}/blinds` (Protected)

**Request Body:**
```json
{
  "qty": 2,
  "width_m": "1.5",
  "height_m": "2.0",
  "note": "Custom size",
  "stock_alert": false,
  "calc_multiplier": 10,
  "extra_charge": "5.00"
}
```

**Response:** Single OrderBlind object

#### Update Order Blind
`PUT /orders/{order_id}/blinds/{blind_id}` (Protected)

**Request Body:** Same as Create (all fields optional)

**Response:** Single OrderBlind object

#### Delete Order Blind
`DELETE /orders/{order_id}/blinds/{blind_id}` (Protected)

**Response:** 204 No Content

#### Upload Blind Image
`POST /orders/{order_id}/blinds/{blind_id}/image` (Protected)

**Request:** Multipart form data
- `image` (file): Image file (jpg, jpeg, png, webp, max 8MB)

**Response:** Single OrderBlind object with updated image

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Not Found (404)
```json
{
  "message": "No query results for model [App\\Models\\Order] 999"
}
```

### Server Error (500)
```json
{
  "message": "Server Error"
}
```

---

## Order Status Values

- `draft` - Order is in draft state
- `pending` - Order is pending approval
- `verified` - Order has been verified
- `ready_to_ship` - Order is ready to ship
- `completed` - Order has been completed
- `delivered` - Order has been delivered
- `cancelled` - Order has been cancelled

---

## Stock Status Values

- `in_stock` - Stock is above threshold
- `low_stock` - Stock is at or below threshold
- `out_of_stock` - Stock is zero

---

## Flutter Integration Example

### Setup HTTP Client

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = 'http://your-domain.com/api';
  String? _token;

  Map<String, String> get headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      _token = data['token'];
      return data;
    }

    throw Exception('Login failed');
  }

  Future<List<dynamic>> getOrders() async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders'),
      headers: headers,
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    }

    throw Exception('Failed to load orders');
  }
}
```

---

## Rate Limiting

The API is rate-limited to 60 requests per minute per user/IP.

---

## Notes

- All timestamps are in UTC ISO 8601 format
- Image URLs are absolute URLs that can be used directly in your Flutter app
- Pagination follows Laravel's standard pagination format
- All protected routes require valid authentication tokens
- Tokens don't expire by default but can be revoked via logout endpoints

