import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../core/app_constants.dart';
import '../../../viewmodels/villa_viewmodel.dart';
import '../detail_villa_screen.dart';

class ExploreTab extends ConsumerWidget {
  const ExploreTab({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final villaState = ref.watch(villaViewModelProvider);
    final theme = Theme.of(context);
    
    return RefreshIndicator(
      onRefresh: () => ref.read(villaViewModelProvider.notifier).fetchVillas(),
      color: AppColors.primary,
      edgeOffset: 100,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          SliverPadding(
            padding: const EdgeInsets.fromLTRB(AppSpacing.p24, AppSpacing.p32, AppSpacing.p24, AppSpacing.p24),
            sliver: SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Text(
                    'Eksplorasi Keistimewaan',
                    style: theme.textTheme.titleSmall?.copyWith(
                      color: AppColors.primary,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 1.2,
                    ),
                  ),
                  const SizedBox(height: AppSpacing.p12),
                  Text(
                    'Temukan Villa Impian Anda.',
                    style: theme.textTheme.headlineMedium?.copyWith(
                      fontWeight: FontWeight.w900,
                      color: AppColors.textPrimary,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: AppSpacing.p8),
                  Text(
                    'Jelajahi koleksi unit villa premium Vireva. Fasilitas pribadi, privasi total, dan kenyamanan mewah menanti Anda.',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: AppColors.textSecondary,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ],
              ),
            ),
          ),
          
          if (villaState.isLoading)
            const SliverFillRemaining(
              child: Center(child: CircularProgressIndicator(color: AppColors.primary)),
            )
          else if (villaState.error != null)
            SliverFillRemaining(
              child: Center(child: Text(villaState.error!)),
            )
          else 
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
          border: Border.all(color: AppColors.border),
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
                    height: 200,
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
                  left: 16,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.9),
                      borderRadius: BorderRadius.circular(100),
                    ),
                    child: Text(
                      villa.tipe?.toUpperCase() ?? 'LUXURY',
                      style: const TextStyle(color: AppColors.primary, fontSize: 10, fontWeight: FontWeight.w900, letterSpacing: 0.5),
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
                  Text(
                    villa.nama,
                    style: theme.textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.w900,
                      fontSize: 18,
                    ),
                  ),
                  const SizedBox(height: AppSpacing.p8),
                  Row(
                    children: [
                      const Icon(Icons.bed_rounded, size: 14, color: AppColors.primary),
                      const SizedBox(width: 4),
                      Text('${villa.bedroom} KAMAR', style: const TextStyle(fontSize: 12, color: AppColors.primary, fontWeight: FontWeight.bold)),
                      const SizedBox(width: 16),
                      const Icon(Icons.bathtub_rounded, size: 14, color: AppColors.primary),
                      const SizedBox(width: 4),
                      Text('${villa.bathroom} MANDI', style: const TextStyle(fontSize: 12, color: AppColors.primary, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.p12),
                  Text(
                    villa.deskripsi.isNotEmpty ? villa.deskripsi : 'Villa mewah dengan pemandangan menakjubkan.',
                    style: theme.textTheme.bodySmall?.copyWith(color: AppColors.textSecondary, height: 1.5),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: AppSpacing.p20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('MULAI DARI', style: TextStyle(fontSize: 10, color: AppColors.textSecondary, fontWeight: FontWeight.bold)),
                          Row(
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Text(
                                villa.formattedHarga,
                                style: theme.textTheme.titleMedium?.copyWith(
                                  color: AppColors.primary,
                                  fontWeight: FontWeight.w900,
                                ),
                              ),
                              const Text('/mlm', style: TextStyle(fontSize: 12, color: AppColors.textSecondary)),
                            ],
                          ),
                        ],
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                        decoration: BoxDecoration(
                          color: const Color(0xFF1E2432), // Dark blue/black button from web
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: const Text(
                          'Reservasi',
                          style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12),
                        ),
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
