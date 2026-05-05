import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/app_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../providers/admin_provider.dart';
import 'dashboard_header.dart';
import '../../admin/manage_villa_screen.dart';
import '../../admin/manage_booking_screen.dart';
import '../../admin/admin_laporan_screen.dart';

class AdminDashboard extends ConsumerWidget {
  const AdminDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final adminState = ref.watch(adminProvider);
    final user = ref.watch(authProvider).user;
    final isSuper = user?.role == 'superadmin';
    final theme = Theme.of(context);

    return RefreshIndicator(
      onRefresh: () => ref.read(adminProvider.notifier).fetchStats(),
      color: AppColors.primary,
      edgeOffset: 100,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          const DashboardHeader(title: 'Panel Kontrol Admin'),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              child: adminState.isLoading
                ? const Center(child: Padding(
                    padding: EdgeInsets.only(top: 80),
                    child: CircularProgressIndicator(color: AppColors.primary),
                  ))
                : Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
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
                          _buildStatCard(theme, 'Tamu Menginap', adminState.stats?['villas']['booked']?.toString() ?? '0', Icons.meeting_room_rounded, const Color(0xFF3B82F6)),
                          const SizedBox(height: 16),
                          Row(
                            children: [
                              Expanded(
                                child: _buildStatCard(theme, 'Daftar Villa', adminState.stats?['villas']['total']?.toString() ?? '0', Icons.apartment_rounded, AppColors.secondary),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: _buildStatCard(theme, 'Reservasi Terjadwal', adminState.stats?['bookings']['pending']?.toString() ?? '0', Icons.calendar_month_rounded, AppColors.primary),
                              ),
                            ],
                          ),
                        ],
                      ),
                      
                      const SizedBox(height: AppSpacing.p24),
                      
                      if (isSuper) ...[
                        _buildRevenueCard(theme, adminState.stats?['revenue']['formatted'] ?? 'Rp 0'),
                        const SizedBox(height: AppSpacing.p32),
                      ],
                      
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
                      if (isSuper)
                        _buildActionTile(context, 'Laporan Finansial', 'Analisis arus kas & pendapatan', Icons.analytics_rounded, const Color(0xFFEC4899), () {
                          Navigator.push(context, MaterialPageRoute(builder: (context) => const AdminLaporanScreen()));
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

  Widget _buildRevenueCard(ThemeData theme, String amount) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(AppSpacing.p24),
      decoration: BoxDecoration(
        gradient: AppColors.darkGradient,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        boxShadow: [
          BoxShadow(
            color: AppColors.secondary.withValues(alpha: 0.3),
            blurRadius: 25,
            offset: const Offset(0, 12),
          ),
        ],
      ),
      child: Stack(
        children: [
          Positioned(
            right: -20,
            top: -20,
            child: Icon(Icons.account_balance_wallet_rounded, color: Colors.white.withValues(alpha: 0.05), size: 120),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'TOTAL PENDAPATAN',
                style: theme.textTheme.bodySmall?.copyWith(
                  color: Colors.white.withValues(alpha: 0.6),
                  fontWeight: FontWeight.w800,
                  letterSpacing: 2,
                ),
              ),
              const SizedBox(height: 12),
              Text(
                amount,
                style: theme.textTheme.displayMedium?.copyWith(
                  color: Colors.white, 
                  fontSize: 32,
                  fontWeight: FontWeight.w900,
                ),
              ),
              const SizedBox(height: 16),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  'Periode Berjalan',
                  style: theme.textTheme.bodySmall?.copyWith(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold),
                ),
              ),
            ],
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
