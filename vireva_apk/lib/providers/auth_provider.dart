import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import '../core/providers.dart';

class AuthState {
  final UserModel? user;
  final bool isLoading;

  AuthState({this.user, this.isLoading = false});

  AuthState copyWith({UserModel? user, bool? isLoading}) {
    return AuthState(
      user: user ?? this.user,
      isLoading: isLoading ?? this.isLoading,
    );
  }

  bool get isAuthenticated => user != null;
}

class AuthNotifier extends StateNotifier<AuthState> {
  final ApiService _apiService;

  AuthNotifier(this._apiService) : super(AuthState());

  Future<String?> login(String email, String password) async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await _apiService.post('/login', data: {
        'email': email,
        'password': password,
        'device_name': 'mobile_device',
      });

      if (response.statusCode == 200) {
        final data = response.data;
        final token = data['token'];
        
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', token);
        
        final user = UserModel.fromJson(data['user'], token: token);
        state = state.copyWith(user: user, isLoading: false);
        return null;
      }
      state = state.copyWith(isLoading: false);
      return 'Terjadi kesalahan tidak dikenal';
    } catch (e) {
      state = state.copyWith(isLoading: false);
      return e.toString();
    }
  }
  
  Future<String?> register(String name, String email, String password) async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await _apiService.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
      });

      if (response.statusCode == 201) {
        final data = response.data;
        final token = data['token'];
        
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', token);
        
        final user = UserModel.fromJson(data['user'], token: token);
        state = state.copyWith(user: user, isLoading: false);
        return null;
      }
      state = state.copyWith(isLoading: false);
      return 'Gagal melakukan registrasi';
    } catch (e) {
      state = state.copyWith(isLoading: false);
      return e.toString();
    }
  }

  Future<void> logout() async {
    state = state.copyWith(user: null);
    
    try {
      await _apiService.post('/logout');
    } catch (_) {}
    
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }

  Future<void> checkAuth() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    
    if (token != null) {
      try {
        final response = await _apiService.get('/user');
        if (response.statusCode == 200) {
          final user = UserModel.fromJson(response.data, token: token);
          state = state.copyWith(user: user);
        }
      } catch (_) {
        await prefs.remove('token');
        state = state.copyWith(user: null);
      }
    }
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier(ref.watch(apiServiceProvider));
});
