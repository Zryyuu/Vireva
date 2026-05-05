import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/villa_model.dart';
import '../services/api_service.dart';
import 'package:dio/dio.dart';
import '../core/providers.dart';

class VillaState {
  final List<VillaModel> villas;
  final bool isLoading;
  final String? error;

  VillaState({
    this.villas = const [],
    this.isLoading = false,
    this.error,
  });

  VillaState copyWith({
    List<VillaModel>? villas,
    bool? isLoading,
    String? error,
  }) {
    return VillaState(
      villas: villas ?? this.villas,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class VillaNotifier extends StateNotifier<VillaState> {
  final ApiService _apiService;

  VillaNotifier(this._apiService) : super(VillaState());

  Future<void> fetchVillas() async {
    state = state.copyWith(isLoading: true, error: null);

    try {
      final response = await _apiService.get('/villas');
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'] ?? response.data;
        final villas = data.map((json) => VillaModel.fromJson(json)).toList();
        state = state.copyWith(villas: villas, isLoading: false);
      } else {
        state = state.copyWith(isLoading: false, error: 'Gagal mengambil data villa');
      }
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<bool> addVilla(Map<String, dynamic> data, String? imagePath) async {
    state = state.copyWith(isLoading: true);
    try {
      FormData formData = FormData.fromMap(data);
      if (imagePath != null) {
        formData.files.add(MapEntry(
          'foto',
          await MultipartFile.fromFile(imagePath),
        ));
      }

      final response = await _apiService.post('/admin/villas', data: formData);
      if (response.statusCode == 201 || response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      state = state.copyWith(error: e.toString(), isLoading: false);
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> updateVilla(int id, Map<String, dynamic> data, String? imagePath) async {
    state = state.copyWith(isLoading: true);
    try {
      FormData formData = FormData.fromMap(data);
      if (imagePath != null) {
        formData.files.add(MapEntry(
          'foto',
          await MultipartFile.fromFile(imagePath),
        ));
      }

      final response = await _apiService.post('/admin/villas/$id', data: formData);
      if (response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      state = state.copyWith(error: e.toString(), isLoading: false);
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }

  Future<bool> deleteVilla(int id) async {
    state = state.copyWith(isLoading: true);
    try {
      final response = await _apiService.delete('/admin/villas/$id');
      if (response.statusCode == 200) {
        await fetchVillas();
        return true;
      }
      return false;
    } catch (e) {
      state = state.copyWith(error: e.toString(), isLoading: false);
      return false;
    } finally {
      state = state.copyWith(isLoading: false);
    }
  }
}

final villaProvider = StateNotifierProvider<VillaNotifier, VillaState>((ref) {
  return VillaNotifier(ref.watch(apiServiceProvider));
});
