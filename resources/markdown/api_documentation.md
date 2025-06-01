# API Authentication with Laravel Sanctum

This documentation describes how to use Laravel Sanctum API authentication with your Flutter app.

## API Endpoints

### Authentication

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| POST | `/api/register` | Register a new user and get token |
| POST | `/api/login` | Login and get token |
| POST | `/api/logout` | Logout (revoke token) |
| GET | `/api/user` | Get authenticated user info |
| POST | `/api/tokens/refresh` | Refresh token |

### Books

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/api/books` | List all books (with pagination and filters) |
| GET | `/api/books/{id}` | Get a specific book |
| GET | `/api/books/latest` | Get latest books |
| GET | `/api/books/exchange` | Get books for exchange |
| GET | `/api/books/used` | Get used books |

## Flutter Implementation

Here's how to implement API authentication in your Flutter app:

### 1. Add the required packages

```bash
flutter pub add http shared_preferences
```

### 2. Create an API service class

```dart
// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Replace with your actual API URL
  final String baseUrl = 'https://yourdomain.com/api';
  
  // Authentication headers
  Future<Map<String, String>> _getHeaders({bool authenticated = true}) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (authenticated) {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    
    return headers;
  }
  
  // Register a new user
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String phoneNumber,
    required String location
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: await _getHeaders(authenticated: false),
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'phonenumber': phoneNumber,
        'location': location,
        'device_name': 'flutter_app',
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 201) {
      // Save the token
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', data['token']);
      return data;
    } else {
      throw Exception(data['message'] ?? 'Registration failed');
    }
  }
  
  // Login user
  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
    bool logoutOtherDevices = false,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: await _getHeaders(authenticated: false),
      body: jsonEncode({
        'email': email,
        'password': password,
        'device_name': 'flutter_app',
        'logout_other_devices': logoutOtherDevices,
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      // Save the token
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', data['token']);
      return data;
    } else {
      throw Exception(data['message'] ?? 'Login failed');
    }
  }
  
  // Logout user
  Future<void> logout() async {
    try {
      await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: await _getHeaders(),
      );
    } finally {
      // Always clear the token
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove('auth_token');
    }
  }
  
  // Get user profile
  Future<Map<String, dynamic>> getUserProfile() async {
    final response = await http.get(
      Uri.parse('$baseUrl/user'),
      headers: await _getHeaders(),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return data;
    } else {
      throw Exception('Failed to load user profile');
    }
  }
  
  // Refresh token
  Future<String> refreshToken() async {
    final response = await http.post(
      Uri.parse('$baseUrl/tokens/refresh'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'device_name': 'flutter_app',
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      final token = data['token'];
      // Save the new token
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', token);
      return token;
    } else {
      throw Exception('Failed to refresh token');
    }
  }
  
  // Get books
  Future<List<dynamic>> getBooks({
    String? search,
    String? type,
    String? condition,
    String? category,
    int page = 1,
  }) async {
    final queryParams = {
      if (search != null) 'search': search,
      if (type != null) 'type': type,
      if (condition != null) 'condition': condition,
      if (category != null) 'category': category,
      'page': page.toString(),
    };
    
    final response = await http.get(
      Uri.parse('$baseUrl/books').replace(queryParameters: queryParams),
      headers: await _getHeaders(authenticated: false),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return data['data'] ?? [];
    } else {
      throw Exception('Failed to load books');
    }
  }
  
  // Get a specific book
  Future<Map<String, dynamic>> getBook(String id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/books/$id'),
      headers: await _getHeaders(authenticated: false),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return data;
    } else {
      throw Exception('Failed to load book details');
    }
  }
  
  // Get latest books
  Future<List<dynamic>> getLatestBooks() async {
    final response = await http.get(
      Uri.parse('$baseUrl/books/latest'),
      headers: await _getHeaders(authenticated: false),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return data;
    } else {
      throw Exception('Failed to load latest books');
    }
  }
}
```

### 3. Create Authentication Provider

```dart
// lib/providers/auth_provider.dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _apiService = ApiService();
  bool _isAuthenticated = false;
  Map<String, dynamic>? _user;
  bool _loading = false;
  String? _error;
  
  bool get isAuthenticated => _isAuthenticated;
  Map<String, dynamic>? get user => _user;
  bool get loading => _loading;
  String? get error => _error;
  
  AuthProvider() {
    _checkAuthentication();
  }
  
  Future<void> _checkAuthentication() async {
    try {
      _loading = true;
      notifyListeners();
      
      _user = await _apiService.getUserProfile();
      _isAuthenticated = true;
      _error = null;
    } catch (e) {
      _isAuthenticated = false;
      _user = null;
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<bool> login(String email, String password) async {
    try {
      _loading = true;
      _error = null;
      notifyListeners();
      
      final result = await _apiService.login(email: email, password: password);
      _user = result['user'];
      _isAuthenticated = true;
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String phoneNumber,
    required String location,
  }) async {
    try {
      _loading = true;
      _error = null;
      notifyListeners();
      
      final result = await _apiService.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
        phoneNumber: phoneNumber,
        location: location,
      );
      
      _user = result['user'];
      _isAuthenticated = true;
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> logout() async {
    try {
      _loading = true;
      notifyListeners();
      
      await _apiService.logout();
    } finally {
      _isAuthenticated = false;
      _user = null;
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> refreshProfile() async {
    try {
      _user = await _apiService.getUserProfile();
      notifyListeners();
    } catch (e) {
      // Handle error
    }
  }
}
```

### 4. Use the Auth Provider in Your App

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Book Hive',
      theme: ThemeData(
        primarySwatch: Colors.orange,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: Consumer<AuthProvider>(
        builder: (context, auth, child) {
          if (auth.loading) {
            return Scaffold(body: Center(child: CircularProgressIndicator()));
          }
          
          if (!auth.isAuthenticated) {
            return LoginScreen(); // Your login screen
          }
          
          return HomeScreen(); // Your home screen
        },
      ),
    );
  }
}
```

## Security Considerations

1. Always use HTTPS for all API requests
2. Store tokens securely (using secure storage when possible)
3. Implement token refresh mechanisms
4. Handle expired tokens gracefully
5. Implement proper error handling for API requests
6. Log out users automatically when authentication fails
