import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../viewmodels/booking_viewmodel.dart';

class DetailVillaScreen extends ConsumerStatefulWidget {
  final VillaModel villa;

  const DetailVillaScreen({super.key, required this.villa});

  @override
  ConsumerState<DetailVillaScreen> createState() => _DetailVillaScreenState();
}

class _DetailVillaScreenState extends ConsumerState<DetailVillaScreen> {
  DateTime? _checkinDate;
  DateTime? _checkoutDate;

  bool _isDateAvailable(DateTime day) {
    final dayOnly = DateTime(day.year, day.month, day.day);
    for (var booking in widget.villa.bookedDates) {
      try {
        final ci = DateTime.parse(booking['checkin']!);
        final co = DateTime.parse(booking['checkout']!);
        final ciOnly = DateTime(ci.year, ci.month, ci.day);
        final coOnly = DateTime(co.year, co.month, co.day);
        
        // Disable dates from Check-in up to Check-out (inclusive).
        // This matches the web frontend (Flatpickr) behavior which blocks both from and to dates.
        if (!dayOnly.isBefore(ciOnly) && !dayOnly.isAfter(coOnly)) {
          return false;
        }
      } catch (_) {}
    }
    return true;
  }

  DateTime _getFirstAvailableDate(DateTime start) {
    DateTime current = start;
    // Limit to 365 days to prevent infinite loop
    for (int i = 0; i < 365; i++) {
      if (_isDateAvailable(current)) return current;
      current = current.add(const Duration(days: 1));
    }
    return start;
  }

  Future<void> _selectDate(BuildContext context, bool isCheckin) async {
    final now = DateTime.now();
    final firstValidCheckin = _getFirstAvailableDate(now);
    
    DateTime initial;
    DateTime first;
    
    if (isCheckin) {
      first = now;
      initial = _checkinDate ?? firstValidCheckin;
      if (!_isDateAvailable(initial)) initial = firstValidCheckin;
    } else {
      final baseDate = _checkinDate ?? firstValidCheckin;
      first = baseDate.add(const Duration(days: 1));
      initial = _checkoutDate ?? _getFirstAvailableDate(first);
      if (!_isDateAvailable(initial)) initial = _getFirstAvailableDate(first);
    }

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initial,
      firstDate: first,
      lastDate: now.add(const Duration(days: 3650)),
      selectableDayPredicate: _isDateAvailable,
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
          // If checkout is before new checkin, reset checkout
          if (_checkoutDate != null && _checkoutDate!.isBefore(_checkinDate!)) {
            _checkoutDate = null;
          }
        } else {
          _checkoutDate = picked;
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final bookingState = ref.watch(bookingViewModelProvider);
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
                  
                  Wrap(
                    spacing: AppSpacing.p8,
                    runSpacing: AppSpacing.p8,
                    children: [
                      _buildInfoTile(Icons.bed_rounded, '${villa.bedroom} KAMAR', theme),
                      _buildInfoTile(Icons.bathtub_rounded, '${villa.bathroom} MANDI', theme),
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
                        child: _buildDateTile('CHECK-IN', _checkinDate != null ? DateFormat('dd MMM yyyy').format(_checkinDate!) : 'Pilih Tanggal', () => _selectDate(context, true), theme),
                      ),
                      const SizedBox(width: AppSpacing.p16),
                      Expanded(
                        child: _buildDateTile('CHECK-OUT', _checkoutDate != null ? DateFormat('dd MMM yyyy').format(_checkoutDate!) : 'Pilih Tanggal', () {
                          if (_checkinDate == null) {
                            ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Pilih tanggal Check-in terlebih dahulu')));
                            return;
                          }
                          _selectDate(context, false);
                        }, theme),
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
                onPressed: (bookingState.isLoading || _checkinDate == null || _checkoutDate == null)
                  ? null 
                  : () async {
                    try {
                      final success = await ref.read(bookingViewModelProvider.notifier).createBooking(
                        villaId: villa.id,
                        checkin: DateFormat('yyyy-MM-dd').format(_checkinDate!),
                        checkout: DateFormat('yyyy-MM-dd').format(_checkoutDate!),
                      );

                      if (context.mounted) {
                        if (success) {
                          _showSuccessDialog(context, theme);
                        } else {
                          final errorMsg = ref.read(bookingViewModelProvider).error ?? 'Terjadi kesalahan saat memproses pesanan.';
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text(errorMsg.replaceAll('Exception: ', '')),
                              backgroundColor: AppColors.error,
                            ),
                          );
                        }
                      }
                    } catch (e) {
                      if (context.mounted) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text(e.toString().replaceAll('Exception: ', '')), backgroundColor: AppColors.error),
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
