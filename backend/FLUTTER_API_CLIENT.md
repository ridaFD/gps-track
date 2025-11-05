# Flutter API Client Implementation Guide

This guide provides a complete Flutter implementation for integrating with the Curtains API.

## 1. Dependencies

Add these dependencies to your `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.0
  provider: ^6.1.0
```

## 2. API Service Class

Create `lib/services/api_service.dart`:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  String? _token;

  // Singleton pattern
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  // Initialize token from storage
  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }

  // Save token to storage
  Future<void> _saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Clear token
  Future<void> _clearToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // Get headers
  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  // Handle response
  dynamic _handleResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (response.body.isEmpty) return null;
      return jsonDecode(response.body);
    } else if (response.statusCode == 401) {
      throw Exception('Unauthorized');
    } else if (response.statusCode == 404) {
      throw Exception('Resource not found');
    } else if (response.statusCode == 422) {
      final errors = jsonDecode(response.body);
      throw Exception(errors['message'] ?? 'Validation error');
    } else {
      throw Exception('Server error: ${response.statusCode}');
    }
  }

  // Authentication
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

    final data = _handleResponse(response);
    await _saveToken(data['token']);
    return data;
  }

  Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/register'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      }),
    );

    final data = _handleResponse(response);
    await _saveToken(data['token']);
    return data;
  }

  Future<Map<String, dynamic>> getUser() async {
    final response = await http.get(
      Uri.parse('$baseUrl/auth/user'),
      headers: _headers,
    );

    return _handleResponse(response);
  }

  Future<void> logout() async {
    await http.post(
      Uri.parse('$baseUrl/auth/logout'),
      headers: _headers,
    );
    await _clearToken();
  }

  Future<void> logoutAll() async {
    await http.post(
      Uri.parse('$baseUrl/auth/logout-all'),
      headers: _headers,
    );
    await _clearToken();
  }

  // Blinds
  Future<Map<String, dynamic>> getBlinds({
    bool? isActive,
    String? color,
    bool? lowStock,
    bool? outOfStock,
    bool? hasDetails,
    int perPage = 20,
    int page = 1,
  }) async {
    final queryParams = <String, dynamic>{};
    if (isActive != null) queryParams['is_active'] = isActive;
    if (color != null) queryParams['color'] = color;
    if (lowStock != null) queryParams['low_stock'] = lowStock;
    if (outOfStock != null) queryParams['out_of_stock'] = outOfStock;
    if (hasDetails != null) queryParams['has_details'] = hasDetails;
    queryParams['per_page'] = perPage;
    queryParams['page'] = page;

    final uri = Uri.parse('$baseUrl/blinds').replace(queryParameters: queryParams);
    final response = await http.get(uri, headers: _headers);
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getBlind(int id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/blinds/$id'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> createBlind(Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl/blinds'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> updateBlind(int id, Map<String, dynamic> data) async {
    final response = await http.put(
      Uri.parse('$baseUrl/blinds/$id'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<void> deleteBlind(int id) async {
    await http.delete(
      Uri.parse('$baseUrl/blinds/$id'),
      headers: _headers,
    );
  }

  Future<Map<String, dynamic>> getBlindStockSummary() async {
    final response = await http.get(
      Uri.parse('$baseUrl/blinds/stock/summary'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  // Orders
  Future<Map<String, dynamic>> getOrders({
    String? status,
    String? customerPhone,
    String? reference,
    String? createdFrom,
    String? createdTo,
    int perPage = 20,
    int page = 1,
  }) async {
    final queryParams = <String, dynamic>{};
    if (status != null) queryParams['status'] = status;
    if (customerPhone != null) queryParams['customer_phone'] = customerPhone;
    if (reference != null) queryParams['reference'] = reference;
    if (createdFrom != null) queryParams['created_from'] = createdFrom;
    if (createdTo != null) queryParams['created_to'] = createdTo;
    queryParams['per_page'] = perPage;
    queryParams['page'] = page;

    final uri = Uri.parse('$baseUrl/orders').replace(queryParameters: queryParams);
    final response = await http.get(uri, headers: _headers);
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getOrder(int id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders/$id'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> createOrder(Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl/orders'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> updateOrder(int id, Map<String, dynamic> data) async {
    final response = await http.put(
      Uri.parse('$baseUrl/orders/$id'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<void> deleteOrder(int id) async {
    await http.delete(
      Uri.parse('$baseUrl/orders/$id'),
      headers: _headers,
    );
  }

  // Order Lines
  Future<List<dynamic>> getOrderLines(int orderId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders/$orderId/lines'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getOrderLine(int orderId, int lineId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders/$orderId/lines/$lineId'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> createOrderLine(int orderId, Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl/orders/$orderId/lines'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> updateOrderLine(
    int orderId,
    int lineId,
    Map<String, dynamic> data,
  ) async {
    final response = await http.put(
      Uri.parse('$baseUrl/orders/$orderId/lines/$lineId'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<void> deleteOrderLine(int orderId, int lineId) async {
    await http.delete(
      Uri.parse('$baseUrl/orders/$orderId/lines/$lineId'),
      headers: _headers,
    );
  }

  Future<Map<String, dynamic>> uploadOrderLineImage(
    int orderId,
    int lineId,
    List<int> imageBytes,
    String filename,
  ) async {
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/orders/$orderId/lines/$lineId/image'),
    );
    request.headers.addAll(_headers);
    request.files.add(http.MultipartFile.fromBytes(
      'image',
      imageBytes,
      filename: filename,
    ));

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);
    return _handleResponse(response);
  }

  Future<List<dynamic>> reorderOrderLines(
    int orderId,
    List<Map<String, dynamic>> newOrder,
  ) async {
    final response = await http.put(
      Uri.parse('$baseUrl/orders/$orderId/lines/reorder'),
      headers: _headers,
      body: jsonEncode({'order': newOrder}),
    );
    return _handleResponse(response);
  }

  // Order Blinds
  Future<List<dynamic>> getOrderBlinds(int orderId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders/$orderId/blinds'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getOrderBlind(int orderId, int blindId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/orders/$orderId/blinds/$blindId'),
      headers: _headers,
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> createOrderBlind(
    int orderId,
    Map<String, dynamic> data,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/orders/$orderId/blinds'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> updateOrderBlind(
    int orderId,
    int blindId,
    Map<String, dynamic> data,
  ) async {
    final response = await http.put(
      Uri.parse('$baseUrl/orders/$orderId/blinds/$blindId'),
      headers: _headers,
      body: jsonEncode(data),
    );
    return _handleResponse(response);
  }

  Future<void> deleteOrderBlind(int orderId, int blindId) async {
    await http.delete(
      Uri.parse('$baseUrl/orders/$orderId/blinds/$blindId'),
      headers: _headers,
    );
  }

  Future<Map<String, dynamic>> uploadOrderBlindImage(
    int orderId,
    int blindId,
    List<int> imageBytes,
    String filename,
  ) async {
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/orders/$orderId/blinds/$blindId/image'),
    );
    request.headers.addAll(_headers);
    request.files.add(http.MultipartFile.fromBytes(
      'image',
      imageBytes,
      filename: filename,
    ));

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);
    return _handleResponse(response);
  }
}
```

## 3. Usage Example

### Login and Get User

```dart
import 'package:flutter/material.dart';
import 'services/api_service.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _api = ApiService();
  Map<String, dynamic>? _user;
  bool _loading = false;

  @override
  void initState() {
    super.initState();
    _initialize();
  }

  Future<void> _initialize() async {
    await _api.init();
    _loadUser();
  }

  Future<void> _loadUser() async {
    setState(() => _loading = true);
    try {
      final user = await _api.getUser();
      setState(() => _user = user);
    } catch (e) {
      print('Error loading user: $e');
    } finally {
      setState(() => _loading = false);
    }
  }

  Future<void> _handleLogin(String email, String password) async {
    setState(() => _loading = true);
    try {
      await _api.login(email, password);
      _loadUser();
    } catch (e) {
      print('Login error: $e');
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return Scaffold(
      appBar: AppBar(title: Text('Curtains App')),
      body: Center(
        child: _user == null
            ? Text('Not logged in')
            : Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome, ${_user!['name']}'),
                  Text('Email: ${_user!['email']}'),
                ],
              ),
      ),
    );
  }
}
```

### Fetch and Display Orders

```dart
import 'package:flutter/material.dart';
import 'services/api_service.dart';

class OrdersScreen extends StatefulWidget {
  @override
  _OrdersScreenState createState() => _OrdersScreenState();
}

class _OrdersScreenState extends State<OrdersScreen> {
  final ApiService _api = ApiService();
  List<dynamic> _orders = [];
  bool _loading = false;

  @override
  void initState() {
    super.initState();
    _loadOrders();
  }

  Future<void> _loadOrders() async {
    setState(() => _loading = true);
    try {
      final response = await _api.getOrders();
      setState(() => _orders = response['data']);
    } catch (e) {
      print('Error loading orders: $e');
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Orders')),
      body: _loading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: _orders.length,
              itemBuilder: (context, index) {
                final order = _orders[index];
                return ListTile(
                  title: Text(order['reference']),
                  subtitle: Text(order['customer']['name']),
                  trailing: Text(order['status']),
                  onTap: () {
                    // Navigate to order details
                  },
                );
              },
            ),
    );
  }
}
```

## 4. Using Provider for State Management

### Auth Provider

Create `lib/providers/auth_provider.dart`:

```dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _api = ApiService();
  Map<String, dynamic>? _user;
  bool _isLoading = false;

  Map<String, dynamic>? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;

  Future<void> initialize() async {
    await _api.init();
    await loadUser();
  }

  Future<void> loadUser() async {
    _setLoading(true);
    try {
      _user = await _api.getUser();
    } catch (e) {
      _user = null;
    }
    _setLoading(false);
  }

  Future<bool> login(String email, String password) async {
    _setLoading(true);
    try {
      await _api.login(email, password);
      await loadUser();
      _setLoading(false);
      return true;
    } catch (e) {
      _setLoading(false);
      return false;
    }
  }

  Future<void> logout() async {
    await _api.logout();
    _user = null;
    notifyListeners();
  }

  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }
}
```

### Main App Setup

```dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: MaterialApp(
        title: 'Curtains App',
        home: HomeScreen(),
      ),
    );
  }
}

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<AuthProvider>(
      builder: (context, auth, child) {
        if (auth.isLoading) {
          return Scaffold(body: Center(child: CircularProgressIndicator()));
        }

        if (!auth.isAuthenticated) {
          return LoginScreen();
        }

        return OrdersScreen();
      },
    );
  }
}
```

## 5. Testing

Create test files and add http mocking for testing your API integration.

## Notes

- Remember to change the `baseUrl` to your actual API URL
- Handle network errors gracefully
- Show loading indicators during API calls
- Display user-friendly error messages
- Consider adding retry logic for failed requests
- Implement proper error logging for debugging

