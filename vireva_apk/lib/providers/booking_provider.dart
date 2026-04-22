import 'package:flutter/material.dart';
import '../services/api_service.dart';

class BookingProvider with ChangeNotifier {
  bool _isLoading = false;
  final ApiService _apiService = ApiService();

  bool get isLoading => _isLoading;

  Future<String?> createBooking({
    required int villaId,
    required String checkin,
    required String checkout,
  }) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.post('/bookings', data: {
        'villa_id': villaId,
        'tanggal_checkin': checkin,
        'tanggal_checkout': checkout,
      });

      if (response.statusCode == 201) {
        _isLoading = false;
        notifyListeners();
        return null; // Success
      }
      return 'Gagal membuat pemesanan';
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return e.toString();
    }
  }
}
