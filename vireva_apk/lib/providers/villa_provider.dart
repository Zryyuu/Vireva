import 'package:flutter/material.dart';
import '../models/villa_model.dart';
import '../services/api_service.dart';
import 'package:dio/dio.dart';

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

  Future<bool> addVilla(Map<String, dynamic> data, String? imagePath) async {
    _isLoading = true;
    notifyListeners();

    try {
      FormData formData = FormData.fromMap(data);
      if (imagePath != null) {
        formData.files.add(MapEntry(
          'foto',
          await MultipartFile.fromFile(imagePath),
        ));
      }

      final response = await _apiService.post('/admin/villa', data: formData);
      if (response.statusCode == 201 || response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> updateVilla(int id, Map<String, dynamic> data, String? imagePath) async {
    _isLoading = true;
    notifyListeners();

    try {
      FormData formData = FormData.fromMap(data);
      if (imagePath != null) {
        formData.files.add(MapEntry(
          'foto',
          await MultipartFile.fromFile(imagePath),
        ));
      }

      final response = await _apiService.post('/admin/villa/$id', data: formData);
      if (response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> deleteVilla(int id) async {
    _isLoading = true;
    notifyListeners();
    try {
      final response = await _apiService.delete('/admin/villa/$id');
      if (response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
