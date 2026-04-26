import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../providers/admin_provider.dart';

class AdminLaporanScreen extends StatefulWidget {
  const AdminLaporanScreen({super.key});

  @override
  State<AdminLaporanScreen> createState() => _AdminLaporanScreenState();
}

class _AdminLaporanScreenState extends State<AdminLaporanScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      if (mounted) {
        context.read<AdminProvider>().fetchStats();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final adminProvider = context.watch<AdminProvider>();

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
          'Laporan Finansial',
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.primary,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
      ),
      body: adminProvider.isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.accent))
          : RefreshIndicator(
              onRefresh: () => adminProvider.fetchStats(),
              color: AppColors.accent,
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(AppSpacing.p24),
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSummaryCard(adminProvider),
                    const SizedBox(height: 24),
                    Text(
                      'Statistik Bulanan',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary,
                      ),
                    ),
                    const SizedBox(height: 16),
                    Container(
                      width: double.infinity,
                      height: 220,
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(24),
                        border: Border.all(color: AppColors.border),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceAround,
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: (adminProvider.stats?['chart_data'] as List<dynamic>?)?.map((item) {
                              final double revenue = (item['revenue'] as num).toDouble();
                              // Tentukan tinggi bar (maksimal 140px)
                              double maxHeight = 140;
                              double barHeight = revenue > 0 ? (revenue / 10000000) * maxHeight : 5; // Skala 10jt
                              if (barHeight > maxHeight) barHeight = maxHeight;

                              return Column(
                                mainAxisAlignment: MainAxisAlignment.end,
                                children: [
                                  Container(
                                    width: 12,
                                    height: barHeight,
                                    decoration: BoxDecoration(
                                      color: AppColors.accent,
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    item['day'],
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 10,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.grey,
                                    ),
                                  ),
                                ],
                              );
                            }).toList() ??
                            [],
                      ),
                    ),
                    const SizedBox(height: 24),
                    Text(
                      'Rincian Transaksi Terbaru',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary,
                      ),
                    ),
                    const SizedBox(height: 16),
                    // Add more report details if needed
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildSummaryCard(AdminProvider provider) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: AppColors.primary,
        borderRadius: BorderRadius.circular(32),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withOpacity(0.3),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        children: [
          Text(
            'Total Pendapatan (Tahun Ini)',
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white.withOpacity(0.6),
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            provider.stats?['revenue']['formatted'] ?? 'Rp 0',
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white,
              fontSize: 32,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildSimpleStat('Pemesanan', provider.stats?['bookings']['total']?.toString() ?? '0'),
              Container(width: 1, height: 30, color: Colors.white.withOpacity(0.2)),
              _buildSimpleStat('Tamu Selesai', provider.stats?['bookings']['completed']?.toString() ?? '0'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSimpleStat(String label, String value) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white,
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white.withOpacity(0.6),
            fontSize: 10,
          ),
        ),
      ],
    );
  }
}
