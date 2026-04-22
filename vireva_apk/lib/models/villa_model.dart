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
  });

  factory VillaModel.fromJson(Map<String, dynamic> json) {
    return VillaModel(
      id: json['id'],
      nama: json['nama'],
      tipe: json['tipe'],
      harga: json['harga'].toDouble(),
      formattedHarga: json['formatted_harga'],
      imageUrl: json['image_url'],
      bedroom: json['detail']['bedroom'],
      bathroom: json['detail']['bathroom'],
      luas: json['detail']['luas'].toDouble(),
      deskripsi: json['detail']['deskripsi'] ?? '',
    );
  }
}
