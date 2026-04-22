import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  UserModel? _user;
  bool _isLoading = false;
  final ApiService _apiService = ApiService();

  UserModel? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;

  Future<String?> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.post('/login', data: {
        'email': email,
        'password': password,
        'device_name': 'mobile_device', // Bisa diganti dinamis
      });

      if (response.statusCode == 200) {
        final data = response.data;
        final token = data['token'];
        
        // Save to SharedPrefs
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', token);
        
        _user = UserModel.fromJson(data['user'], token: token);
        _isLoading = false;
        notifyListeners();
        return null; // Success
      }
      return 'Terjadi kesalahan tidak dikenal';
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return e.toString();
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.post('/logout');
    } catch (_) {}
    
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    _user = null;
    notifyListeners();
  }

  // Cek jika sudah login sebelumnya
  Future<void> checkAuth() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    
    if (token != null) {
      try {
        final response = await _apiService.get('/user');
        if (response.statusCode == 200) {
          _user = UserModel.fromJson(response.data, token: token);
          notifyListeners();
        }
      } catch (_) {
        await prefs.remove('token');
      }
    }
  }
}
