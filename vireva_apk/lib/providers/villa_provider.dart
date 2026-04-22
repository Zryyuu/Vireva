import 'package:flutter/material.dart';
import '../models/villa_model.dart';
import '../services/api_service.dart';

class VillaProvider with ChangeNotifier {
  List<VillaModel> _villas = [];
  bool _isLoading = false;
  String? _error;
  final ApiService _apiService = ApiService();

  List<VillaModel> get villas => _villas;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchVillas() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.get('/villas');
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'] ?? response.data;
        _villas = data.map((json) => VillaModel.fromJson(json)).toList();
      } else {
        _error = 'Gagal mengambil data villa';
      }
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
