# Flutter Integration with Laravel Sanctum

This guide will help you integrate your Flutter app with the Laravel Sanctum API for the Book Hive application.

## Setting Up Your Flutter Project

### 1. Required Dependencies

Add these dependencies to your `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.0
  provider: ^6.0.5
  flutter_secure_storage: ^8.0.0
```

Run `flutter pub get` to install the dependencies.

### 2. API Service Class

Create a file `lib/services/api_service.dart` with the following content:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService {
  // Change this to your Laravel API URL
  final String baseUrl = 'https://YOUR_API_URL/api';
  final FlutterSecureStorage _storage = const FlutterSecureStorage();
  
  // Get authentication headers
  Future<Map<String, String>> _getHeaders({bool authenticated = true}) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (authenticated) {
      final token = await _storage.read(key: 'auth_token');
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    
    return headers;
  }
  
  // Handle API errors
  void _handleError(http.Response response) {
    if (response.statusCode == 401) {
      throw Exception('Unauthorized. Please login again.');
    } else if (response.statusCode == 422) {
      final errors = jsonDecode(response.body)['errors'];
      final messages = errors.values.map((e) => e[0]).join(', ');
      throw Exception(messages);
    } else if (response.statusCode >= 400) {
      final message = jsonDecode(response.body)['message'] ?? 'An error occurred';
      throw Exception(message);
    }
  }
  
  // Login
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: await _getHeaders(authenticated: false),
      body: jsonEncode({
        'email': email,
        'password': password,
        'device_name': 'flutter_app',
      }),
    );
    
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      await _storage.write(key: 'auth_token', value: data['token']);
      return data;
    } else {
      _handleError(response);
      throw Exception('Login failed');
    }
  }
  
  // Register
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String phoneNumber,
    required String location,
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
    
    if (response.statusCode == 201) {
      final data = jsonDecode(response.body);
      await _storage.write(key: 'auth_token', value: data['token']);
      return data;
    } else {
      _handleError(response);
      throw Exception('Registration failed');
    }
  }
  
  // Logout
  Future<void> logout() async {
    try {
      final token = await _storage.read(key: 'auth_token');
      if (token != null) {
        await http.post(
          Uri.parse('$baseUrl/logout'),
          headers: await _getHeaders(),
        );
      }
    } finally {
      await _storage.delete(key: 'auth_token');
    }
  }
  
  // Get current user
  Future<Map<String, dynamic>> getUser() async {
    final response = await http.get(
      Uri.parse('$baseUrl/user'),
      headers: await _getHeaders(),
    );
    
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      _handleError(response);
      throw Exception('Failed to load user');
    }
  }
  
  // Check if user is authenticated
  Future<bool> isAuthenticated() async {
    final token = await _storage.read(key: 'auth_token');
    if (token == null) return false;
    
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user'),
        headers: await _getHeaders(),
      );
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
  
  // Get latest books
  Future<List<dynamic>> getLatestBooks() async {
    final response = await http.get(
      Uri.parse('$baseUrl/books/latest'),
      headers: await _getHeaders(authenticated: false),
    );
    
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      _handleError(response);
      throw Exception('Failed to load latest books');
    }
  }
  
  // Get book details
  Future<Map<String, dynamic>> getBookDetails(String id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/books/$id'),
      headers: await _getHeaders(authenticated: false),
    );
    
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      _handleError(response);
      throw Exception('Failed to load book details');
    }
  }
  
  // Search books
  Future<Map<String, dynamic>> searchBooks({
    String? query,
    String? type,
    String? condition,
    String? category,
    int page = 1,
  }) async {
    final queryParams = {
      if (query != null && query.isNotEmpty) 'search': query,
      if (type != null && type.isNotEmpty) 'type': type,
      if (condition != null && condition.isNotEmpty) 'condition': condition,
      if (category != null && category.isNotEmpty) 'category': category,
      'page': page.toString(),
    };
    
    final response = await http.get(
      Uri.parse('$baseUrl/books').replace(queryParameters: queryParams),
      headers: await _getHeaders(authenticated: false),
    );
    
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      _handleError(response);
      throw Exception('Failed to search books');
    }
  }
  
  // Get cart
  Future<Map<String, dynamic>> getCart() async {
    final response = await http.get(
      Uri.parse('$baseUrl/cart'),
      headers: await _getHeaders(),
    );
    
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      _handleError(response);
      throw Exception('Failed to load cart');
    }
  }
  
  // Add item to cart
  Future<void> addToCart(String bookId, int quantity) async {
    final response = await http.post(
      Uri.parse('$baseUrl/cart/add'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'book_id': bookId,
        'quantity': quantity,
      }),
    );
    
    if (response.statusCode != 200) {
      _handleError(response);
      throw Exception('Failed to add item to cart');
    }
  }
  
  // Update cart item quantity
  Future<void> updateCartQuantity(String cartId, int quantity) async {
    final response = await http.post(
      Uri.parse('$baseUrl/cart/update'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'cart_id': cartId,
        'quantity': quantity,
      }),
    );
    
    if (response.statusCode != 200) {
      _handleError(response);
      throw Exception('Failed to update cart');
    }
  }
  
  // Remove item from cart
  Future<void> removeFromCart(String cartId) async {
    final response = await http.post(
      Uri.parse('$baseUrl/cart/remove'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'cart_id': cartId,
      }),
    );
    
    if (response.statusCode != 200) {
      _handleError(response);
      throw Exception('Failed to remove item from cart');
    }
  }
}
```

### 3. Create Authentication Provider

Add a provider to manage authentication state:

```dart
// lib/providers/auth_provider.dart
import 'package:flutter/foundation.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _api = ApiService();
  bool _isAuthenticated = false;
  bool _loading = true;
  Map<String, dynamic>? _user;
  String? _error;
  
  bool get isAuthenticated => _isAuthenticated;
  bool get isLoading => _loading;
  Map<String, dynamic>? get user => _user;
  String? get error => _error;
  
  AuthProvider() {
    _checkAuthentication();
  }
  
  Future<void> _checkAuthentication() async {
    _loading = true;
    notifyListeners();
    
    try {
      _isAuthenticated = await _api.isAuthenticated();
      if (_isAuthenticated) {
        _user = await _api.getUser();
      }
    } catch (e) {
      _isAuthenticated = false;
      _user = null;
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<bool> login(String email, String password) async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      final data = await _api.login(email, password);
      _user = data['user'];
      _isAuthenticated = true;
      notifyListeners();
      return true;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
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
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      final data = await _api.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
        phoneNumber: phoneNumber,
        location: location,
      );
      _user = data['user'];
      _isAuthenticated = true;
      notifyListeners();
      return true;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return false;
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> logout() async {
    _loading = true;
    notifyListeners();
    
    try {
      await _api.logout();
    } finally {
      _isAuthenticated = false;
      _user = null;
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> refreshUserProfile() async {
    try {
      _user = await _api.getUser();
      notifyListeners();
    } catch (e) {
      // Handle error
    }
  }
}
```

### 4. Create Books Provider

```dart
// lib/providers/books_provider.dart
import 'package:flutter/foundation.dart';
import '../services/api_service.dart';

class BooksProvider with ChangeNotifier {
  final ApiService _api = ApiService();
  List<dynamic> _latestBooks = [];
  List<dynamic> _exchangeBooks = [];
  List<dynamic> _usedBooks = [];
  Map<String, dynamic>? _currentBook;
  bool _loading = false;
  String? _error;
  
  List<dynamic> get latestBooks => _latestBooks;
  List<dynamic> get exchangeBooks => _exchangeBooks;
  List<dynamic> get usedBooks => _usedBooks;
  Map<String, dynamic>? get currentBook => _currentBook;
  bool get isLoading => _loading;
  String? get error => _error;
  
  Future<void> loadLatestBooks() async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      _latestBooks = await _api.getLatestBooks();
    } catch (e) {
      _error = e.toString();
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> loadBookDetails(String id) async {
    _loading = true;
    _error = null;
    _currentBook = null;
    notifyListeners();
    
    try {
      _currentBook = await _api.getBookDetails(id);
    } catch (e) {
      _error = e.toString();
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<Map<String, dynamic>> searchBooks({
    String? query,
    String? type,
    String? condition,
    String? category,
    int page = 1,
  }) async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      final results = await _api.searchBooks(
        query: query,
        type: type,
        condition: condition,
        category: category,
        page: page,
      );
      _loading = false;
      notifyListeners();
      return results;
    } catch (e) {
      _error = e.toString();
      _loading = false;
      notifyListeners();
      return {'data': [], 'total': 0};
    }
  }
}
```

### 5. Create Cart Provider

```dart
// lib/providers/cart_provider.dart
import 'package:flutter/foundation.dart';
import '../services/api_service.dart';

class CartProvider with ChangeNotifier {
  final ApiService _api = ApiService();
  List<dynamic> _items = [];
  double _total = 0.0;
  int _count = 0;
  bool _loading = false;
  String? _error;
  
  List<dynamic> get items => _items;
  double get total => _total;
  int get count => _count;
  bool get isLoading => _loading;
  String? get error => _error;
  
  Future<void> loadCart() async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      final cart = await _api.getCart();
      _items = cart['items'];
      _total = cart['total'].toDouble();
      _count = cart['count'];
    } catch (e) {
      _error = e.toString();
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> addToCart(String bookId, int quantity) async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      await _api.addToCart(bookId, quantity);
      await loadCart();
    } catch (e) {
      _error = e.toString();
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> updateQuantity(String cartId, int quantity) async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      await _api.updateCartQuantity(cartId, quantity);
      await loadCart();
    } catch (e) {
      _error = e.toString();
      _loading = false;
      notifyListeners();
    }
  }
  
  Future<void> removeItem(String cartId) async {
    _loading = true;
    _error = null;
    notifyListeners();
    
    try {
      await _api.removeFromCart(cartId);
      await loadCart();
    } catch (e) {
      _error = e.toString();
      _loading = false;
      notifyListeners();
    }
  }
}
```

### 6. Set Up Your App

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'providers/books_provider.dart';
import 'providers/cart_provider.dart';
import 'screens/splash_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => BooksProvider()),
        ChangeNotifierProvider(create: (_) => CartProvider()),
      ],
      child: MaterialApp(
        title: 'Book Hive',
        theme: ThemeData(
          primarySwatch: Colors.orange,
          visualDensity: VisualDensity.adaptivePlatformDensity,
        ),
        home: SplashScreen(),
      ),
    );
  }
}
```

## Testing Your Setup

1. Run the PHP test script on your server to verify your Sanctum configuration:

```bash
php test_api_for_flutter.php
```

2. Try the generated curl commands to test your API endpoints

3. Update your Flutter app with the correct API URL and test the connection

## Common Issues

### CORS Issues

If you're experiencing CORS issues, check that:

1. Your `Cors` middleware is properly registered
2. You've added all necessary domains to the Sanctum `stateful` configuration

### Authentication Issues

If authentication isn't working:

1. Check that your tokens are being properly stored and sent with requests
2. Ensure your Flutter app is using the correct API endpoint URL
3. Verify that the token is valid and hasn't expired

### MongoDB Issues

1. Make sure your PersonalAccessToken model properly extends the MongoDB model
2. Check the MongoDB connection configuration

## Security Best Practices

1. Always use HTTPS for API communication
2. Use secure storage for tokens on the Flutter app
3. Implement token refresh mechanisms
4. Set appropriate token expiration time
5. Validate all inputs on the server side
6. Use token abilities to restrict access
