import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../providers/auth_provider.dart';
import '../../providers/villa_provider.dart';
import '../../providers/admin_provider.dart';
import 'detail_villa_screen.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../admin/manage_villa_screen.dart';
import '../admin/manage_booking_screen.dart';
import '../admin/admin_laporan_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      if (mounted) {
        final auth = context.read<AuthProvider>();
        if (auth.user?.role == 'user') {
          context.read<VillaProvider>().fetchVillas();
        } else {
          context.read<AdminProvider>().fetchStats();
        }
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final role = auth.user?.role ?? 'user';

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: role == 'user' 
          ? _buildGuestDashboard(auth) 
          : _buildAdminDashboard(auth),
      ),
    );
  }

  // --- GUEST DASHBOARD ---
  Widget _buildGuestDashboard(AuthProvider auth) {
    final villaProvider = context.watch<VillaProvider>();
    return RefreshIndicator(
      onRefresh: () => villaProvider.fetchVillas(),
      color: AppColors.accent,
      child: CustomScrollView(
        slivers: [
          _buildHeader(auth, 'Cari Villa Mewah?'),
          _buildGuestContent(villaProvider),
          const SliverToBoxAdapter(child: SizedBox(height: 100)),
        ],
      ),
    );
  }

  // --- ADMIN DASHBOARD ---
  Widget _buildAdminDashboard(AuthProvider auth) {
    final adminProvider = context.watch<AdminProvider>();
    final isSuper = auth.user?.role == 'superadmin';

    return RefreshIndicator(
      onRefresh: () => adminProvider.fetchStats(),
      color: AppColors.accent,
      child: CustomScrollView(
        slivers: [
          _buildHeader(auth, 'Panel Kontrol Admin'),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              child: adminProvider.isLoading
                  ? const Center(child: Padding(
                      padding: EdgeInsets.only(top: 40),
                      child: CircularProgressIndicator(color: AppColors.accent),
                    ))
                  : Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 8),
                        Text(
                          'Ringkasan Bisnis Hari Ini',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary,
                          ),
                        ),
                        const SizedBox(height: 20),
                        
                        // Stats Grid
                        GridView.count(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          crossAxisCount: 2,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                          childAspectRatio: 1.5,
                          children: [
                            _buildStatCard('Total Villa', adminProvider.stats?['villas']['total']?.toString() ?? '0', Icons.home_rounded),
                            _buildStatCard('Tersedia', adminProvider.stats?['villas']['available']?.toString() ?? '0', Icons.check_circle_rounded),
                            _buildStatCard('Terisi', adminProvider.stats?['villas']['booked']?.toString() ?? '0', Icons.hotel_rounded),
                            _buildStatCard('Pemesanan', adminProvider.stats?['bookings']['total']?.toString() ?? '0', Icons.assignment_rounded),
                          ],
                        ),
                        
                        const SizedBox(height: 24),
                        
                        // Superadmin Only Revenue Card
                        if (isSuper) ...[
                          Container(
                            width: double.infinity,
                            padding: const EdgeInsets.all(24),
                            decoration: BoxDecoration(
                              gradient: const LinearGradient(
                                colors: [AppColors.primary, Color(0xFF1E293B)],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ),
                              borderRadius: BorderRadius.circular(24),
                              boxShadow: [
                                BoxShadow(
                                  color: AppColors.primary.withOpacity(0.3),
                                  blurRadius: 20,
                                  offset: const Offset(0, 10),
                                ),
                              ],
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    const Icon(Icons.account_balance_wallet_rounded, color: Colors.white, size: 20),
                                    const SizedBox(width: 8),
                                    Text(
                                      'Total Pendapatan',
                                      style: GoogleFonts.plusJakartaSans(
                                        color: Colors.white.withOpacity(0.7),
                                        fontSize: 14,
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  adminProvider.stats?['revenue']['formatted'] ?? 'Rp 0',
                                  style: GoogleFonts.plusJakartaSans(
                                    color: Colors.white,
                                    fontSize: 28,
                                    fontWeight: FontWeight.w800,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  '*Khusus Superadmin',
                                  style: GoogleFonts.plusJakartaSans(
                                    color: Colors.white.withOpacity(0.4),
                                    fontSize: 10,
                                    fontStyle: FontStyle.italic,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(height: 24),
                        ],
                        
                        // Quick Actions
                        Text(
                          'Aksi Cepat',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary,
                          ),
                        ),
                        const SizedBox(height: 16),
                        _buildAdminActionTile('Daftar Villa', 'Kelola ketersediaan & foto', Icons.list_alt_rounded, onTap: () {
                          Navigator.push(context, MaterialPageRoute(builder: (context) => const ManageVillaScreen()));
                        }),
                        _buildAdminActionTile('Daftar Pemesanan', 'Lihat detail reservasi tamu', Icons.calendar_today_rounded, onTap: () {
                          Navigator.push(context, MaterialPageRoute(builder: (context) => const ManageBookingScreen()));
                        }),
                        if (isSuper)
                          _buildAdminActionTile('Laporan Finansial', 'Lihat rincian pemasukan', Icons.bar_chart_rounded, onTap: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => const AdminLaporanScreen()));
                          }),
                        
                        const SizedBox(height: 100),
                      ],
                    ),
            ),
          ),
        ],
      ),
    );
  }

  // --- COMMON COMPONENTS ---
  Widget _buildHeader(AuthProvider auth, String title) {
    return SliverToBoxAdapter(
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.p24),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Halo, ${auth.user?.name ?? 'User'}!',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    color: AppColors.textSecondary,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                Text(
                  title,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    color: AppColors.primary,
                    letterSpacing: -0.5,
                  ),
                ),
              ],
            ),
            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: AppColors.border),
              ),
              child: IconButton(
                icon: const Icon(Icons.logout_rounded, color: AppColors.primary),
                onPressed: () => auth.logout(),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildGuestContent(VillaProvider villaProvider) {
    if (villaProvider.isLoading) {
      return const SliverFillRemaining(
        child: Center(child: CircularProgressIndicator(color: AppColors.accent)),
      );
    }
    if (villaProvider.error != null) {
      return SliverFillRemaining(
        child: Center(child: Text(villaProvider.error!, textAlign: TextAlign.center)),
      );
    }
    return SliverPadding(
      padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (context, index) => _buildVillaCard(villaProvider.villas[index]),
          childCount: villaProvider.villas.length,
        ),
      ),
    );
  }

  Widget _buildStatCard(String label, String value, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: AppColors.accent, size: 20),
          const Spacer(),
          Text(
            value,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: AppColors.primary,
            ),
          ),
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 12,
              color: AppColors.textSecondary,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAdminActionTile(String title, String sub, IconData icon, {bool isDisabled = false, VoidCallback? onTap}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: isDisabled ? Colors.grey[50] : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.border),
      ),
      child: ListTile(
        leading: Icon(icon, color: isDisabled ? Colors.grey : AppColors.primary),
        title: Text(
          title,
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.bold,
            color: isDisabled ? Colors.grey : AppColors.primary,
          ),
        ),
        subtitle: Text(
          sub,
          style: GoogleFonts.plusJakartaSans(fontSize: 12),
        ),
        trailing: const Icon(Icons.chevron_right_rounded, size: 20),
        enabled: !isDisabled,
        onTap: onTap,
      ),
    );
  }

  Widget _buildVillaCard(dynamic villa) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => DetailVillaScreen(villa: villa)),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          border: Border.all(color: AppColors.border),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
              child: CachedNetworkImage(
                imageUrl: villa.imageUrl ?? '',
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
                errorWidget: (context, url, error) => Container(color: Colors.grey[100]),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(villa.nama, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(villa.formattedHarga, style: const TextStyle(color: AppColors.accent, fontWeight: FontWeight.bold)),
                      Row(
                        children: [
                          Icon(Icons.bed_rounded, size: 16, color: Colors.grey[600]),
                          const SizedBox(width: 4),
                          Text('${villa.bedroom}'),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
