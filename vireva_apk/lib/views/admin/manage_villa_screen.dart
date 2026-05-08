import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/app_constants.dart';
import '../../viewmodels/villa_viewmodel.dart';
import '../../models/villa_model.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'add_edit_villa_screen.dart';

class ManageVillaScreen extends ConsumerStatefulWidget {
  const ManageVillaScreen({super.key});

  @override
  ConsumerState<ManageVillaScreen> createState() => _ManageVillaScreenState();
}

class _ManageVillaScreenState extends ConsumerState<ManageVillaScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(villaViewModelProvider.notifier).fetchVillas();
    });
  }

  void _confirmDelete(VillaModel villa) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
        title: const Text('Hapus Villa'),
        content: Text('Apakah Anda yakin ingin menghapus ${villa.nama}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('BATAL', style: TextStyle(color: AppColors.textSecondary)),
          ),
          TextButton(
            onPressed: () async {
              Navigator.pop(context);
              final success = await ref.read(villaViewModelProvider.notifier).deleteVilla(villa.id);
              if (!context.mounted) return;
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(success ? 'Villa berhasil dihapus' : 'Gagal menghapus villa'),
                  backgroundColor: success ? AppColors.success : AppColors.error,
                  behavior: SnackBarBehavior.floating,
                ),
              );
            },
            child: const Text('HAPUS', style: TextStyle(color: AppColors.error, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final villaState = ref.watch(villaViewModelProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Kelola Villa'),
      ),
      body: villaState.isLoading && villaState.villas.isEmpty
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : RefreshIndicator(
              onRefresh: () => ref.read(villaViewModelProvider.notifier).fetchVillas(),
              color: AppColors.primary,
              child: ListView.builder(
                padding: const EdgeInsets.all(AppSpacing.p24),
                itemCount: villaState.villas.length,
                itemBuilder: (context, index) {
                  final villa = villaState.villas[index];
                  return _buildVillaTile(villa, theme);
                },
              ),
            ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const AddEditVillaScreen()),
          );
        },
        backgroundColor: AppColors.primary,
        icon: const Icon(Icons.add_rounded, color: Colors.white),
        label: const Text('TAMBAH VILLA', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
      ),
    );
  }

  Widget _buildVillaTile(VillaModel villa, ThemeData theme) {
    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      padding: const EdgeInsets.all(AppSpacing.p12),
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
            child: CachedNetworkImage(
              imageUrl: villa.imageUrl ?? '',
              width: 90,
              height: 90,
              fit: BoxFit.cover,
              errorWidget: (context, url, error) => Container(
                width: 90,
                height: 90,
                color: AppColors.surface,
                child: const Icon(Icons.villa_rounded, color: AppColors.textSecondary),
              ),
            ),
          ),
          const SizedBox(width: AppSpacing.p16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  villa.nama,
                  style: theme.textTheme.titleMedium,
                ),
                Text(
                  villa.tipe,
                  style: theme.textTheme.bodySmall,
                ),
                const SizedBox(height: 8),
                Text(
                  villa.formattedHarga,
                  style: theme.textTheme.bodyMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary,
                  ),
                ),
              ],
            ),
          ),
          PopupMenuButton<String>(
            icon: const Icon(Icons.more_vert_rounded, color: AppColors.textMuted),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radiusSm)),
            onSelected: (value) {
              if (value == 'edit') {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => AddEditVillaScreen(villa: villa)),
                );
              } else if (value == 'delete') {
                _confirmDelete(villa);
              }
            },
            itemBuilder: (context) => [
              const PopupMenuItem(
                value: 'edit',
                child: Row(
                  children: [
                    Icon(Icons.edit_outlined, size: 18, color: AppColors.primary),
                    SizedBox(width: 12),
                    Text('Edit Villa'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'delete',
                child: Row(
                  children: [
                    Icon(Icons.delete_outline_rounded, size: 18, color: AppColors.error),
                    SizedBox(width: 12),
                    Text('Hapus Villa', style: TextStyle(color: AppColors.error)),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
