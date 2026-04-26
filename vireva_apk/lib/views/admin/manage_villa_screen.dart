import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../providers/villa_provider.dart';
import '../../models/villa_model.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'add_edit_villa_screen.dart';

class ManageVillaScreen extends StatefulWidget {
  const ManageVillaScreen({super.key});

  @override
  State<ManageVillaScreen> createState() => _ManageVillaScreenState();
}

class _ManageVillaScreenState extends State<ManageVillaScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      if (mounted) {
        context.read<VillaProvider>().fetchVillas();
      }
    });
  }

  void _confirmDelete(VillaModel villa) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text('Hapus Villa', style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold)),
        content: Text('Apakah Anda yakin ingin menghapus ${villa.nama}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Batal', style: GoogleFonts.plusJakartaSans(color: Colors.grey)),
          ),
          TextButton(
            onPressed: () async {
              Navigator.pop(context);
              final success = await context.read<VillaProvider>().deleteVilla(villa.id);
              if (!mounted) return;
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text(success ? 'Villa berhasil dihapus' : 'Gagal menghapus villa')),
              );
            },
            child: Text('Hapus', style: GoogleFonts.plusJakartaSans(color: Colors.red, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final villaProvider = context.watch<VillaProvider>();

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
          'Kelola Villa',
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.primary,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
      ),
      body: villaProvider.isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.accent))
          : RefreshIndicator(
              onRefresh: () => villaProvider.fetchVillas(),
              color: AppColors.accent,
              child: ListView.builder(
                padding: const EdgeInsets.all(AppSpacing.p24),
                itemCount: villaProvider.villas.length,
                itemBuilder: (context, index) {
                  final villa = villaProvider.villas[index];
                  return _buildVillaTile(villa);
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
        label: Text(
          'Tambah Villa',
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
    );
  }

  Widget _buildVillaTile(VillaModel villa) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: CachedNetworkImage(
              imageUrl: villa.imageUrl ?? '',
              width: 80,
              height: 80,
              fit: BoxFit.cover,
              errorWidget: (context, url, error) => Container(
                width: 80,
                height: 80,
                color: Colors.grey[100],
                child: const Icon(Icons.image_not_supported_rounded, color: Colors.grey),
              ),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  villa.nama,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary,
                  ),
                ),
                Text(
                  villa.tipe,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 12,
                    color: AppColors.textSecondary,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  villa.formattedHarga,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                    color: AppColors.accent,
                  ),
                ),
              ],
            ),
          ),
          Column(
            children: [
              IconButton(
                icon: const Icon(Icons.edit_outlined, color: Colors.blue, size: 20),
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => AddEditVillaScreen(villa: villa)),
                  );
                },
              ),
              IconButton(
                icon: const Icon(Icons.delete_outline_rounded, color: Colors.red, size: 20),
                onPressed: () => _confirmDelete(villa),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
