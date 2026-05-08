import 'villa_model.dart';

class TamuModel {
  final int id;
  final String namaTamu;
  final String? noHape;
  final String? noIdentitas;
  final String? alamat;

  TamuModel({
    required this.id,
    required this.namaTamu,
    this.noHape,
    this.noIdentitas,
    this.alamat,
  });

  factory TamuModel.fromJson(Map<String, dynamic> json) {
    return TamuModel(
      id: int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      namaTamu: json['nama_tamu'] ?? '',
      noHape: json['no_hape'],
      noIdentitas: json['no_identitas'],
      alamat: json['alamat'],
    );
  }
}

class BookingModel {
  final int id;
  final int? villaId;
  final int? tamuId;
  final String tanggalCheckin;
  final String tanggalCheckout;
  final int totalHari;
  final double totalBiaya;
  final String statusPemesanan;
  final String statusPembayaran;
  final String? metodePembayaran;
  final String? buktiPembayaran;
  final String? catatanAdmin;
  final String? createdAt;
  final VillaModel? villa;
  final TamuModel? tamu;

  BookingModel({
    required this.id,
    this.villaId,
    this.tamuId,
    required this.tanggalCheckin,
    required this.tanggalCheckout,
    required this.totalHari,
    required this.totalBiaya,
    required this.statusPemesanan,
    required this.statusPembayaran,
    this.metodePembayaran,
    this.buktiPembayaran,
    this.catatanAdmin,
    this.createdAt,
    this.villa,
    this.tamu,
  });

  factory BookingModel.fromJson(Map<String, dynamic> json) {
    return BookingModel(
      id: int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      villaId: int.tryParse(json['villa_id']?.toString() ?? '0'),
      tamuId: int.tryParse(json['tamu_id']?.toString() ?? '0'),
      tanggalCheckin: json['tanggal_checkin'] ?? '',
      tanggalCheckout: json['tanggal_checkout'] ?? '',
      totalHari: int.tryParse(json['total_hari']?.toString() ?? '0') ?? 0,
      totalBiaya: double.tryParse(json['total_biaya']?.toString() ?? '0') ?? 0.0,
      statusPemesanan: json['status_pemesanan'] ?? 'menunggu',
      statusPembayaran: json['status_pembayaran'] ?? 'pending',
      metodePembayaran: json['metode_pembayaran'],
      buktiPembayaran: json['bukti_pembayaran'],
      catatanAdmin: json['catatan_admin'],
      createdAt: json['created_at'],
      villa: json['villa'] != null ? VillaModel.fromJson(json['villa']) : null,
      tamu: json['tamu'] != null ? TamuModel.fromJson(json['tamu']) : null,
    );
  }

  // Status helpers
  bool get isPending => statusPembayaran == 'pending';
  bool get isSettled => statusPembayaran == 'settlement';
  bool get isCancelled =>
      statusPembayaran == 'cancel' || statusPemesanan == 'batal';
  bool get isActive => statusPemesanan == 'aktif';
  bool get isCompleted => statusPemesanan == 'selesai';
  bool get hasBukti => buktiPembayaran != null && buktiPembayaran!.isNotEmpty;

  String get statusLabel {
    if (isCancelled) return 'Dibatalkan';
    if (isCompleted) return 'Selesai';
    if (isActive) return 'Sedang Menginap';
    if (isSettled) return 'Terkonfirmasi';
    return 'Menunggu Konfirmasi';
  }

  String get formattedTotalBiaya {
    return 'Rp ${totalBiaya.toStringAsFixed(0).replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (m) => '${m[1]}.')}';
  }
}
