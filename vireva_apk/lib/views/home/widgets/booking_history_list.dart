import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/app_constants.dart';
import '../../../viewmodels/booking_viewmodel.dart';
import 'dashboard_header.dart';
import '../../booking/booking_detail_screen.dart';

class BookingHistoryList extends ConsumerWidget {
  const BookingHistoryList({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final bookingState = ref.watch(bookingViewModelProvider);
    final theme = Theme.of(context);

    return RefreshIndicator(
      onRefresh: () => ref.read(bookingViewModelProvider.notifier).fetchBookings(),
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
    final status = booking.statusPemesanan;
    final bookingId = booking.id;
    
    Color statusColor;
    String statusText;
    switch (status) {
      case 'aktif': 
        statusColor = Colors.blue; 
        statusText = 'SEDANG MENGINAP';
        break;
      case 'selesai': 
        statusColor = AppColors.textSecondary; 
        statusText = 'SELESAI';
        break;
      case 'batal': 
        statusColor = AppColors.error; 
        statusText = 'BATAL';
        break;
      case 'menunggu':
      default: 
        if (booking.statusPembayaran == 'pending') {
          statusColor = AppColors.textMuted;
          statusText = 'MENUNGGU KONFIRMASI';
        } else {
          statusColor = AppColors.primary;
          statusText = 'TERKONFIRMASI';
        }
    }

    final checkinDate = DateTime.parse(booking.tanggalCheckin);
    final checkoutDate = DateTime.parse(booking.tanggalCheckout);
    final duration = checkoutDate.difference(checkinDate).inDays;

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
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('NO. RESERVASI', style: TextStyle(fontSize: 8, color: AppColors.textSecondary, fontWeight: FontWeight.bold)),
                  Text(
                    '#VRV-${bookingId.toString().padLeft(5, '0')}',
                    style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 14),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: statusColor.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(100),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Container(width: 4, height: 4, decoration: BoxDecoration(color: statusColor, shape: BoxShape.circle)),
                    const SizedBox(width: 4),
                    Text(
                      statusText,
                      style: TextStyle(
                        fontSize: 8,
                        fontWeight: FontWeight.w900,
                        color: statusColor,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: AppSpacing.p20),
          Text(
            booking.villa?.nama ?? 'Villa',
            style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w900),
          ),
          const SizedBox(height: 4),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(4),
            ),
            child: Text(
              booking.villa?.tipe?.toUpperCase() ?? 'VILLA',
              style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold, color: AppColors.textSecondary),
            ),
          ),
          const SizedBox(height: AppSpacing.p20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('CHECK-IN', style: TextStyle(fontSize: 8, color: AppColors.textSecondary, fontWeight: FontWeight.bold)),
                  Text(_formatDateShort(booking.tanggalCheckin), style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w900)),
                ],
              ),
              const Icon(Icons.arrow_forward_rounded, size: 14, color: AppColors.textSecondary),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  const Text('CHECK-OUT', style: TextStyle(fontSize: 8, color: AppColors.textSecondary, fontWeight: FontWeight.bold)),
                  Text(_formatDateShort(booking.tanggalCheckout), style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w900)),
                ],
              ),
            ],
          ),
          const SizedBox(height: 12),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 4),
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.05),
              borderRadius: BorderRadius.circular(100),
            ),
            alignment: Alignment.center,
            child: Text(
              '$duration Malam Menginap',
              style: const TextStyle(color: AppColors.primary, fontSize: 8, fontWeight: FontWeight.w900),
            ),
          ),
          const SizedBox(height: 20),
          const Divider(height: 1, color: AppColors.border),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('TOTAL TAGIHAN', style: TextStyle(fontSize: 8, color: AppColors.textSecondary, fontWeight: FontWeight.bold)),
                  Text(
                    booking.formattedTotalBiaya,
                    style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 14),
                  ),
                ],
              ),
              Row(
                children: [
                  GestureDetector(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => BookingDetailScreen(booking: booking),
                        ),
                      );
                    },
                    child: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: const Color(0xFF1E2432),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Icon(Icons.visibility_rounded, size: 16, color: Colors.white),
                    ),
                  ),
                  if (status == 'menunggu' || status == 'aktif') ...[
                    const SizedBox(width: 8),
                    GestureDetector(
                      onTap: () {
                        // Action to cancel booking
                      },
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: AppColors.error.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: const Icon(Icons.close_rounded, size: 16, color: AppColors.error),
                      ),
                    ),
                  ],
                ],
              )
            ],
          ),
        ],
      ),
    );
  }

  String _getMonthShort(int month) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return months[month - 1];
  }

  String _formatDateShort(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return '${date.day.toString().padLeft(2, '0')} ${_getMonthShort(date.month)} ${date.year}';
    } catch (_) {
      return dateStr;
    }
  }
}
