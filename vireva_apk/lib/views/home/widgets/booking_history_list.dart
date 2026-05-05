import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import '../../../core/app_constants.dart';
import '../../../providers/booking_provider.dart';
import 'dashboard_header.dart';

class BookingHistoryList extends ConsumerWidget {
  const BookingHistoryList({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final bookingState = ref.watch(bookingProvider);
    final theme = Theme.of(context);

    return RefreshIndicator(
      onRefresh: () => ref.read(bookingProvider.notifier).fetchBookings(),
      color: AppColors.primary,
      child: CustomScrollView(
        slivers: [
          const DashboardHeader(title: 'Riwayat Pesanan'),
          
          if (bookingState.isLoading && bookingState.bookings.isEmpty)
            const SliverFillRemaining(
              child: Center(child: CircularProgressIndicator(color: AppColors.primary)),
            )
          else if (bookingState.bookings.isEmpty)
            SliverFillRemaining(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.history_rounded, size: 64, color: AppColors.accent),
                    const SizedBox(height: 16),
                    Text(
                      'Belum ada riwayat pemesanan',
                      style: theme.textTheme.bodyLarge?.copyWith(color: AppColors.textSecondary),
                    ),
                  ],
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final booking = bookingState.bookings[index];
                    return _BookingHistoryCard(booking: booking);
                  },
                  childCount: bookingState.bookings.length,
                ),
              ),
            ),
            
          const SliverToBoxAdapter(child: SizedBox(height: AppSpacing.p48)),
        ],
      ),
    );
  }
}

class _BookingHistoryCard extends ConsumerWidget {
  final dynamic booking;
  const _BookingHistoryCard({required this.booking});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final theme = Theme.of(context);
    final status = booking['status_pembayaran'] ?? 'pending';
    final bookingId = booking['id'];
    
    Color statusColor;
    String statusText;
    switch (status) {
      case 'settlement': 
        statusColor = AppColors.success; 
        statusText = 'LUNAS';
        break;
      case 'pending': 
        statusColor = Colors.orange; 
        statusText = 'MENUNGGU PEMBAYARAN';
        break;
      case 'expire':
      case 'cancel': 
        statusColor = AppColors.error; 
        statusText = 'DIBATALKAN';
        break;
      default: 
        statusColor = AppColors.textSecondary;
        statusText = status.toString().toUpperCase();
    }

    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      padding: const EdgeInsets.all(AppSpacing.p20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  booking['villa']['nama_villa'] ?? 'Villa',
                  style: theme.textTheme.titleMedium,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: statusColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  statusText,
                  style: GoogleFonts.spaceGrotesk(
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                    color: statusColor,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              const Icon(Icons.calendar_today_rounded, size: 14, color: AppColors.textSecondary),
              const SizedBox(width: 8),
              Text(
                '${booking['tanggal_checkin']} - ${booking['tanggal_checkout']}',
                style: theme.textTheme.bodySmall,
              ),
            ],
          ),
          const Divider(height: 24, color: AppColors.border),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Total Biaya',
                style: theme.textTheme.bodySmall,
              ),
              Text(
                'Rp ${booking['total_biaya']}',
                style: theme.textTheme.bodyLarge?.copyWith(fontWeight: FontWeight.bold, color: AppColors.primary),
              ),
            ],
          ),
          
          if (status == 'pending') ...[
            const SizedBox(height: AppSpacing.p16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: () async {
                  final picker = ImagePicker();
                  final XFile? image = await picker.pickImage(source: ImageSource.gallery);
                  
                  if (image != null) {
                    final success = await ref.read(bookingProvider.notifier).uploadBukti(bookingId, image.path);
                    if (context.mounted) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(success ? 'Bukti berhasil diupload!' : 'Gagal upload bukti'),
                          backgroundColor: success ? AppColors.success : AppColors.error,
                        ),
                      );
                    }
                  }
                },
                icon: const Icon(Icons.upload_file_rounded, size: 18),
                label: const Text('UPLOAD BUKTI BAYAR'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.accent,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              '*Silakan transfer ke Rekening BCA: 123456789 a/n Vireva Villa',
              style: TextStyle(fontSize: 10, color: AppColors.textSecondary, fontStyle: FontStyle.italic),
            ),
          ],
          
          if (booking['bukti_pembayaran'] != null && status == 'pending') ...[
             const SizedBox(height: 8),
             Row(
               children: [
                 const Icon(Icons.hourglass_bottom_rounded, size: 14, color: Colors.orange),
                 const SizedBox(width: 4),
                 Text(
                   'Menunggu verifikasi admin',
                   style: theme.textTheme.bodySmall?.copyWith(color: Colors.orange, fontWeight: FontWeight.bold),
                 ),
               ],
             ),
          ],
        ],
      ),
    );
  }
}
