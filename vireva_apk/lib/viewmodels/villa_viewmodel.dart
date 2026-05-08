import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../models/villa_model.dart';
import '../repositories/villa_repository.dart';
import '../core/providers.dart';

// ─── State ───────────────────────────────────────────────────────────────────

class VillaState {
  final List<VillaModel> villas;
  final bool isLoading;
  final String? error;

  const VillaState({
    this.villas = const [],
    this.isLoading = false,
    this.error,
  });

  VillaState copyWith({
    List<VillaModel>? villas,
    bool? isLoading,
    String? error,
    bool clearError = false,
  }) {
    return VillaState(
      villas: villas ?? this.villas,
      isLoading: isLoading ?? this.isLoading,
      error: clearError ? null : (error ?? this.error),
    );
  }
}

// ─── ViewModel ───────────────────────────────────────────────────────────────

class VillaViewModel extends StateNotifier<VillaState> {
  final VillaRepository _repo;

  VillaViewModel(this._repo) : super(const VillaState());

  Future<void> fetchVillas() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final villas = await _repo.getVillas();
      state = state.copyWith(villas: villas, isLoading: false);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<bool> addVilla(Map<String, dynamic> data, List<XFile>? newImages) async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      await _repo.addVilla(data, newImages);
      await fetchVillas();
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<bool> updateVilla(
      int id, Map<String, dynamic> data, List<XFile>? newImages, List<String>? oldImages) async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      await _repo.updateVilla(id, data, newImages, oldImages);
      await fetchVillas();
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<bool> deleteVilla(int id) async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      await _repo.deleteVilla(id);
      await fetchVillas();
      return true;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }
}

// ─── Provider ────────────────────────────────────────────────────────────────

final villaViewModelProvider =
    StateNotifierProvider<VillaViewModel, VillaState>((ref) {
  return VillaViewModel(ref.watch(villaRepositoryProvider));
});
