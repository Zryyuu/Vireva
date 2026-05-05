import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../providers/villa_provider.dart';

class AddEditVillaScreen extends ConsumerStatefulWidget {
  final VillaModel? villa;
  const AddEditVillaScreen({super.key, this.villa});

  @override
  ConsumerState<AddEditVillaScreen> createState() => _AddEditVillaScreenState();
}

class _AddEditVillaScreenState extends ConsumerState<AddEditVillaScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _namaController;
  late TextEditingController _hargaController;
  late TextEditingController _kapasitasController;
  late TextEditingController _bedroomController;
  late TextEditingController _bathroomController;
  late TextEditingController _luasController;
  late TextEditingController _deskripsiController;
  
  String _tipeVilla = 'Villa 1 Kamar Tidur';
  File? _imageFile;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _namaController = TextEditingController(text: widget.villa?.nama);
    _hargaController = TextEditingController(text: widget.villa?.harga.toStringAsFixed(0));
    _kapasitasController = TextEditingController(text: '2');
    _bedroomController = TextEditingController(text: widget.villa?.bedroom.toString() ?? '1');
    _bathroomController = TextEditingController(text: widget.villa?.bathroom.toString() ?? '1');
    _luasController = TextEditingController(text: widget.villa?.luas.toString() ?? '');
    _deskripsiController = TextEditingController(text: widget.villa?.deskripsi);
    if (widget.villa != null) {
      _tipeVilla = widget.villa!.tipe;
    }
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _isSubmitting = true);

    final data = {
      'nama_villa': _namaController.text,
      'tipe_villa': _tipeVilla,
      'harga_permalam': _hargaController.text,
      'kapasitas': _kapasitasController.text,
      'jumlah_bedroom': _bedroomController.text,
      'jumlah_bathroom': _bathroomController.text,
      'luas_bangunan': _luasController.text,
      'deskripsi': _deskripsiController.text,
    };

    bool success;
    if (widget.villa != null) {
      success = await ref.read(villaProvider.notifier).updateVilla(widget.villa!.id, data, _imageFile?.path);
    } else {
      success = await ref.read(villaProvider.notifier).addVilla(data, _imageFile?.path);
    }

    setState(() => _isSubmitting = false);

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Villa berhasil disimpan'), backgroundColor: AppColors.success),
      );
      Navigator.pop(context);
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(ref.read(villaProvider).error ?? 'Gagal menyimpan villa'), backgroundColor: AppColors.error),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.villa == null ? 'Tambah Villa Baru' : 'Edit Villa'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.p24),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildImagePicker(theme),
              const SizedBox(height: AppSpacing.p32),
              _buildTextField('NAMA VILLA', _namaController, 'Contoh: Villa Sapphire', theme),
              const SizedBox(height: AppSpacing.p20),
              _buildDropdown('TIPE VILLA', theme),
              const SizedBox(height: AppSpacing.p20),
              _buildTextField('HARGA PER MALAM (RP)', _hargaController, '0', theme, keyboardType: TextInputType.number),
              const SizedBox(height: AppSpacing.p20),
              Row(
                children: [
                  Expanded(child: _buildTextField('BEDROOM', _bedroomController, '1', theme, keyboardType: TextInputType.number)),
                  const SizedBox(width: AppSpacing.p16),
                  Expanded(child: _buildTextField('BATHROOM', _bathroomController, '1', theme, keyboardType: TextInputType.number)),
                ],
              ),
              const SizedBox(height: AppSpacing.p20),
              _buildTextField('DESKRIPSI', _deskripsiController, 'Ceritakan tentang villa ini...', theme, maxLines: 4),
              const SizedBox(height: AppSpacing.p48),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submit,
                  child: _isSubmitting 
                    ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text('SIMPAN VILLA'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildImagePicker(ThemeData theme) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text('FOTO VILLA', style: theme.textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold, letterSpacing: 1)),
        const SizedBox(height: 12),
        GestureDetector(
          onTap: _pickImage,
          child: Container(
            width: double.infinity,
            height: 200,
            decoration: BoxDecoration(
              color: AppColors.surface,
              borderRadius: BorderRadius.circular(AppSpacing.radius),
              border: Border.all(color: AppColors.border),
            ),
            child: _imageFile != null
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(AppSpacing.radius),
                    child: Image.file(_imageFile!, fit: BoxFit.cover),
                  )
                : (widget.villa?.imageUrl != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(AppSpacing.radius),
                        child: Image.network(widget.villa!.imageUrl!, fit: BoxFit.cover),
                      )
                    : const Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.add_photo_alternate_outlined, size: 48, color: AppColors.primary),
                          SizedBox(height: 12),
                          Text('Klik untuk pilih foto villa'),
                        ],
                      )),
          ),
        ),
      ],
    );
  }

  Widget _buildTextField(String label, TextEditingController controller, String placeholder, ThemeData theme, {TextInputType keyboardType = TextInputType.text, int maxLines = 1}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: theme.textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold, letterSpacing: 1)),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          keyboardType: keyboardType,
          maxLines: maxLines,
          decoration: InputDecoration(
            hintText: placeholder,
          ),
          validator: (value) => value == null || value.isEmpty ? 'Wajib diisi' : null,
        ),
      ],
    );
  }

  Widget _buildDropdown(String label, ThemeData theme) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: theme.textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold, letterSpacing: 1)),
        const SizedBox(height: 8),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
            border: Border.all(color: AppColors.border),
          ),
          child: DropdownButtonHideUnderline(
            child: DropdownButton<String>(
              value: _tipeVilla,
              isExpanded: true,
              icon: const Icon(Icons.keyboard_arrow_down_rounded),
              items: [
                'Villa 1 Kamar Tidur',
                'Villa 2 Kamar Tidur',
                'Villa 3 Kamar Tidur',
                'Villa Keluarga (Family)',
                'Villa Presidential',
                '1-Bedroom Villa',
                '2-Bedroom Villa',
                '3-Bedroom Villa',
              ].map((String val) {
                return DropdownMenuItem<String>(
                  value: val,
                  child: Text(val),
                );
              }).toList(),
              onChanged: (val) {
                setState(() => _tipeVilla = val!);
              },
            ),
          ),
        ),
      ],
    );
  }
}
