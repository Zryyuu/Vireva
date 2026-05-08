import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/booking_model.dart';
import '../repositories/admin_repository.dart';
import '../core/providers.dart';

// ─── State ───────────────────────────────────────────────────────────────────

class AdminState {
  final List<BookingModel> bookings;
  final Map<String, dynamic> stats;
  final bool isLoading;
  final String? error;
  final String? successMessage;

  const AdminState({
    this.bookings = const [],
    this.stats = const {'staying': 0, 'upcoming': 0, 'total': 0},
    this.isLoading = false,
    this.error,
    this.successMessage,
  });

  AdminState copyWith({
    List<BookingModel>? bookings,
    Map<String, dynamic>? stats,
    bool? isLoading,
    String? error,
    String? successMessage,
    bool clearMessages = false,
  }) {
    return AdminState(
      bookings: bookings ?? this.bookings,
      stats: stats ?? this.stats,
      isLoading: isLoading ?? this.isLoading,
      error: clearMessages ? null : (error ?? this.error),
      successMessage:
          clearMessages ? null : (successMessage ?? this.successMessage),
    );
  }
}

// ─── ViewModel ───────────────────────────────────────────────────────────────

class AdminViewModel extends StateNotifier<AdminState> {
  final AdminRepository _adminRepo;
  AdminViewModel(this._adminRepo) : super(const AdminState());

  Future<void> fetchBookings() async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      final results = await Future.wait([
        _adminRepo.getBookings(),
        _adminRepo.getStats(),
      ]);
      state = state.copyWith(
        bookings: results[0] as List<BookingModel>,
        stats: results[1] as Map<String, dynamic>,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<bool> verifyPayment(int id, String status, {String? catatan}) async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      await _adminRepo.verifyPayment(id, status, catatan: catatan);
      await fetchBookings();
      final msg = status == 'settlement'
          ? 'Pembayaran berhasil diverifikasi.'
          : 'Reservasi dibatalkan.';
      state = state.copyWith(successMessage: msg);
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<bool> processAction(int id, String action) async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      await _adminRepo.processAction(id, action);
      await fetchBookings();
      final messages = {
        'checkin': 'Tamu berhasil Check-in.',
        'checkout': 'Tamu berhasil Check-out.',
        'cancel': 'Reservasi dibatalkan.',
      };
      state = state.copyWith(successMessage: messages[action] ?? 'Berhasil.');
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<bool> createManualBooking(
      Map<String, dynamic> data, String? imagePath) async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      await _adminRepo.createManualBooking(data, imagePath);
      await fetchBookings();
      state = state.copyWith(successMessage: 'Pemesanan manual berhasil dibuat.');
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  void clearMessages() => state = state.copyWith(clearMessages: true);
}

// ─── Provider ────────────────────────────────────────────────────────────────

final adminViewModelProvider =
    StateNotifierProvider<AdminViewModel, AdminState>((ref) {
  return AdminViewModel(
    ref.watch(adminRepositoryProvider),
  );
});
