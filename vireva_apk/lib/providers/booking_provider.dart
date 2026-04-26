import 'package:flutter/material.dart';
import '../services/api_service.dart';

class BookingProvider with ChangeNotifier {
  List<dynamic> _bookings = [];
  bool _isLoading = false;
  final ApiService _apiService = ApiService();

  List<dynamic> get bookings => _bookings;
  bool get isLoading => _isLoading;

  Future<void> fetchBookings({bool isAdmin = false}) async {
    _isLoading = true;
    notifyListeners();

    try {
      final url = isAdmin ? '/admin/bookings' : '/bookings';
      final response = await _apiService.get(url);
      if (response.statusCode == 200) {
        _bookings = response.data;
      }
    } catch (e) {
      debugPrint('Fetch Bookings Error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

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
        await fetchBookings();
        return response.data['snap_token']; // Kembalikan snap_token jika ada
      }
      return null;
    } catch (e) {
      debugPrint('Booking Error: $e');
      rethrow; // Biarkan UI menangkap error
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> updateBookingStatus(int id, String action) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.post('/admin/transaksi/$id/action', data: {
        'action': action,
      });

      if (response.statusCode == 200) {
        await fetchBookings(isAdmin: true);
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Update Booking Error: $e');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
