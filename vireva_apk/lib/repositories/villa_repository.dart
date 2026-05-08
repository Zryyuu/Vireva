import 'package:dio/dio.dart';
import 'package:image_picker/image_picker.dart';
import '../services/api_service.dart';
import '../models/villa_model.dart';

class VillaRepository {
  final ApiService _api;

  VillaRepository(this._api);

  Future<List<VillaModel>> getVillas() async {
    final response = await _api.get('/villas');
    final List<dynamic> data = response.data['data'] ?? response.data;
    return data.map((json) => VillaModel.fromJson(json)).toList();
  }

  Future<VillaModel> getVillaDetail(int id) async {
    final response = await _api.get('/villas/$id');
    return VillaModel.fromJson(response.data['data'] ?? response.data);
  }

  Future<void> addVilla(Map<String, dynamic> data, List<XFile>? newImages) async {
    final formData = FormData.fromMap(data);
    if (newImages != null && newImages.isNotEmpty) {
      for (var file in newImages) {
        final bytes = await file.readAsBytes();
        formData.files.add(MapEntry(
          'foto[]',
          MultipartFile.fromBytes(bytes, filename: file.name),
        ));
      }
    }
    await _api.postForm('/admin/villas', data: formData);
  }

  Future<void> updateVilla(
      int id, Map<String, dynamic> data, List<XFile>? newImages, List<String>? oldImages) async {
    final formData = FormData.fromMap(data);
    
    if (newImages != null && newImages.isNotEmpty) {
      for (var file in newImages) {
        final bytes = await file.readAsBytes();
        formData.files.add(MapEntry(
          'foto[]',
          MultipartFile.fromBytes(bytes, filename: file.name),
        ));
      }
    }
    
    if (oldImages != null && oldImages.isNotEmpty) {
      for (var oldPath in oldImages) {
        formData.fields.add(MapEntry('old_foto[]', oldPath));
      }
    } else if (oldImages != null && oldImages.isEmpty) {
      // If the list is empty but explicitly provided, it means all existing images were deleted.
      // We pass an empty value so the backend knows to clear old_foto.
      formData.fields.add(const MapEntry('old_foto[]', ''));
    }

    await _api.postForm('/admin/villas/$id', data: formData);
  }

  Future<void> deleteVilla(int id) async {
    await _api.delete('/admin/villas/$id');
  }
}
