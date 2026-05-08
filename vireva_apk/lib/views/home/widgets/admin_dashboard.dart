import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/app_constants.dart';
import '../../../viewmodels/admin_viewmodel.dart';
import '../../../viewmodels/villa_viewmodel.dart';
import 'dashboard_header.dart';
import '../../admin/manage_villa_screen.dart';
import '../../admin/manage_booking_screen.dart';


class AdminDashboard extends ConsumerWidget {
  const AdminDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final adminState = ref.watch(adminViewModelProvider);
    final villaState = ref.watch(villaViewModelProvider);
    final theme = Theme.of(context);

    final stats = adminState.stats;
    final bookedVillas = stats['villas']?['staying']?.toString() ?? '0';
    final upcomingCheckins = stats['bookings']?['upcoming']?.toString() ?? '0';
    final monthlyTotal = stats['bookings']?['total']?.toString() ?? '0';

    return RefreshIndicator(
      onRefresh: () async {
        ref.read(adminViewModelProvider.notifier).fetchBookings();
        ref.read(villaViewModelProvider.notifier).fetchVillas();
      },
      color: AppColors.primary,
      edgeOffset: 100,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          const DashboardHeader(title: 'Panel Kontrol Admin'),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              child: (adminState.isLoading || villaState.isLoading) && adminState.bookings.isEmpty
                ? const Center(child: Padding(
                    padding: EdgeInsets.only(top: 80),
                    child: CircularProgressIndicator(color: AppColors.primary),
                  ))
                : Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (adminState.error != null)
                        Container(
                          margin: const EdgeInsets.only(bottom: 16),
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: Colors.red.shade50,
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(color: Colors.red.shade200),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.error_outline, color: Colors.red),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Text(
                                  adminState.error!,
                                  style: const TextStyle(color: Colors.red, fontSize: 12),
                                ),
                              ),
                            ],
                          ),
                        ),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Ringkasan Bisnis',
                            style: theme.textTheme.titleLarge?.copyWith(
                              fontWeight: FontWeight.w800,
                              fontSize: 18,
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: AppColors.primaryLight,
                              borderRadius: BorderRadius.circular(100),
                            ),
                            child: const Text(
                              'HARI INI',
                              style: TextStyle(
                                color: AppColors.primaryDark,
                                fontSize: 10,
                                fontWeight: FontWeight.bold,
                                letterSpacing: 1,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: AppSpacing.p20),
                      
                      Column(
                        children: [
                          _buildStatCard(theme, 'Sedang Menginap', bookedVillas, Icons.meeting_room_rounded, const Color(0xFF3B82F6)),
                          const SizedBox(height: 16),
                          Row(
                            children: [
                              Expanded(
                                child: _buildStatCard(theme, 'Check-in Terjadwal', upcomingCheckins, Icons.calendar_month_rounded, const Color(0xFFF59E0B)),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: _buildStatCard(theme, 'Reservasi (Bulan Ini)', monthlyTotal, Icons.bar_chart_rounded, AppColors.primary),
                              ),
                            ],
                          ),
                        ],
                      ),
                      
                      const SizedBox(height: AppSpacing.p32),
                      
                      Text(
                        'Aksi Cepat',
                        style: theme.textTheme.titleLarge?.copyWith(
                          fontWeight: FontWeight.w800,
                          fontSize: 18,
                        ),
                      ),
                      const SizedBox(height: AppSpacing.p16),
                      _buildActionTile(context, 'Daftar Unit Villa', 'Kelola inventaris & status unit', Icons.view_quilt_rounded, AppColors.primary, () {
                        Navigator.push(context, MaterialPageRoute(builder: (context) => const ManageVillaScreen()));
                      }),
                      _buildActionTile(context, 'Manajemen Reservasi', 'Verifikasi & pantau jadwal tamu', Icons.event_available_rounded, const Color(0xFF6366F1), () {
                        Navigator.push(context, MaterialPageRoute(builder: (context) => const ManageBookingScreen()));
                      }),
                      
                      const SizedBox(height: AppSpacing.p48),
                    ],
                  ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard(ThemeData theme, String label, String value, IconData icon, Color color) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(AppSpacing.p20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        boxShadow: [
          BoxShadow(
            color: color.withValues(alpha: 0.08),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
        border: Border.all(color: color.withValues(alpha: 0.1), width: 1.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: color, size: 20),
          ),
          const SizedBox(height: 16),
          Text(
            value,
            style: theme.textTheme.displayMedium?.copyWith(
              fontSize: 24,
              color: AppColors.textPrimary,
              fontWeight: FontWeight.w900,
            ),
          ),
          Text(
            label,
            style: theme.textTheme.bodyMedium?.copyWith(
              color: AppColors.textSecondary,
              fontWeight: FontWeight.w600,
              fontSize: 12,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActionTile(BuildContext context, String title, String sub, IconData icon, Color color, VoidCallback onTap) {
    final theme = Theme.of(context);
    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.03),
            blurRadius: 15,
            offset: const Offset(0, 5),
          ),
        ],
        border: Border.all(color: AppColors.border.withValues(alpha: 0.5)),
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: AppSpacing.p20, vertical: 8),
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(16),
          ),
          child: Icon(icon, color: color, size: 24),
        ),
        title: Text(
          title, 
          style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold)
        ),
        subtitle: Text(
          sub, 
          style: theme.textTheme.bodySmall?.copyWith(color: AppColors.textSecondary)
        ),
        trailing: Container(
          padding: const EdgeInsets.all(4),
          decoration: BoxDecoration(
            color: AppColors.surface,
            shape: BoxShape.circle,
            border: Border.all(color: AppColors.border),
          ),
          child: const Icon(Icons.chevron_right_rounded, size: 20, color: AppColors.textMuted),
        ),
        onTap: onTap,
      ),
    );
  }
}
