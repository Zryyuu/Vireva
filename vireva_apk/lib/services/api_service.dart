import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/foundation.dart' show kIsWeb;

class ApiService {
  static String get baseUrl {
    if (kIsWeb) return 'http://127.0.0.1:8000/api';
    return 'http://192.168.43.116:8000/api';
  }

  final Dio _dio;

  ApiService()
      : _dio = Dio(BaseOptions(
          baseUrl: baseUrl,
          connectTimeout: const Duration(seconds: 15),
          receiveTimeout: const Duration(seconds: 15),
          headers: {
            'Accept': 'application/json',
          },
        )) {
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final prefs = await SharedPreferences.getInstance();
        final token = prefs.getString('token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        return handler.next(options);
      },
      onError: (err, handler) {
        final message = err.response?.data?['message'] ??
            'Koneksi ke server gagal.';
        return handler.reject(
          DioException(
            requestOptions: err.requestOptions,
            response: err.response,
            error: message,
            message: message,
            type: err.type,
          ),
        );
      },
    ));
  }

  Future<Response> get(String path,
      {Map<String, dynamic>? queryParameters}) async {
    try {
      return await _dio.get(path, queryParameters: queryParameters);
    } on DioException catch (e) {
      throw Exception(e.message ?? 'Gagal mengambil data.');
    }
  }

  Future<Response> post(String path, {dynamic data}) async {
    try {
      return await _dio.post(path,
          data: data,
          options: Options(contentType: 'application/json'));
    } on DioException catch (e) {
      throw Exception(e.message ?? 'Gagal mengirim data.');
    }
  }

  /// Khusus multipart/form-data (upload file)
  Future<Response> postForm(String path, {required FormData data}) async {
    try {
      return await _dio.post(path,
          data: data,
          options: Options(contentType: 'multipart/form-data'));
    } on DioException catch (e) {
      throw Exception(e.message ?? 'Gagal mengunggah file.');
    }
  }

  Future<Response> put(String path, {dynamic data}) async {
    try {
      return await _dio.put(path, data: data);
    } on DioException catch (e) {
      throw Exception(e.message ?? 'Gagal memperbarui data.');
    }
  }

  Future<Response> delete(String path) async {
    try {
      return await _dio.delete(path);
    } on DioException catch (e) {
      throw Exception(e.message ?? 'Gagal menghapus data.');
    }
  }
}
