import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/booking_model.dart';
import '../repositories/booking_repository.dart';
import '../core/providers.dart';

// ─── State ───────────────────────────────────────────────────────────────────

class BookingState {
  final List<BookingModel> bookings;
  final bool isLoading;
  final String? error;
  final String? successMessage;

  const BookingState({
    this.bookings = const [],
    this.isLoading = false,
    this.error,
    this.successMessage,
  });

  BookingState copyWith({
    List<BookingModel>? bookings,
    bool? isLoading,
    String? error,
    String? successMessage,
    bool clearMessages = false,
  }) {
    return BookingState(
      bookings: bookings ?? this.bookings,
      isLoading: isLoading ?? this.isLoading,
      error: clearMessages ? null : (error ?? this.error),
      successMessage:
          clearMessages ? null : (successMessage ?? this.successMessage),
    );
  }
}

// ─── ViewModel ───────────────────────────────────────────────────────────────

class BookingViewModel extends StateNotifier<BookingState> {
  final BookingRepository _repo;

  BookingViewModel(this._repo) : super(const BookingState());

  Future<void> fetchBookings() async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      final bookings = await _repo.getUserBookings();
      state = state.copyWith(bookings: bookings, isLoading: false);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<bool> createBooking({
    required int villaId,
    required String checkin,
    required String checkout,
  }) async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      await _repo.createBooking(
          villaId: villaId, checkin: checkin, checkout: checkout);
      await fetchBookings();
      state = state.copyWith(successMessage: 'Reservasi berhasil dibuat!');
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<bool> uploadBukti(int bookingId, String imagePath) async {
    state = state.copyWith(isLoading: true, clearMessages: true);
    try {
      await _repo.uploadBukti(bookingId, imagePath);
      await fetchBookings();
      state = state.copyWith(successMessage: 'Bukti pembayaran berhasil dikirim!');
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  void clearMessages() => state = state.copyWith(clearMessages: true);
}

// ─── Provider ────────────────────────────────────────────────────────────────

final bookingViewModelProvider =
    StateNotifierProvider<BookingViewModel, BookingState>((ref) {
  return BookingViewModel(ref.watch(bookingRepositoryProvider));
});
