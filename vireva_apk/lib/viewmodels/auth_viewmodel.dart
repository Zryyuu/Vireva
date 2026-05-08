import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/user_model.dart';
import '../repositories/auth_repository.dart';
import '../core/providers.dart';

// ─── State ───────────────────────────────────────────────────────────────────

class AuthState {
  final UserModel? user;
  final bool isLoading;
  final String? error;

  const AuthState({this.user, this.isLoading = false, this.error});

  AuthState copyWith({
    UserModel? user,
    bool? isLoading,
    String? error,
    bool clearError = false,
    bool clearUser = false,
  }) {
    return AuthState(
      user: clearUser ? null : (user ?? this.user),
      isLoading: isLoading ?? this.isLoading,
      error: clearError ? null : (error ?? this.error),
    );
  }

  bool get isAuthenticated => user != null;
}

// ─── ViewModel ───────────────────────────────────────────────────────────────

class AuthViewModel extends StateNotifier<AuthState> {
  final AuthRepository _repo;

  AuthViewModel(this._repo) : super(const AuthState());

  Future<bool> login(String email, String password) async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final user = await _repo.login(email, password);
      state = state.copyWith(user: user, isLoading: false);
      return true;
    } catch (e) {
      state = state.copyWith(
          isLoading: false, error: _parseError(e));
      return false;
    }
  }

  Future<bool> register(String name, String email, String password) async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final user = await _repo.register(name, email, password);
      state = state.copyWith(user: user, isLoading: false);
      return true;
    } catch (e) {
      state = state.copyWith(
          isLoading: false, error: _parseError(e));
      return false;
    }
  }

  Future<void> logout() async {
    state = state.copyWith(clearUser: true);
    await _repo.logout();
  }

  Future<void> checkAuth() async {
    state = state.copyWith(isLoading: true);
    final user = await _repo.getUser();
    state = state.copyWith(user: user, isLoading: false);
  }

  String _parseError(dynamic e) {
    final str = e.toString();
    if (str.contains('credentials') || str.contains('password')) {
      return 'Email atau password salah.';
    }
    if (str.contains('connect') || str.contains('timeout')) {
      return 'Tidak dapat terhubung ke server.';
    }
    return str.replaceFirst('Exception: ', '');
  }
}

// ─── Provider ────────────────────────────────────────────────────────────────

final authViewModelProvider =
    StateNotifierProvider<AuthViewModel, AuthState>((ref) {
  return AuthViewModel(ref.watch(authRepositoryProvider));
});
