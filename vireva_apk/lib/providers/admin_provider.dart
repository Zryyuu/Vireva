import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_service.dart';
import '../core/providers.dart';

class AdminState {
  final Map<String, dynamic>? stats;
  final bool isLoading;

  AdminState({this.stats, this.isLoading = false});

  AdminState copyWith({Map<String, dynamic>? stats, bool? isLoading}) {
    return AdminState(
      stats: stats ?? this.stats,
      isLoading: isLoading ?? this.isLoading,
    );
  }
}

class AdminNotifier extends StateNotifier<AdminState> {
  final ApiService _apiService;

  AdminNotifier(this._apiService) : super(AdminState());

  Future<void> fetchStats() async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await _apiService.get('/admin/stats');
      if (response.statusCode == 200) {
        state = state.copyWith(stats: response.data, isLoading: false);
      } else {
        state = state.copyWith(isLoading: false);
      }
    } catch (e) {
      state = state.copyWith(isLoading: false);
    }
  }
}

final adminProvider = StateNotifierProvider<AdminNotifier, AdminState>((ref) {
  return AdminNotifier(ref.watch(apiServiceProvider));
});
