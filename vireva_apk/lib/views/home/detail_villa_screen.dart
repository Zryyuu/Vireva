import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../providers/booking_provider.dart';

class DetailVillaScreen extends ConsumerStatefulWidget {
  final VillaModel villa;

  const DetailVillaScreen({super.key, required this.villa});

  @override
  ConsumerState<DetailVillaScreen> createState() => _DetailVillaScreenState();
}

class _DetailVillaScreenState extends ConsumerState<DetailVillaScreen> {
  DateTime _checkinDate = DateTime.now();
  DateTime _checkoutDate = DateTime.now().add(const Duration(days: 1));

  Future<void> _selectDate(BuildContext context, bool isCheckin) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: isCheckin ? _checkinDate : _checkoutDate,
      firstDate: isCheckin ? DateTime.now() : _checkinDate.add(const Duration(days: 1)),
      lastDate: DateTime.now().add(const Duration(days: 3650)),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: AppColors.primary,
              onPrimary: Colors.white,
              onSurface: AppColors.primary,
            ),
          ),
          child: child!,
        );
      },
    );
    if (picked != null) {
      setState(() {
        if (isCheckin) {
          _checkinDate = picked;
          if (_checkoutDate.isBefore(_checkinDate)) {
            _checkoutDate = _checkinDate.add(const Duration(days: 1));
          }
        } else {
          _checkoutDate = picked;
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final bookingState = ref.watch(bookingProvider);
    final theme = Theme.of(context);
    final villa = widget.villa;

    return Scaffold(
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 450,
            pinned: true,
            leading: Padding(
              padding: const EdgeInsets.all(8.0),
              child: CircleAvatar(
                backgroundColor: Colors.white,
                child: IconButton(
                  icon: const Icon(Icons.arrow_back_rounded, color: AppColors.primary, size: 20),
                  onPressed: () => Navigator.pop(context),
                ),
              ),
            ),
            flexibleSpace: FlexibleSpaceBar(
              background: CachedNetworkImage(
                imageUrl: villa.imageUrl ?? '',
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(color: AppColors.surface),
                errorWidget: (context, url, error) => Container(color: AppColors.surface, child: const Icon(Icons.error_outline)),
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Container(
              padding: const EdgeInsets.all(AppSpacing.p24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: AppColors.primary,
                          borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
                        ),
                        child: Text(
                          villa.tipe.toUpperCase(),
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1.2,
                          ),
                        ),
                      ),
                      Row(
                        children: [
                          const Icon(Icons.star_rounded, color: Colors.amber, size: 20),
                          const SizedBox(width: 4),
                          Text(
                            '4.9 (124 reviews)',
                            style: theme.textTheme.bodyMedium,
                          ),
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.p16),
                  Text(
                    villa.nama,
                    style: theme.textTheme.displayLarge?.copyWith(fontSize: 32),
                  ),
                  const SizedBox(height: AppSpacing.p24),
                  
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildInfoTile(Icons.bed_rounded, '${villa.bedroom} BR', theme),
                      _buildInfoTile(Icons.bathtub_rounded, '${villa.bathroom} BA', theme),
                      _buildInfoTile(Icons.square_foot_rounded, '${villa.luas} m²', theme),
                    ],
                  ),
                  
                  const SizedBox(height: AppSpacing.p32),
                  Text('Deskripsi', style: theme.textTheme.titleLarge),
                  const SizedBox(height: AppSpacing.p12),
                  Text(
                    villa.deskripsi,
                    style: theme.textTheme.bodyLarge?.copyWith(
                      color: AppColors.textSecondary,
                      height: 1.7,
                    ),
                  ),
                  
                  const SizedBox(height: AppSpacing.p32),
                  Text('Durasi Menginap', style: theme.textTheme.titleLarge),
                  const SizedBox(height: AppSpacing.p16),
                  Row(
                    children: [
                      Expanded(
                        child: _buildDateTile('CHECK-IN', DateFormat('dd MMM yyyy').format(_checkinDate), () => _selectDate(context, true), theme),
                      ),
                      const SizedBox(width: AppSpacing.p16),
                      Expanded(
                        child: _buildDateTile('CHECK-OUT', DateFormat('dd MMM yyyy').format(_checkoutDate), () => _selectDate(context, false), theme),
                      ),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.p48),
                ],
              ),
            ),
          ),
        ],
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(AppSpacing.p24),
        decoration: const BoxDecoration(
          color: Colors.white,
          border: Border(top: BorderSide(color: AppColors.border)),
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Total Harga', style: theme.textTheme.bodySmall),
                  Text(
                    villa.formattedHarga,
                    style: theme.textTheme.titleLarge?.copyWith(color: AppColors.primary),
                  ),
                ],
              ),
            ),
            const SizedBox(width: AppSpacing.p24),
            Expanded(
              flex: 2,
              child: ElevatedButton(
                onPressed: bookingState.isLoading 
                  ? null 
                  : () async {
                    try {
                      final success = await ref.read(bookingProvider.notifier).createBooking(
                        villaId: villa.id,
                        checkin: DateFormat('yyyy-MM-dd').format(_checkinDate),
                        checkout: DateFormat('yyyy-MM-dd').format(_checkoutDate),
                      );

                      if (context.mounted && success) {
                        _showSuccessDialog(context, theme);
                      }
                    } catch (e) {
                      if (context.mounted) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text(e.toString()), backgroundColor: AppColors.error),
                        );
                      }
                    }
                  },
                child: bookingState.isLoading 
                  ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                  : const Text('PESAN SEKARANG'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoTile(IconData icon, String label, ThemeData theme) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        children: [
          Icon(icon, size: 20, color: AppColors.primary),
          const SizedBox(width: 8),
          Text(label, style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  Widget _buildDateTile(String label, String date, VoidCallback onTap, ThemeData theme) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(AppSpacing.p16),
        decoration: BoxDecoration(
          color: AppColors.card,
          borderRadius: BorderRadius.circular(AppSpacing.radius),
          border: Border.all(color: AppColors.border),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: theme.textTheme.bodySmall?.copyWith(letterSpacing: 1, fontWeight: FontWeight.bold)),
            const SizedBox(height: 4),
            Text(date, style: theme.textTheme.titleMedium),
          ],
        ),
      ),
    );
  }

  void _showSuccessDialog(BuildContext context, ThemeData theme) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const SizedBox(height: AppSpacing.p20),
            const Icon(Icons.check_circle_rounded, color: AppColors.success, size: 80),
            const SizedBox(height: AppSpacing.p24),
            Text('Booking Berhasil!', style: theme.textTheme.titleLarge),
            const SizedBox(height: AppSpacing.p12),
            Text(
              'Pemesanan villa Anda telah diterima. Silakan cek riwayat pemesanan untuk melakukan upload bukti pembayaran.',
              textAlign: TextAlign.center,
              style: theme.textTheme.bodyMedium,
            ),
            const SizedBox(height: AppSpacing.p32),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(context);
                  Navigator.pop(context);
                },
                child: const Text('KEMBALI KE BERANDA'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
