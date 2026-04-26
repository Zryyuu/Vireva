import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../providers/booking_provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:intl/intl.dart';
import '../booking/payment_screen.dart';

class DetailVillaScreen extends StatefulWidget {
  final VillaModel villa;

  const DetailVillaScreen({super.key, required this.villa});

  @override
  State<DetailVillaScreen> createState() => _DetailVillaScreenState();
}

class _DetailVillaScreenState extends State<DetailVillaScreen> {
  DateTime _checkinDate = DateTime.now();
  DateTime _checkoutDate = DateTime.now().add(const Duration(days: 1));

  Future<void> _selectDate(BuildContext context, bool isCheckin) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: isCheckin ? _checkinDate : _checkoutDate,
      firstDate: isCheckin ? DateTime.now() : _checkinDate.add(const Duration(days: 1)),
      lastDate: DateTime(2101),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: AppColors.primary,
              onPrimary: Colors.white,
              onSurface: AppColors.primary,
            ),
          ),
          child: child!,
        );
      },
    );
    if (picked != null) {
      setState(() {
        if (isCheckin) {
          _checkinDate = picked;
          if (_checkoutDate.isBefore(_checkinDate)) {
            _checkoutDate = _checkinDate.add(const Duration(days: 1));
          }
        } else {
          _checkoutDate = picked;
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final bookingProvider = context.watch<BookingProvider>();
    final villa = widget.villa;
    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          // App Bar with Image
          SliverAppBar(
            expandedHeight: 400,
            pinned: true,
            leading: Padding(
              padding: const EdgeInsets.all(8.0),
              child: CircleAvatar(
                backgroundColor: Colors.white,
                child: IconButton(
                  icon: const Icon(Icons.arrow_back_rounded, color: AppColors.primary),
                  onPressed: () => Navigator.pop(context),
                ),
              ),
            ),
            flexibleSpace: FlexibleSpaceBar(
              background: CachedNetworkImage(
                imageUrl: villa.imageUrl ?? '',
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(color: Colors.grey[200]),
                errorWidget: (context, url, error) => Container(
                  color: Colors.grey[100],
                  child: const Icon(Icons.image_not_supported_outlined, size: 50, color: Colors.grey),
                ),
              ),
            ),
          ),

          // Content
          SliverToBoxAdapter(
            child: Container(
              padding: const EdgeInsets.all(AppSpacing.p24),
              decoration: const BoxDecoration(
                color: AppColors.background,
                borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: AppColors.accent.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          villa.tipe,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: AppColors.accent,
                          ),
                        ),
                      ),
                      Row(
                        children: [
                          const Icon(Icons.star_rounded, color: Colors.amber, size: 20),
                          Text(
                            ' 4.9 (124 Ulasan)',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: AppColors.textSecondary,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Text(
                    villa.nama,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 28,
                      fontWeight: FontWeight.w800,
                      color: AppColors.primary,
                      letterSpacing: -1,
                    ),
                  ),
                  const SizedBox(height: 24),
                  
                  // Facilities Row
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildFacilityItem(Icons.bed_rounded, '${villa.bedroom} Kamar Tidur'),
                      _buildFacilityItem(Icons.bathtub_rounded, '${villa.bathroom} Kamar Mandi'),
                      _buildFacilityItem(Icons.square_foot_rounded, '${villa.luas} m²'),
                    ],
                  ),
                  
                  const SizedBox(height: 32),
                  Text(
                    'Tentang Villa',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    villa.deskripsi,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 15,
                      color: AppColors.textSecondary,
                      height: 1.6,
                    ),
                  ),
                  
                  const SizedBox(height: 32),
                  Text(
                    'Pilih Tanggal Menginap',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Row(
                    children: [
                      Expanded(
                        child: _buildDateTile(
                          'Check-in',
                          DateFormat('dd MMM yyyy').format(_checkinDate),
                          () => _selectDate(context, true),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: _buildDateTile(
                          'Check-out',
                          DateFormat('dd MMM yyyy').format(_checkoutDate),
                          () => _selectDate(context, false),
                        ),
                      ),
                    ],
                  ),
                  
                  const SizedBox(height: 120), // Spacing for bottom button
                ],
              ),
            ),
          ),
        ],
      ),
      
      // Bottom Navigation Bar for Booking
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(AppSpacing.p24),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Harga Total',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 12,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  Text(
                    villa.formattedHarga,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                      color: AppColors.accent,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(width: 24),
            Expanded(
              flex: 2,
              child: ElevatedButton(
                onPressed: bookingProvider.isLoading 
                ? null 
                : () async {
                  try {
                    final snapToken = await bookingProvider.createBooking(
                      villaId: villa.id,
                      checkin: DateFormat('yyyy-MM-dd').format(_checkinDate),
                      checkout: DateFormat('yyyy-MM-dd').format(_checkoutDate),
                    );

                    if (context.mounted) {
                      if (snapToken != null) {
                        // Buka Halaman Pembayaran
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => PaymentScreen(
                              snapToken: snapToken,
                              onFinish: (success) {
                                Navigator.pop(context); // Tutup WebView
                                if (success) {
                                  _showSuccessDialog(context);
                                }
                              },
                            ),
                          ),
                        );
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Gagal membuat token pembayaran'), backgroundColor: Colors.redAccent),
                        );
                      }
                    }
                  } catch (e) {
                    if (context.mounted) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(content: Text(e.toString()), backgroundColor: Colors.redAccent),
                      );
                    }
                  }
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
                child: bookingProvider.isLoading 
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : Text(
                    'Pesan Sekarang',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDateTile(String label, String date, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppColors.border),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 12,
                color: AppColors.textSecondary,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              date,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showSuccessDialog(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const SizedBox(height: 20),
            const Icon(Icons.check_circle_outline_rounded, color: AppColors.accent, size: 80),
            const SizedBox(height: 24),
            Text(
              'Pemesanan Berhasil!',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
            const SizedBox(height: 12),
            Text(
              'Tim kami akan segera menghubungi Anda untuk konfirmasi.',
              textAlign: TextAlign.center,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 14,
                color: AppColors.textSecondary,
              ),
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(context); // Close dialog
                  Navigator.pop(context); // Back to home
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text('Kembali ke Beranda', style: TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFacilityItem(IconData icon, String label) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: AppColors.border),
          ),
          child: Icon(icon, color: AppColors.primary, size: 24),
        ),
        const SizedBox(height: 8),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 11,
            fontWeight: FontWeight.w600,
            color: AppColors.textSecondary,
          ),
        ),
      ],
    );
  }
}
