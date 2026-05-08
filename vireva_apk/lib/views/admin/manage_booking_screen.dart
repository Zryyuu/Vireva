import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/app_constants.dart';
import '../../viewmodels/admin_viewmodel.dart';
import '../../models/booking_model.dart';
import 'add_manual_booking_screen.dart';

class ManageBookingScreen extends ConsumerStatefulWidget {
  const ManageBookingScreen({super.key});

  @override
  ConsumerState<ManageBookingScreen> createState() => _ManageBookingScreenState();
}

class _ManageBookingScreenState extends ConsumerState<ManageBookingScreen> {
  String _selectedFilter = 'Semua';
  int? _selectedMonth = DateTime.now().month;
  int? _selectedYear = DateTime.now().year;

  final Map<int, String> _months = {
    1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr',
    5: 'Mei', 6: 'Jun', 7: 'Jul', 8: 'Agu',
    9: 'Sep', 10: 'Okt', 11: 'Nov', 12: 'Des'
  };
  final List<int> _years = [2024, 2025, 2026, 2027];
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(adminViewModelProvider.notifier).fetchBookings();
    });
  }

  @override
  Widget build(BuildContext context) {
    final adminState = ref.watch(adminViewModelProvider);
    final theme = Theme.of(context);

    final filteredBookings = adminState.bookings.where((b) {
      bool matchStatus = _selectedFilter == 'Semua' || b.statusPemesanan.toLowerCase() == _selectedFilter.toLowerCase();
      
      bool matchMonth = true;
      bool matchYear = true;
      
      if (_selectedMonth != null || _selectedYear != null) {
        try {
          final date = DateTime.parse(b.tanggalCheckin);
          if (_selectedMonth != null) {
            matchMonth = date.month == _selectedMonth;
          }
          if (_selectedYear != null) {
            matchYear = date.year == _selectedYear;
          }
        } catch (_) {
           matchMonth = false;
        }
      }
      
      return matchStatus && matchMonth && matchYear;
    }).toList();

    return Scaffold(
      appBar: AppBar(
        title: const Text('DAFTAR RESERVASI'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            child: Row(
              children: [
                Expanded(
                  child: _buildDropdown<String?>(
                    value: _selectedFilter == 'Semua' ? null : _selectedFilter,
                    hint: 'Status',
                    isActive: _selectedFilter != 'Semua',
                    items: ['Semua', 'Menunggu', 'Aktif', 'Selesai', 'Batal']
                        .map((s) => DropdownMenuItem(value: s == 'Semua' ? null : s, child: Text(s, overflow: TextOverflow.ellipsis)))
                        .toList(),
                    onChanged: (v) => setState(() => _selectedFilter = v ?? 'Semua'),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: _buildDropdown<int?>(
                    value: _selectedMonth,
                    hint: 'Bulan',
                    isActive: _selectedMonth != null,
                    items: [
                      const DropdownMenuItem<int?>(value: null, child: Text('Semua', overflow: TextOverflow.ellipsis)),
                      ..._months.entries.map((e) => DropdownMenuItem<int?>(value: e.key, child: Text(e.value, overflow: TextOverflow.ellipsis)))
                    ],
                    onChanged: (v) => setState(() => _selectedMonth = v),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: _buildDropdown<int?>(
                    value: _selectedYear,
                    hint: 'Tahun',
                    isActive: _selectedYear != null,
                    items: [
                      const DropdownMenuItem<int?>(value: null, child: Text('Semua', overflow: TextOverflow.ellipsis)),
                      ..._years.map((y) => DropdownMenuItem<int?>(value: y, child: Text(y.toString(), overflow: TextOverflow.ellipsis)))
                    ],
                    onChanged: (v) => setState(() => _selectedYear = v),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: adminState.isLoading && adminState.bookings.isEmpty
                ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                : RefreshIndicator(
                    onRefresh: () => ref.read(adminViewModelProvider.notifier).fetchBookings(),
                    color: AppColors.primary,
                    child: filteredBookings.isEmpty 
                        ? ListView(
                            children: const [
                              Padding(
                                padding: EdgeInsets.all(32.0),
                                child: Center(child: Text('Tidak ada reservasi ditemukan.', style: TextStyle(color: AppColors.textSecondary))),
                              )
                            ],
                          )
                        : ListView.builder(
                            padding: const EdgeInsets.fromLTRB(AppSpacing.p24, AppSpacing.p24, AppSpacing.p24, 100), // Prevent FAB overlap
                            itemCount: filteredBookings.length,
                            itemBuilder: (context, index) {
                              final booking = filteredBookings[index];
                              return _buildBookingCard(booking, theme);
                            },
                          ),
                  ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const AddManualBookingScreen()),
        ),
        backgroundColor: AppColors.primary,
        icon: const Icon(Icons.add, color: Colors.white),
        label: const Text('Reservasi Manual', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
      ),
    );
  }

  Widget _buildBookingCard(BookingModel booking, ThemeData theme) {
    Color statusColor;
    String statusText;

    switch (booking.statusPemesanan) {
      case 'aktif':
        statusColor = Colors.blue;
        statusText = 'AKTIF';
        break;
      case 'selesai':
        statusColor = AppColors.success;
        statusText = 'SELESAI';
        break;
      case 'batal':
        statusColor = AppColors.error;
        statusText = 'BATAL';
        break;
      default:
        statusColor = Colors.orange;
        statusText = 'MENUNGGU';
    }

    return Container(
      margin: const EdgeInsets.only(bottom: AppSpacing.p16),
      padding: const EdgeInsets.all(AppSpacing.p20),
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: statusColor.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
                ),
                child: Text(
                  statusText,
                  style: theme.textTheme.bodySmall?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: statusColor,
                    fontSize: 10,
                  ),
                ),
              ),
              Text(
                '#${booking.id}',
                style: theme.textTheme.bodySmall,
              ),
            ],
          ),
          const SizedBox(height: AppSpacing.p16),
          Text(
            booking.tamu?.namaTamu ?? 'Tamu',
            style: theme.textTheme.titleMedium,
          ),
          Text(
            booking.villa?.nama ?? 'Villa',
            style: theme.textTheme.bodyMedium,
          ),
          const Padding(
            padding: EdgeInsets.symmetric(vertical: AppSpacing.p12),
            child: Divider(height: 1, color: AppColors.border),
          ),
          Row(
            children: [
              const Icon(Icons.calendar_today_rounded, size: 14, color: AppColors.textSecondary),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  '${_formatDate(booking.tanggalCheckin)} - ${_formatDate(booking.tanggalCheckout)}',
                  style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.bold),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
          if (booking.isPending && booking.buktiPembayaran != null) ...[
            const SizedBox(height: AppSpacing.p20),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.orange.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
                border: Border.all(color: Colors.orange.withValues(alpha: 0.3)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'BUKTI PEMBAYARAN DIUPLOAD',
                    style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.orange),
                  ),
                  const SizedBox(height: 12),
                  GestureDetector(
                    onTap: () => _showImagePreview(booking.buktiPembayaran!),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: CachedNetworkImage(
                        imageUrl: booking.buktiPembayaran!,
                        height: 100,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        placeholder: (context, url) => Container(color: AppColors.surface),
                        errorWidget: (context, url, error) => const Icon(Icons.image_not_supported),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton(
                          onPressed: () => _verifyPayment(booking.id, 'settlement'),
                          style: ElevatedButton.styleFrom(backgroundColor: AppColors.success),
                          child: const Text('TERIMA'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: OutlinedButton(
                          onPressed: () => _verifyPayment(booking.id, 'cancel'),
                          style: OutlinedButton.styleFrom(foregroundColor: AppColors.error),
                          child: const Text('TOLAK'),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],

          if (booking.statusPemesanan == 'aktif' || (booking.statusPemesanan == 'menunggu' && booking.isSettled)) ...[
            const SizedBox(height: AppSpacing.p20),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: () => _handleAction(booking.id, 'cancel', 'Membatalkan reservasi ini?'),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: AppColors.error,
                      side: const BorderSide(color: AppColors.error),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radiusSm)),
                      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 12),
                    ),
                    child: const FittedBox(
                      fit: BoxFit.scaleDown,
                      child: Text('BATALKAN', maxLines: 1),
                    ),
                  ),
                ),
                const SizedBox(width: AppSpacing.p12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () {
                      final action = (booking.statusPemesanan == 'menunggu') ? 'checkin' : 'checkout';
                      final actionText = (booking.statusPemesanan == 'menunggu') ? 'Konfirmasi Check-in?' : 'Konfirmasi Check-out?';
                      _handleAction(booking.id, action, actionText);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: (booking.statusPemesanan == 'aktif') ? AppColors.primary : AppColors.success,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radiusSm)),
                      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 12),
                    ),
                    child: FittedBox(
                      fit: BoxFit.scaleDown,
                      child: Text(
                        (booking.statusPemesanan == 'aktif') ? 'CHECK-OUT' : 'CHECK-IN',
                        style: const TextStyle(fontWeight: FontWeight.bold),
                        maxLines: 1,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildDropdown<T>({
    required T value,
    required String hint,
    required List<DropdownMenuItem<T>> items,
    required ValueChanged<T?> onChanged,
    bool isActive = false,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(100),
        border: Border.all(color: Colors.black),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<T>(
          value: value,
          isExpanded: true,
          hint: Text(hint, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.black), overflow: TextOverflow.ellipsis),
          style: const TextStyle(fontSize: 12, color: Colors.black, fontWeight: FontWeight.bold),
          icon: const Icon(Icons.keyboard_arrow_down_rounded, size: 18, color: Colors.black),
          isDense: true,
          items: items,
          onChanged: onChanged,
        ),
      ),
    );
  }

  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}';
    } catch (_) {
      if (dateString.length >= 10) return dateString.substring(0, 10);
      return dateString;
    }
  }

  void _showImagePreview(String url) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            CachedNetworkImage(imageUrl: url),
            TextButton(onPressed: () => Navigator.pop(context), child: const Text('TUTUP')),
          ],
        ),
      ),
    );
  }

  Future<void> _verifyPayment(int id, String status) async {
    final success = await ref.read(adminViewModelProvider.notifier).verifyPayment(id, status);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(success ? 'Berhasil diverifikasi' : 'Gagal verifikasi')),
      );
    }
  }

  void _handleAction(int id, String action, String message) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
        title: const Text('Konfirmasi'),
        content: Text(message),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('TIDAK')),
          TextButton(
            onPressed: () => Navigator.pop(ctx, true), 
            child: const Text('YA', style: TextStyle(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );

    if (confirm == true && mounted) {
      final success = await ref.read(adminViewModelProvider.notifier).processAction(id, action);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(success ? 'Berhasil diproses' : 'Gagal memproses'),
            backgroundColor: success ? AppColors.success : AppColors.error,
          ),
        );
      }
    }
  }
}
