class VillaModel {
  final int id;
  final String nama;
  final String tipe;
  final double harga;
  final String formattedHarga;
  final String? imageUrl;
  final int bedroom;
  final int bathroom;
  final double luas;
  final String deskripsi;
  final int kapasitas;
  final String statusVilla;
  final List<String> allImages;
  final List<String> rawImages;
  final List<Map<String, String>> bookedDates;

  VillaModel({
    required this.id,
    required this.nama,
    required this.tipe,
    required this.harga,
    required this.formattedHarga,
    this.imageUrl,
    required this.bedroom,
    required this.bathroom,
    required this.luas,
    required this.deskripsi,
    required this.kapasitas,
    this.statusVilla = 'tersedia',
    this.allImages = const [],
    this.rawImages = const [],
    this.bookedDates = const [],
  });

  factory VillaModel.fromJson(Map<String, dynamic> json) {
    final detail = json['detail'] as Map<String, dynamic>? ?? {};
    return VillaModel(
      id: int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      nama: json['nama'] ?? '',
      tipe: json['tipe'] ?? '',
      harga: double.tryParse(json['harga']?.toString() ?? '0') ?? 0.0,
      formattedHarga: json['formatted_harga'] ?? '',
      imageUrl: json['image_url'],
      bedroom: int.tryParse(detail['bedroom']?.toString() ?? '0') ?? 0,
      bathroom: int.tryParse(detail['bathroom']?.toString() ?? '0') ?? 0,
      luas: double.tryParse(detail['luas']?.toString() ?? '0') ?? 0.0,
      deskripsi: detail['deskripsi'] ?? '',
      kapasitas: int.tryParse(json['kapasitas']?.toString() ?? '0') ?? 0,
      statusVilla: json['status_villa'] ?? 'tersedia',
      allImages: _parseImages(json['all_images']),
      rawImages: _parseImages(json['raw_foto']),
      bookedDates: _parseBookedDates(json['booked_dates']),
    );
  }

  static List<Map<String, String>> _parseBookedDates(dynamic data) {
    if (data is List) {
      return data.map((e) => <String, String>{
        'checkin': e['checkin'].toString(),
        'checkout': e['checkout'].toString(),
      }).toList();
    }
    return <Map<String, String>>[];
  }

  static List<String> _parseImages(dynamic data) {
    if (data is List) {
      return data.map((e) => e.toString()).toList();
    } else if (data is String && data.isNotEmpty) {
      return [data];
    }
    return [];
  }

  bool get isAvailable => statusVilla == 'tersedia';
}
