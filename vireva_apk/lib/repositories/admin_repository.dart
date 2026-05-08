import 'package:dio/dio.dart';
import '../services/api_service.dart';
import '../models/booking_model.dart';

class AdminRepository {
  final ApiService _api;

  AdminRepository(this._api);

  /// Ambil semua booking (admin)
  Future<List<BookingModel>> getBookings() async {
    final response = await _api.get('/admin/bookings');
    final List<dynamic> data = response.data is List
        ? response.data
        : (response.data['data'] ?? []);
    return data.map((json) => BookingModel.fromJson(json)).toList();
  }

  /// Ambil statistik ringkasan (admin)
  Future<Map<String, dynamic>> getStats() async {
    final response = await _api.get('/admin/stats');
    return response.data;
  }

  /// Verifikasi pembayaran: status = 'settlement' | 'cancel'
  Future<void> verifyPayment(int id, String status, {String? catatan}) async {
    final requestData = <String, dynamic>{'status': status};
    if (catatan != null) requestData['catatan'] = catatan;
    
    await _api.post('/admin/bookings/$id/verify', data: requestData);
  }

  /// Proses aksi operasional: action = 'checkin' | 'checkout' | 'cancel'
  Future<void> processAction(int id, String action) async {
    await _api.post('/admin/transaksi/$id/action', data: {'action': action});
  }

  /// Buat booking manual (admin)
  Future<void> createManualBooking(
      Map<String, dynamic> data, String? imagePath) async {
    final formData = FormData.fromMap(data);
    if (imagePath != null) {
      formData.files.add(MapEntry(
        'bukti_pembayaran',
        await MultipartFile.fromFile(imagePath),
      ));
    }
    await _api.postForm('/admin/bookings/manual', data: formData);
  }
}
