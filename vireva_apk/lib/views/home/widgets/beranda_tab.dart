import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/app_constants.dart';
import '../../../viewmodels/auth_viewmodel.dart';
import '../../../viewmodels/booking_viewmodel.dart';
// Booking detail screen import removed since it does not exist yet

class BerandaTab extends ConsumerWidget {
  const BerandaTab({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final auth = ref.watch(authViewModelProvider);
    final bookingState = ref.watch(bookingViewModelProvider);
    final theme = Theme.of(context);

    final totalReservasi = bookingState.bookings.length;
    final bookingAktif = bookingState.bookings.where((b) => b.statusPemesanan == 'aktif' || b.statusPemesanan == 'menunggu').length;
    final selesaiMenginap = bookingState.bookings.where((b) => b.statusPemesanan == 'selesai').length;

    // Ambil maksimal 2 reservasi terbaru
    final recentBookings = bookingState.bookings.take(2).toList();

    return RefreshIndicator(
      onRefresh: () => ref.read(bookingViewModelProvider.notifier).fetchBookings(),
      color: AppColors.primary,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
        slivers: [
          SliverPadding(
            padding: const EdgeInsets.all(AppSpacing.p24),
            sliver: SliverList(
              delegate: SliverChildListDelegate([
                // Header Welcome
                Container(
                  padding: const EdgeInsets.all(AppSpacing.p24),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9), // Light green like in web
                    borderRadius: BorderRadius.circular(AppSpacing.radius),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'PORTAL MEMBER',
                        style: TextStyle(
                          fontSize: 10,
                          fontWeight: FontWeight.w900, // Force bold
                          color: AppColors.primary,
                          letterSpacing: 1,
                        ),
                      ),
                      const SizedBox(height: AppSpacing.p8),
                      RichText(
                        text: TextSpan(
                          style: theme.textTheme.headlineSmall?.copyWith(
                            fontWeight: FontWeight.w900,
                            color: AppColors.textPrimary,
                          ),
                          children: [
                            const TextSpan(text: 'Selamat Datang, '),
                            TextSpan(
                              text: auth.user?.name ?? 'Tamu',
                              style: const TextStyle(color: AppColors.primary),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: AppSpacing.p12),
                      const Text(
                        'Temukan kenyamanan eksklusif dan rencanakan liburan impian Anda bersama kami. Kelola privasi dan reservasi villa Anda dengan mudah dari dasbor ini.',
                        style: TextStyle(
                          fontSize: 12,
                          color: AppColors.textSecondary,
                          height: 1.5,
                        ),
                      ),
                    ],
                  ),
                ),
                
                const SizedBox(height: AppSpacing.p24),

                // Alert Profil Belum Lengkap
                Container(
                  padding: const EdgeInsets.all(AppSpacing.p20),
                  decoration: BoxDecoration(
                    color: Colors.orange.withValues(alpha: 0.05),
                    borderRadius: BorderRadius.circular(AppSpacing.radius),
                    border: Border.all(color: Colors.orange.withValues(alpha: 0.3)),
                  ),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Colors.orange.withValues(alpha: 0.1),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(Icons.person_add_alt_1_rounded, color: Colors.orange, size: 20),
                      ),
                      const SizedBox(width: AppSpacing.p16),
                      const Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Profil Belum Lengkap', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                            SizedBox(height: 4),
                            Text(
                              'Mohon lengkapi No. WhatsApp, KTP, dan Alamat Anda untuk kemudahan proses reservasi villa.',
                              style: TextStyle(fontSize: 11, color: AppColors.textSecondary),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(width: AppSpacing.p12),
                      ElevatedButton(
                        onPressed: () {
                          // Navigasi ke profil belum tersedia
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.orange,
                          foregroundColor: Colors.white,
                          elevation: 0,
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                        ),
                        child: const Text('Lengkapi\nSekarang', textAlign: TextAlign.center, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: AppSpacing.p24),

                // Stats
                Row(
                  children: [
                    Expanded(child: _buildStatCard('TOTAL RESERVASI', totalReservasi.toString())),
                    const SizedBox(width: AppSpacing.p12),
                    Expanded(child: _buildStatCard('BOOKING AKTIF', bookingAktif.toString(), valueColor: AppColors.primary)),
                    const SizedBox(width: AppSpacing.p12),
                    Expanded(child: _buildStatCard('SELESAI MENGINAP', selesaiMenginap.toString())),
                  ],
                ),

                const SizedBox(height: AppSpacing.p32),

                // Riwayat Perjalanan Anda
                Text(
                  'Riwayat Perjalanan Anda',
                  style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: AppSpacing.p16),
                
                if (bookingState.isLoading && recentBookings.isEmpty)
                  const Center(child: CircularProgressIndicator(color: AppColors.primary))
                else if (recentBookings.isEmpty)
                  Container(
                    padding: const EdgeInsets.all(AppSpacing.p24),
                    alignment: Alignment.center,
                    decoration: BoxDecoration(
                      color: AppColors.surface,
                      borderRadius: BorderRadius.circular(AppSpacing.radius),
                      border: Border.all(color: AppColors.border),
                    ),
                    child: const Text('Belum ada riwayat perjalanan.', style: TextStyle(color: AppColors.textSecondary)),
                  )
                else
                  Column(
                    children: recentBookings.map((booking) {
                      return _buildRecentBookingCard(context, booking);
                    }).toList(),
                  ),
              ]),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard(String title, String value, {Color? valueColor}) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.p16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
          const SizedBox(height: AppSpacing.p8),
          Text(
            value, 
            style: TextStyle(
              fontSize: 20, 
              fontWeight: FontWeight.w900, 
              color: valueColor ?? AppColors.textPrimary
            )
          ),
        ],
      ),
    );
  }

  Widget _buildRecentBookingCard(BuildContext context, dynamic booking) {
    Color statusColor;
    String statusText;

    switch (booking.statusPemesanan) {
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
      default:
        statusColor = AppColors.primary;
        statusText = 'TERKONFIRMASI';
    }

    final checkinDate = DateTime.parse(booking.tanggalCheckin);
    final monthShort = _getMonthShort(checkinDate.month);
    final dayStr = checkinDate.day.toString().padLeft(2, '0');

    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      padding: const EdgeInsets.all(AppSpacing.p16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Date box
          Container(
            width: 50,
            padding: const EdgeInsets.symmetric(vertical: 8),
            decoration: const BoxDecoration(
              border: Border(right: BorderSide(color: AppColors.border)),
            ),
            child: Column(
              children: [
                Text(monthShort.toUpperCase(), style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
                Text(dayStr, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: AppColors.primary)),
              ],
            ),
          ),
          const SizedBox(width: AppSpacing.p16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  booking.villa?.nama ?? 'Villa',
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
                const SizedBox(height: 4),
                Text(
                  '1 Malam • ${_formatDateShort(booking.tanggalCheckin)} - ${_formatDateShort(booking.tanggalCheckout)}',
                  style: const TextStyle(fontSize: 10, color: AppColors.textSecondary),
                ),
                const SizedBox(height: 8),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(100),
                    border: Border.all(color: statusColor.withValues(alpha: 0.5)),
                  ),
                  child: Text(
                    statusText,
                    style: TextStyle(color: statusColor, fontSize: 8, fontWeight: FontWeight.w900, letterSpacing: 0.5),
                  ),
                ),
                const SizedBox(height: 16),
                const Divider(height: 1, color: AppColors.border),
                const SizedBox(height: 12),
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
                          style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 12),
                        ),
                      ],
                    ),
                    TextButton(
                      onPressed: () {
                        // Navigate to detail
                        // Navigator.push(
                        //  context,
                          // MaterialPageRoute(builder: (context) => BookingDetailScreen(booking: booking)),
                        // );
                      },
                      style: TextButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      child: const Text('DETAIL RESERVASI', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w900, color: AppColors.textPrimary)),
                    )
                  ],
                ),
              ],
            ),
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
