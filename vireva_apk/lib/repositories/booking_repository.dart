import 'package:dio/dio.dart';
import '../services/api_service.dart';
import '../models/booking_model.dart';

class BookingRepository {
  final ApiService _api;

  BookingRepository(this._api);

  /// Ambil booking milik user yang sedang login
  Future<List<BookingModel>> getUserBookings() async {
    final response = await _api.get('/bookings');
    final List<dynamic> data = response.data is List
        ? response.data
        : (response.data['data'] ?? []);
    return data.map((json) => BookingModel.fromJson(json)).toList();
  }

  /// Buat booking baru (user)
  Future<BookingModel> createBooking({
    required int villaId,
    required String checkin,
    required String checkout,
  }) async {
    final response = await _api.post('/bookings', data: {
      'villa_id': villaId,
      'tanggal_checkin': checkin,
      'tanggal_checkout': checkout,
    });
    return BookingModel.fromJson(response.data['data'] ?? response.data);
  }

  /// Upload bukti pembayaran (user)
  Future<void> uploadBukti(int bookingId, String imagePath) async {
    final formData = FormData.fromMap({
      'bukti_pembayaran': await MultipartFile.fromFile(imagePath),
    });
    await _api.postForm('/bookings/$bookingId/upload-bukti', data: formData);
  }
}
