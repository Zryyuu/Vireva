import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/app_constants.dart';
import '../../../providers/villa_provider.dart';
import '../detail_villa_screen.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'dashboard_header.dart';

class GuestDashboard extends ConsumerWidget {
  const GuestDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final villaState = ref.watch(villaProvider);
    final theme = Theme.of(context);
    
    return RefreshIndicator(
      onRefresh: () => ref.read(villaProvider.notifier).fetchVillas(),
      color: AppColors.primary,
      edgeOffset: 100,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          const DashboardHeader(title: 'Cari Villa Mewah?'),
          
          if (villaState.isLoading)
            const SliverFillRemaining(
              child: Center(child: CircularProgressIndicator(color: AppColors.primary)),
            )
          else if (villaState.error != null)
            SliverFillRemaining(
              child: Center(child: Text(villaState.error!)),
            )
          else ...[
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              sliver: SliverToBoxAdapter(
                child: Text(
                  'Koleksi Eksklusif',
                  style: theme.textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.w800,
                    fontSize: 20,
                  ),
                ),
              ),
            ),
            const SliverToBoxAdapter(child: SizedBox(height: 16)),
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: AppSpacing.p24),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final villa = villaState.villas[index];
                    return _VillaCard(villa: villa);
                  },
                  childCount: villaState.villas.length,
                ),
              ),
            ),
          ],
            
          const SliverToBoxAdapter(child: SizedBox(height: AppSpacing.p48)),
        ],
      ),
    );
  }
}

class _VillaCard extends StatelessWidget {
  final dynamic villa;
  const _VillaCard({required this.villa});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => DetailVillaScreen(villa: villa)),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: AppSpacing.p24),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(AppSpacing.radius),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
          border: Border.all(color: AppColors.border.withValues(alpha: 0.5)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Stack(
              children: [
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(AppSpacing.radius)),
                  child: CachedNetworkImage(
                    imageUrl: villa.imageUrl ?? '',
                    height: 240,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(color: AppColors.surface, child: const Center(child: CircularProgressIndicator())),
                    errorWidget: (context, url, error) => Container(
                      color: AppColors.surface, 
                      child: const Icon(Icons.broken_image_rounded, size: 48, color: AppColors.textMuted)
                    ),
                  ),
                ),
                Positioned(
                  top: 16,
                  right: 16,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.9),
                      borderRadius: BorderRadius.circular(100),
                      boxShadow: [
                        BoxShadow(color: Colors.black.withValues(alpha: 0.1), blurRadius: 4),
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star_rounded, color: Colors.orange, size: 16),
                        const SizedBox(width: 4),
                        Text(
                          '4.8',
                          style: theme.textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                ),
                Positioned(
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Container(
                    height: 80,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.bottomCenter,
                        end: Alignment.topCenter,
                        colors: [
                          Colors.black.withValues(alpha: 0.6),
                          Colors.transparent,
                        ],
                      ),
                    ),
                  ),
                ),
                Positioned(
                  bottom: 16,
                  left: 20,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppColors.primary,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      villa.tipe?.toUpperCase() ?? 'LUXURY',
                      style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1),
                    ),
                  ),
                ),
              ],
            ),
            Padding(
              padding: const EdgeInsets.all(AppSpacing.p20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Text(
                          villa.nama,
                          style: theme.textTheme.titleLarge?.copyWith(
                            fontWeight: FontWeight.w800,
                            fontSize: 22,
                          ),
                        ),
                      ),
                      Text(
                        villa.formattedHarga,
                        style: theme.textTheme.titleMedium?.copyWith(
                          color: AppColors.primary,
                          fontWeight: FontWeight.w900,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.p12),
                  Row(
                    children: [
                      _buildAmenity(Icons.bed_rounded, '${villa.bedroom} BR'),
                      const SizedBox(width: 12),
                      _buildAmenity(Icons.bathtub_rounded, '${villa.bathroom} BT'),
                      const SizedBox(width: 12),
                      _buildAmenity(Icons.groups_rounded, '${villa.capacity} Pax'),
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

  Widget _buildAmenity(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: AppColors.border.withValues(alpha: 0.5)),
      ),
      child: Row(
        children: [
          Icon(icon, size: 14, color: AppColors.textSecondary),
          const SizedBox(width: 6),
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.bold,
              color: AppColors.textSecondary,
            ),
          ),
        ],
      ),
    );
  }
}
