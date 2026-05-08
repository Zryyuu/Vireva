import 'dart:io';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../viewmodels/villa_viewmodel.dart';

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
  String _statusVilla = 'tersedia';
  final List<XFile> _newImages = [];
  List<String> _oldImages = [];
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
      
      // Handle status gracefully if it is something unexpected
      if (['tersedia', 'terisi', 'maintenance'].contains(widget.villa!.statusVilla)) {
        _statusVilla = widget.villa!.statusVilla;
      }
      _oldImages = List.from(widget.villa!.rawImages);
    }
  }

  Future<void> _pickImages() async {
    final picker = ImagePicker();
    final pickedFiles = await picker.pickMultiImage();
    if (pickedFiles.isNotEmpty) {
      setState(() {
        _newImages.addAll(pickedFiles);
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
      'status_villa': _statusVilla,
    };

    bool success;
    if (widget.villa != null) {
      success = await ref.read(villaViewModelProvider.notifier).updateVilla(widget.villa!.id, data, _newImages, _oldImages);
    } else {
      success = await ref.read(villaViewModelProvider.notifier).addVilla(data, _newImages);
    }

    setState(() => _isSubmitting = false);

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Villa berhasil disimpan'), backgroundColor: AppColors.success),
      );
      Navigator.pop(context);
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(ref.read(villaViewModelProvider).error ?? 'Gagal menyimpan villa'), backgroundColor: AppColors.error),
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
                  Expanded(child: _buildTextField('KAMAR TIDUR', _bedroomController, '1', theme, keyboardType: TextInputType.number)),
                  const SizedBox(width: AppSpacing.p16),
                  Expanded(child: _buildTextField('KAMAR MANDI', _bathroomController, '1', theme, keyboardType: TextInputType.number)),
                ],
              ),
              const SizedBox(height: AppSpacing.p20),
              Row(
                children: [
                  Expanded(child: _buildTextField('KAPASITAS TAMU', _kapasitasController, '2', theme, keyboardType: TextInputType.number)),
                  const SizedBox(width: AppSpacing.p16),
                  Expanded(child: _buildTextField('LUAS (m²)', _luasController, '120', theme, keyboardType: TextInputType.number)),
                ],
              ),
              const SizedBox(height: AppSpacing.p20),
              _buildStatusDropdown('STATUS VILLA', theme),
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
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text('GALERI VILLA', style: theme.textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold, letterSpacing: 1)),
            TextButton.icon(
              onPressed: _pickImages,
              icon: const Icon(Icons.add_photo_alternate, size: 18),
              label: const Text('Tambah Foto'),
              style: TextButton.styleFrom(
                visualDensity: VisualDensity.compact,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        if (_oldImages.isEmpty && _newImages.isEmpty)
          GestureDetector(
            onTap: _pickImages,
            child: Container(
              width: double.infinity,
              height: 160,
              decoration: BoxDecoration(
                color: AppColors.surface,
                borderRadius: BorderRadius.circular(AppSpacing.radius),
                border: Border.all(color: AppColors.border, style: BorderStyle.solid),
              ),
              child: const Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.collections_outlined, size: 48, color: AppColors.primary),
                  SizedBox(height: 12),
                  Text('Klik untuk memilih foto villa', style: TextStyle(fontWeight: FontWeight.bold)),
                ],
              ),
            ),
          )
        else
          SizedBox(
            height: 120,
            child: ListView(
              scrollDirection: Axis.horizontal,
              children: [
                // Display old images (existing)
                for (int i = 0; i < _oldImages.length; i++)
                  Padding(
                    padding: const EdgeInsets.only(right: 12),
                    child: _buildImageThumbnail(
                      isNetwork: true,
                      // For viewing, we need the full URL. We use allImages assuming it aligns by index with rawImages
                      imageUrl: widget.villa!.allImages.length > i ? widget.villa!.allImages[i] : '',
                      onDelete: () {
                        setState(() {
                          _oldImages.removeAt(i);
                        });
                      },
                    ),
                  ),
                // Display new images
                for (int i = 0; i < _newImages.length; i++)
                  Padding(
                    padding: const EdgeInsets.only(right: 12),
                    child: _buildImageThumbnail(
                      isNetwork: false,
                      xfile: _newImages[i],
                      onDelete: () {
                        setState(() {
                          _newImages.removeAt(i);
                        });
                      },
                    ),
                  ),
              ],
            ),
          ),
      ],
    );
  }

  Widget _buildImageThumbnail({
    required bool isNetwork,
    String? imageUrl,
    XFile? xfile,
    required VoidCallback onDelete,
  }) {
    ImageProvider? imageProvider;
    if (isNetwork && imageUrl != null) {
      imageProvider = NetworkImage(imageUrl);
    } else if (xfile != null) {
      if (kIsWeb) {
        imageProvider = NetworkImage(xfile.path);
      } else {
        imageProvider = FileImage(File(xfile.path));
      }
    }

    return Stack(
      children: [
        Container(
          width: 160,
          height: 120,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(AppSpacing.radiusSm),
            border: Border.all(color: AppColors.border),
            image: imageProvider != null
                ? DecorationImage(
                    fit: BoxFit.cover,
                    image: imageProvider,
                  )
                : null,
          ),
        ),
        Positioned(
          top: 4,
          right: 4,
          child: GestureDetector(
            onTap: onDelete,
            child: Container(
              padding: const EdgeInsets.all(4),
              decoration: const BoxDecoration(
                color: Colors.red,
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.close, size: 16, color: Colors.white),
            ),
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

  Widget _buildStatusDropdown(String label, ThemeData theme) {
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
              value: _statusVilla,
              isExpanded: true,
              icon: const Icon(Icons.keyboard_arrow_down_rounded),
              items: const [
                DropdownMenuItem(value: 'tersedia', child: Text('Tersedia')),
                DropdownMenuItem(value: 'terisi', child: Text('Terisi')),
                DropdownMenuItem(value: 'maintenance', child: Text('Maintenance')),
              ],
              onChanged: (val) {
                if (val != null) {
                  setState(() => _statusVilla = val);
                }
              },
            ),
          ),
        ),
      ],
    );
  }
}
