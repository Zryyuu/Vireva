import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AdminProvider with ChangeNotifier {
  Map<String, dynamic>? _stats;
  bool _isLoading = false;
  final ApiService _apiService = ApiService();

  Map<String, dynamic>? get stats => _stats;
  bool get isLoading => _isLoading;

  Future<void> fetchStats() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.get('/admin/stats');
      if (response.statusCode == 200) {
        _stats = response.data;
      }
    } catch (e) {
      debugPrint('Error fetching admin stats: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
