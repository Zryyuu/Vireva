import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../providers/booking_provider.dart';

class ManageBookingScreen extends StatefulWidget {
  const ManageBookingScreen({super.key});

  @override
  State<ManageBookingScreen> createState() => _ManageBookingScreenState();
}

class _ManageBookingScreenState extends State<ManageBookingScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      if (mounted) {
        context.read<BookingProvider>().fetchBookings(isAdmin: true);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final bookingProvider = context.watch<BookingProvider>();

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: AppColors.primary, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Daftar Reservasi',
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.primary,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
      ),
      body: bookingProvider.isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.accent))
          : RefreshIndicator(
              onRefresh: () => bookingProvider.fetchBookings(isAdmin: true),
              color: AppColors.accent,
              child: ListView.builder(
                padding: const EdgeInsets.all(AppSpacing.p24),
                itemCount: bookingProvider.bookings.length,
                itemBuilder: (context, index) {
                  final booking = bookingProvider.bookings[index];
                  return _buildBookingCard(booking);
                },
              ),
            ),
    );
  }

  Widget _buildBookingCard(dynamic booking) {
    final status = booking['status_pemesanan'] ?? 'menunggu';
    Color statusColor;
    String statusText;

    switch (status) {
      case 'aktif':
        statusColor = Colors.blue;
        statusText = 'AKTIF / IN-STAY';
        break;
      case 'selesai':
        statusColor = AppColors.accent;
        statusText = 'SELESAI';
        break;
      case 'batal':
        statusColor = Colors.red;
        statusText = 'BATAL';
        break;
      default:
        statusColor = Colors.orange;
        statusText = 'MENUNGGU';
    }

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: statusColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: statusColor.withOpacity(0.2)),
                ),
                child: Text(
                  statusText,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                    color: statusColor,
                  ),
                ),
              ),
              Text(
                '#${booking['id']}',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: Colors.grey[400],
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Text(
            booking['tamu']['nama_tamu'] ?? 'Tamu',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppColors.primary,
            ),
          ),
          Text(
            booking['villa']['nama_villa'] ?? 'Villa',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: AppColors.textSecondary,
            ),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 12),
            child: Divider(height: 1, color: AppColors.border),
          ),
          Row(
            children: [
              const Icon(Icons.calendar_today_rounded, size: 14, color: Colors.grey),
              const SizedBox(width: 8),
              Text(
                '${booking['tanggal_checkin']} - ${booking['tanggal_checkout']}',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: AppColors.primary,
                ),
              ),
            ],
          ),
          if (status == 'aktif' || (status == 'menunggu' && booking['status_pembayaran'] == 'settlement')) ...[
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () async {
                  final action = status == 'aktif' ? 'checkout' : 'checkin';
                  final success = await context.read<BookingProvider>().updateBookingStatus(booking['id'], action);
                  if (mounted) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text(success ? 'Berhasil diproses' : 'Gagal memproses')),
                    );
                  }
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: status == 'aktif' ? AppColors.primary : AppColors.accent,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  elevation: 0,
                ),
                child: Text(
                  status == 'aktif' ? 'Proses Checkout' : 'Proses Check-in',
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }
}
