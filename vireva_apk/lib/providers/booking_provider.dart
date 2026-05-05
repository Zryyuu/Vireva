import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:dio/dio.dart';
import '../services/api_service.dart';
import '../core/providers.dart';

class BookingState {
  final List<dynamic> bookings;
  final bool isLoading;

  BookingState({this.bookings = const [], this.isLoading = false});

  BookingState copyWith({List<dynamic>? bookings, bool? isLoading}) {
    return BookingState(
      bookings: bookings ?? this.bookings,
      isLoading: isLoading ?? this.isLoading,
    );
  }
}

class BookingNotifier extends StateNotifier<BookingState> {
  final ApiService _apiService;

  BookingNotifier(this._apiService) : super(BookingState());

  Future<void> fetchBookings({bool isAdmin = false}) async {
    state = state.copyWith(isLoading: true);

    try {
      final url = isAdmin ? '/admin/bookings' : '/bookings';
      final response = await _apiService.get(url);
      if (response.statusCode == 200) {
        state = state.copyWith(bookings: response.data, isLoading: false);
      } else {
        state = state.copyWith(isLoading: false);
      }
    } catch (e) {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> createBooking({
    required int villaId,
    required String checkin,
    required String checkout,
  }) async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await _apiService.post('/bookings', data: {
        'villa_id': villaId,
        'tanggal_checkin': checkin,
        'tanggal_checkout': checkout,
      });

      if (response.statusCode == 201) {
        await fetchBookings();
        return true;
      }
      return false;
    } catch (e) {
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> uploadBukti(int id, String imagePath) async {
    state = state.copyWith(isLoading: true);

    try {
      FormData formData = FormData.fromMap({
        'bukti_pembayaran': await MultipartFile.fromFile(imagePath),
      });

      final response = await _apiService.post('/bookings/$id/upload-bukti', data: formData);

      if (response.statusCode == 200) {
        await fetchBookings();
        return true;
      }
      return false;
    } catch (e) {
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> verifyBooking(int id, String status, {String? catatan}) async {
    state = state.copyWith(isLoading: true);

    try {
      final response = await _apiService.post('/admin/bookings/$id/verify', data: {
        'status': status,
        'catatan': catatan,
      });

      if (response.statusCode == 200) {
        await fetchBookings(isAdmin: true);
        return true;
      }
      return false;
    } catch (e) {
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> createManualBooking(Map<String, dynamic> data, String? imagePath) async {
    state = state.copyWith(isLoading: true);

    try {
      FormData formData = FormData.fromMap(data);
      if (imagePath != null) {
        formData.files.add(MapEntry(
          'bukti_pembayaran',
          await MultipartFile.fromFile(imagePath),
        ));
      }

      final response = await _apiService.post('/admin/bookings/manual', data: formData);

      if (response.statusCode == 201) {
        await fetchBookings(isAdmin: true);
        return true;
      }
      return false;
    } catch (e) {
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> updateBookingStatus(int id, String action) async {
    state = state.copyWith(isLoading: true);

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
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }
}

final bookingProvider = StateNotifierProvider<BookingNotifier, BookingState>((ref) {
  return BookingNotifier(ref.watch(apiServiceProvider));
});
