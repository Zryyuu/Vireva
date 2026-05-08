import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_service.dart';
import '../repositories/auth_repository.dart';
import '../repositories/villa_repository.dart';
import '../repositories/booking_repository.dart';
import '../repositories/admin_repository.dart';

// ─── Service ─────────────────────────────────────────────────────────────────

final apiServiceProvider = Provider<ApiService>((ref) => ApiService());

// ─── Repositories ─────────────────────────────────────────────────────────────

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(ref.watch(apiServiceProvider));
});

final villaRepositoryProvider = Provider<VillaRepository>((ref) {
  return VillaRepository(ref.watch(apiServiceProvider));
});

final bookingRepositoryProvider = Provider<BookingRepository>((ref) {
  return BookingRepository(ref.watch(apiServiceProvider));
});

final adminRepositoryProvider = Provider<AdminRepository>((ref) {
  return AdminRepository(ref.watch(apiServiceProvider));
});
