import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/app_constants.dart';
import '../../providers/admin_provider.dart';

class AdminLaporanScreen extends ConsumerStatefulWidget {
  const AdminLaporanScreen({super.key});

  @override
  ConsumerState<AdminLaporanScreen> createState() => _AdminLaporanScreenState();
}

class _AdminLaporanScreenState extends ConsumerState<AdminLaporanScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(adminProvider.notifier).fetchStats();
    });
  }

  @override
  Widget build(BuildContext context) {
    final adminState = ref.watch(adminProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Laporan Finansial'),
      ),
      body: adminState.isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : RefreshIndicator(
              onRefresh: () => ref.read(adminProvider.notifier).fetchStats(),
              color: AppColors.primary,
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(AppSpacing.p24),
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSummaryCard(adminState, theme),
                    const SizedBox(height: AppSpacing.p32),
                    Text(
                      'Statistik Bulanan',
                      style: theme.textTheme.titleMedium,
                    ),
                    const SizedBox(height: AppSpacing.p16),
                    _buildChart(adminState, theme),
                    const SizedBox(height: AppSpacing.p32),
                    Text(
                      'Rincian Transaksi Terbaru',
                      style: theme.textTheme.titleMedium,
                    ),
                    const SizedBox(height: AppSpacing.p16),
                    Center(
                      child: Text(
                        'Belum ada rincian tambahan.',
                        style: theme.textTheme.bodyMedium,
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildSummaryCard(AdminState state, ThemeData theme) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(AppSpacing.p32),
      decoration: BoxDecoration(
        color: AppColors.primary,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
      ),
      child: Column(
        children: [
          Text(
            'TOTAL PENDAPATAN',
            style: theme.textTheme.bodySmall?.copyWith(
              color: Colors.white.withOpacity(0.6),
              letterSpacing: 1.5,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: AppSpacing.p12),
          Text(
            state.stats?['revenue']['formatted'] ?? 'Rp 0',
            style: theme.textTheme.displayMedium?.copyWith(
              color: Colors.white,
              fontSize: 36,
            ),
          ),
          const SizedBox(height: AppSpacing.p32),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildSimpleStat(theme, 'Pemesanan', state.stats?['bookings']['total']?.toString() ?? '0'),
              Container(width: 1, height: 30, color: Colors.white.withOpacity(0.1)),
              _buildSimpleStat(theme, 'Selesai', state.stats?['bookings']['completed']?.toString() ?? '0'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSimpleStat(ThemeData theme, String label, String value) {
    return Column(
      children: [
        Text(
          value,
          style: theme.textTheme.titleLarge?.copyWith(color: Colors.white),
        ),
        Text(
          label,
          style: theme.textTheme.bodySmall?.copyWith(color: Colors.white.withOpacity(0.6)),
        ),
      ],
    );
  }

  Widget _buildChart(AdminState state, ThemeData theme) {
    return Container(
      width: double.infinity,
      height: 220,
      padding: const EdgeInsets.all(AppSpacing.p20),
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: (state.stats?['chart_data'] as List<dynamic>?)?.map((item) {
              final double revenue = (item['revenue'] as num).toDouble();
              double maxHeight = 140;
              double barHeight = revenue > 0 ? (revenue / 10000000) * maxHeight : 5;
              if (barHeight > maxHeight) barHeight = maxHeight;

              return Column(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  Container(
                    width: 16,
                    height: barHeight,
                    decoration: BoxDecoration(
                      color: AppColors.primary,
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    item['day'],
                    style: theme.textTheme.bodySmall?.copyWith(fontSize: 10),
                  ),
                ],
              );
            }).toList() ?? [],
      ),
    );
  }
}
