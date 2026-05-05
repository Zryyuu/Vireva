import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/app_constants.dart';
import '../../providers/booking_provider.dart';
import 'add_manual_booking_screen.dart';

class ManageBookingScreen extends ConsumerStatefulWidget {
  const ManageBookingScreen({super.key});

  @override
  ConsumerState<ManageBookingScreen> createState() => _ManageBookingScreenState();
}

class _ManageBookingScreenState extends ConsumerState<ManageBookingScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(bookingProvider.notifier).fetchBookings(isAdmin: true);
    });
  }

  @override
  Widget build(BuildContext context) {
    final bookingState = ref.watch(bookingProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('DAFTAR RESERVASI'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add_circle_outline_rounded),
            onPressed: () => Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const AddManualBookingScreen()),
            ),
          ),
        ],
      ),
      body: bookingState.isLoading && bookingState.bookings.isEmpty
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : RefreshIndicator(
              onRefresh: () => ref.read(bookingProvider.notifier).fetchBookings(isAdmin: true),
              color: AppColors.primary,
              child: ListView.builder(
                padding: const EdgeInsets.all(AppSpacing.p24),
                itemCount: bookingState.bookings.length,
                itemBuilder: (context, index) {
                  final booking = bookingState.bookings[index];
                  return _buildBookingCard(booking, theme);
                },
              ),
            ),
    );
  }

  Widget _buildBookingCard(dynamic booking, ThemeData theme) {
    final status = booking['status_pemesanan'] ?? 'menunggu';
    final paymentStatus = booking['status_pembayaran'] ?? 'pending';
    final proof = booking['bukti_pembayaran'];
    
    Color statusColor;
    String statusText;

    switch (status) {
      case 'aktif':
        statusColor = Colors.blue;
        statusText = 'AKTIF';
        break;
      case 'selesai':
        statusColor = AppColors.success;
        statusText = 'SELESAI';
        break;
      case 'batal':
        statusColor = AppColors.error;
        statusText = 'BATAL';
        break;
      default:
        statusColor = Colors.orange;
        statusText = 'MENUNGGU';
    }

    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      padding: const EdgeInsets.all(AppSpacing.p20),
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
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
                  borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
                ),
                child: Text(
                  statusText,
                  style: theme.textTheme.bodySmall?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: statusColor,
                    fontSize: 10,
                  ),
                ),
              ),
              Text(
                '#${booking['id']}',
                style: theme.textTheme.bodySmall,
              ),
            ],
          ),
          const SizedBox(height: AppSpacing.p16),
          Text(
            booking['tamu']['nama_tamu'] ?? 'Tamu',
            style: theme.textTheme.titleMedium,
          ),
          Text(
            booking['villa']['nama_villa'] ?? 'Villa',
            style: theme.textTheme.bodyMedium,
          ),
          const Padding(
            padding: EdgeInsets.symmetric(vertical: AppSpacing.p12),
            child: Divider(height: 1, color: AppColors.border),
          ),
          Row(
            children: [
              const Icon(Icons.calendar_today_rounded, size: 14, color: AppColors.textSecondary),
              const SizedBox(width: 8),
              Text(
                '${booking['tanggal_checkin']} - ${booking['tanggal_checkout']}',
                style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.bold),
              ),
            ],
          ),
          
          if (paymentStatus == 'pending' && proof != null) ...[
            const SizedBox(height: AppSpacing.p20),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.orange.withOpacity(0.1),
                borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
                border: Border.all(color: Colors.orange.withOpacity(0.3)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'BUKTI PEMBAYARAN DIUPLOAD',
                    style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.orange),
                  ),
                  const SizedBox(height: 12),
                  GestureDetector(
                    onTap: () => _showImagePreview(booking['bukti_url'] ?? ''),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: CachedNetworkImage(
                        imageUrl: booking['bukti_url'] ?? '',
                        height: 100,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        placeholder: (context, url) => Container(color: AppColors.surface),
                        errorWidget: (context, url, error) => const Icon(Icons.image_not_supported),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton(
                          onPressed: () => _verifyPayment(booking['id'], 'settlement'),
                          style: ElevatedButton.styleFrom(backgroundColor: AppColors.success),
                          child: const Text('TERIMA'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: OutlinedButton(
                          onPressed: () => _verifyPayment(booking['id'], 'cancel'),
                          style: OutlinedButton.styleFrom(foregroundColor: AppColors.error),
                          child: const Text('TOLAK'),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],

          if (status == 'aktif' || (status == 'menunggu' && paymentStatus == 'settlement')) ...[
            const SizedBox(height: AppSpacing.p20),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: () => _handleAction(booking['id'], 'cancel', 'Membatalkan reservasi ini?'),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: AppColors.error,
                      side: const BorderSide(color: AppColors.error),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radiusSm)),
                    ),
                    child: const Text('BATALKAN'),
                  ),
                ),
                const SizedBox(width: AppSpacing.p12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () {
                      final action = (status == 'menunggu') ? 'checkin' : 'checkout';
                      final actionText = (status == 'menunggu') ? 'Konfirmasi Check-in?' : 'Konfirmasi Check-out?';
                      _handleAction(booking['id'], action, actionText);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: (status == 'aktif') ? AppColors.primary : AppColors.success,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radiusSm)),
                    ),
                    child: Text(
                      (status == 'aktif') ? 'CHECK-OUT' : 'CHECK-IN',
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  void _showImagePreview(String url) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            CachedNetworkImage(imageUrl: url),
            TextButton(onPressed: () => Navigator.pop(context), child: const Text('TUTUP')),
          ],
        ),
      ),
    );
  }

  Future<void> _verifyPayment(int id, String status) async {
    final success = await ref.read(bookingProvider.notifier).verifyBooking(id, status);
    if (context.mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(success ? 'Berhasil diverifikasi' : 'Gagal verifikasi')),
      );
    }
  }

  void _handleAction(int id, String action, String message) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
        title: const Text('Konfirmasi'),
        content: Text(message),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('TIDAK')),
          TextButton(
            onPressed: () => Navigator.pop(ctx, true), 
            child: const Text('YA', style: TextStyle(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );

    if (confirm == true && mounted) {
      final success = await ref.read(bookingProvider.notifier).updateBookingStatus(id, action);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(success ? 'Berhasil diproses' : 'Gagal memproses'),
            backgroundColor: success ? AppColors.success : AppColors.error,
          ),
        );
      }
    }
  }
}
