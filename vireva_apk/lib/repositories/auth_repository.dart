import 'package:shared_preferences/shared_preferences.dart';
import '../services/api_service.dart';
import '../models/user_model.dart';

class AuthRepository {
  final ApiService _api;

  AuthRepository(this._api);

  Future<UserModel> login(String email, String password) async {
    final response = await _api.post('/login', data: {
      'email': email,
      'password': password,
      'device_name': 'mobile_device',
    });

    final data = response.data;
    final token = data['token'] as String;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);

    return UserModel.fromJson(data['user'], token: token);
  }

  Future<UserModel> register(String name, String email, String password) async {
    final response = await _api.post('/register', data: {
      'name': name,
      'email': email,
      'password': password,
    });

    final data = response.data;
    final token = data['token'] as String;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);

    return UserModel.fromJson(data['user'], token: token);
  }

  Future<void> logout() async {
    try {
      await _api.post('/logout');
    } catch (_) {}

    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }

  Future<UserModel?> getUser() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    if (token == null) return null;

    try {
      final response = await _api.get('/user');
      if (response.statusCode == 200) {
        return UserModel.fromJson(response.data, token: token);
      }
    } catch (_) {
      await prefs.remove('token');
    }
    return null;
  }
}
