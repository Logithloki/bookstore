# Using Laravel Sanctum with Your Flutter App

This document provides instructions for connecting your Flutter app to the Laravel backend using Sanctum authentication.

## API Overview

Your Laravel backend has been set up with Sanctum authentication to secure API routes. The API is designed to work with your Flutter mobile app and includes endpoints for:

1. Authentication (register, login, logout)
2. Book browsing and searching
3. Cart management

## Testing the API

### Basic Test

First, test that the API is accessible without authentication:

```bash
curl -X GET "http://your-domain.com/api/test-sanctum"
```

You should see a response like:
```json
{
    "message": "API is working!",
    "time": "2025-05-27 12:34:56",
    "auth_required": false
}
```

### Authentication Flow

#### 1. Registration

Register a new user:

```bash
curl -X POST "http://your-domain.com/api/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "testuser@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phonenumber": "1234567890",
    "location": "Test City",
    "device_name": "test-device"
  }'
```

If successful, you'll receive a user object and a token:

```json
{
    "message": "User registered successfully",
    "user": {
        "_id": "...",
        "name": "Test User",
        "email": "testuser@example.com",
        ...
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz..."
}
```

#### 2. Login

Login with existing credentials:

```bash
curl -X POST "http://your-domain.com/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "testuser@example.com",
    "password": "password123",
    "device_name": "test-device"
  }'
```

You'll receive a user object and a token:

```json
{
    "user": {
        "_id": "...",
        "name": "Test User",
        "email": "testuser@example.com",
        ...
    },
    "token": "2|abcdefghijklmnopqrstuvwxyz..."
}
```

#### 3. Using Authentication

Test that you can access authenticated routes with your token:

```bash
curl -X GET "http://your-domain.com/api/test-auth" \
  -H "Authorization: Bearer 2|abcdefghijklmnopqrstuvwxyz..." \
  -H "Accept: application/json"
```

You should see a response like:

```json
{
    "message": "You are authenticated!",
    "user": {
        "_id": "...",
        "name": "Test User",
        "email": "testuser@example.com",
        ...
    },
    "time": "2025-05-27 12:34:56",
    "auth_required": true
}
```

#### 4. Logout

Logout to invalidate the token:

```bash
curl -X POST "http://your-domain.com/api/logout" \
  -H "Authorization: Bearer 2|abcdefghijklmnopqrstuvwxyz..." \
  -H "Accept: application/json"
```

You should receive:

```json
{
    "message": "Logged out successfully"
}
```

## Key API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
| ------ | -------- | ----------- | ------------ |
| POST | `/api/register` | Register new user | No |
| POST | `/api/login` | Login user | No |
| GET | `/api/user` | Get current user | Yes |
| POST | `/api/logout` | Logout user | Yes |
| POST | `/api/tokens/refresh` | Refresh token | Yes |

### Books

| Method | Endpoint | Description | Auth Required |
| ------ | -------- | ----------- | ------------ |
| GET | `/api/books` | List/search books | No |
| GET | `/api/books/{id}` | Get book details | No |
| GET | `/api/books/latest` | Get latest books | No |
| GET | `/api/books/exchange` | Get exchange books | No |
| GET | `/api/books/used` | Get used books | No |

### Cart

| Method | Endpoint | Description | Auth Required |
| ------ | -------- | ----------- | ------------ |
| GET | `/api/cart` | Get cart items | Yes |
| POST | `/api/cart/add` | Add item to cart | Yes |
| POST | `/api/cart/update` | Update cart item | Yes |
| POST | `/api/cart/remove` | Remove item from cart | Yes |
| POST | `/api/cart/clear` | Clear cart | Yes |

## Flutter Integration

See the `resources/markdown/flutter_integration.md` document for detailed Flutter integration instructions, including:

1. Setting up API services
2. Authentication provider
3. Sample screens
4. Error handling

## Security Considerations

1. Always use HTTPS for API requests in production
2. Store tokens securely in your Flutter app
3. Implement proper token refresh mechanisms
4. Handle expired tokens gracefully
5. Set appropriate CORS headers for web requests

## Troubleshooting

### Common Issues

1. **Authentication failures**: Make sure you're sending the token with the "Bearer " prefix
2. **CORS issues**: Check your CORS middleware if using a web frontend
3. **Token expiration**: Tokens expire after 14 days by default, implement token refreshing
4. **MongoDB connection errors**: Ensure your MongoDB connection is correctly configured

If you encounter any issues, check your Laravel logs for more information.
