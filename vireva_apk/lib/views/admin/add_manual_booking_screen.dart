import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'dart:io';
import 'package:image_picker/image_picker.dart';
import '../../core/app_constants.dart';
import '../../viewmodels/admin_viewmodel.dart';
import '../../viewmodels/villa_viewmodel.dart';

class AddManualBookingScreen extends ConsumerStatefulWidget {
  const AddManualBookingScreen({super.key});

  @override
  ConsumerState<AddManualBookingScreen> createState() => _AddManualBookingScreenState();
}

class _AddManualBookingScreenState extends ConsumerState<AddManualBookingScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  
  int? _selectedVillaId;
  DateTime _checkinDate = DateTime.now();
  DateTime _checkoutDate = DateTime.now().add(const Duration(days: 1));
  File? _image;

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(villaViewModelProvider.notifier).fetchVillas();
    });
  }

  @override
  Widget build(BuildContext context) {
    final villaState = ref.watch(villaViewModelProvider);
    final adminState = ref.watch(adminViewModelProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(title: const Text('PESANAN MANUAL')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.p24),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Informasi Tamu', style: theme.textTheme.titleLarge),
              const SizedBox(height: 16),
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(labelText: 'Nama Tamu', prefixIcon: Icon(Icons.person_outline)),
                validator: (v) => v?.isEmpty == true ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _phoneController,
                decoration: const InputDecoration(labelText: 'Nomor WhatsApp', prefixIcon: Icon(Icons.phone_outlined)),
                keyboardType: TextInputType.phone,
                validator: (v) => v?.isEmpty == true ? 'Wajib diisi' : null,
              ),
              
              const SizedBox(height: 32),
              Text('Pilih Villa & Tanggal', style: theme.textTheme.titleLarge),
              const SizedBox(height: 16),
              DropdownButtonFormField<int>(
                initialValue: _selectedVillaId,
                decoration: const InputDecoration(labelText: 'Villa', prefixIcon: Icon(Icons.villa_outlined)),
                items: villaState.villas.map((v) {
                  return DropdownMenuItem<int>(
                    value: v.id,
                    child: Text(v.nama),
                  );
                }).toList(),
                onChanged: (v) => setState(() => _selectedVillaId = v),
                validator: (v) => v == null ? 'Wajib pilih villa' : null,
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: _buildDateTile('CHECK-IN', _checkinDate, true),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: _buildDateTile('CHECK-OUT', _checkoutDate, false),
                  ),
                ],
              ),
              
              const SizedBox(height: 32),
              Text('Bukti Pembayaran (Opsional)', style: theme.textTheme.titleLarge),
              const SizedBox(height: 16),
              GestureDetector(
                onTap: _pickImage,
                child: Container(
                  height: 200,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: AppColors.surface,
                    borderRadius: BorderRadius.circular(AppSpacing.radius),
                    border: Border.all(color: AppColors.border),
                  ),
                  child: _image != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(AppSpacing.radius),
                          child: Image.file(_image!, fit: BoxFit.cover),
                        )
                      : const Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.add_a_photo_outlined, size: 40, color: AppColors.textSecondary),
                            SizedBox(height: 8),
                            Text('Upload Bukti Transfer (Jika Ada)', style: TextStyle(color: AppColors.textSecondary)),
                          ],
                        ),
                ),
              ),
              
              const SizedBox(height: 48),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: (adminState.isLoading || villaState.isLoading) ? null : _submit,
                  child: adminState.isLoading
                      ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                      : const Text('SIMPAN PESANAN'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDateTile(String label, DateTime date, bool isCheckin) {
    return GestureDetector(
      onTap: () async {
        final DateTime? picked = await showDatePicker(
          context: context,
          initialDate: date,
          firstDate: isCheckin ? DateTime.now() : _checkinDate.add(const Duration(days: 1)),
          lastDate: DateTime.now().add(const Duration(days: 365)),
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
      },
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          border: Border.all(color: AppColors.border),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: const TextStyle(fontSize: 10, color: AppColors.textSecondary)),
            const SizedBox(height: 4),
            Text(DateFormat('dd/MM/yyyy').format(date)),
          ],
        ),
      ),
    );
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(source: ImageSource.gallery);
    if (picked != null) {
      setState(() => _image = File(picked.path));
    }
  }

  Future<void> _submit() async {
    if (_formKey.currentState!.validate()) {
      // Validasi overlap tanggal dari data bookings di adminState
      final adminState = ref.read(adminViewModelProvider);
      final conflictingBookings = adminState.bookings.where((b) {
        if (b.villa?.id != _selectedVillaId) return false;
        // Hanya cek pemesanan yang masih aktif/menunggu
        if (b.statusPemesanan == 'batal' || b.statusPemesanan == 'selesai') return false;
        
        try {
          final bCheckin = DateTime.parse(b.tanggalCheckin);
          final bCheckout = DateTime.parse(b.tanggalCheckout);
          
          // Strip time for pure date comparison
          final newCheckin = DateTime(_checkinDate.year, _checkinDate.month, _checkinDate.day);
          final newCheckout = DateTime(_checkoutDate.year, _checkoutDate.month, _checkoutDate.day);
          final existingCheckin = DateTime(bCheckin.year, bCheckin.month, bCheckin.day);
          final existingCheckout = DateTime(bCheckout.year, bCheckout.month, bCheckout.day);
          
          // Logika Overlap: Jika check-in baru mendahului check-out lama, 
          // DAN check-out baru melewati check-in lama.
          return newCheckin.isBefore(existingCheckout) && newCheckout.isAfter(existingCheckin);
        } catch (_) {
          return false;
        }
      }).toList();

      if (conflictingBookings.isNotEmpty) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Gagal: Villa sudah dipesan pada tanggal tersebut.'),
              backgroundColor: AppColors.error,
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
        return;
      }

      final success = await ref.read(adminViewModelProvider.notifier).createManualBooking(
        {
          'nama_tamu': _nameController.text,
          'no_hape': _phoneController.text,
          'villa_id': _selectedVillaId,
          'tanggal_checkin': DateFormat('yyyy-MM-dd').format(_checkinDate),
          'tanggal_checkout': DateFormat('yyyy-MM-dd').format(_checkoutDate),
        },
        _image?.path,
      );

      if (success && mounted) {
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Pemesanan manual berhasil disimpan!'),
            backgroundColor: AppColors.success,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    }
  }
}
