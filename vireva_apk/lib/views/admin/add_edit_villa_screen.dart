import 'dart:io';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../core/app_constants.dart';
import '../../models/villa_model.dart';
import '../../providers/villa_provider.dart';

class AddEditVillaScreen extends StatefulWidget {
  final VillaModel? villa;
  const AddEditVillaScreen({super.key, this.villa});

  @override
  State<AddEditVillaScreen> createState() => _AddEditVillaScreenState();
}

class _AddEditVillaScreenState extends State<AddEditVillaScreen> {
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
    _kapasitasController = TextEditingController(text: '2'); // Default
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
      success = await context.read<VillaProvider>().updateVilla(widget.villa!.id, data, _imageFile?.path);
    } else {
      success = await context.read<VillaProvider>().addVilla(data, _imageFile?.path);
    }

    setState(() => _isSubmitting = false);

    if (success) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Villa berhasil disimpan')),
        );
        Navigator.pop(context);
      }
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(context.read<VillaProvider>().error ?? 'Gagal menyimpan villa')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          icon: const Icon(Icons.close_rounded, color: AppColors.primary),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          widget.villa == null ? 'Tambah Villa Baru' : 'Edit Villa',
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.primary,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.p24),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildImagePicker(),
              const SizedBox(height: 24),
              _buildTextField('Nama Villa', _namaController, 'Contoh: Villa Sapphire'),
              const SizedBox(height: 16),
              _buildDropdown('Tipe Villa'),
              const SizedBox(height: 16),
              _buildTextField('Harga Per Malam (Rp)', _hargaController, '0', keyboardType: TextInputType.number),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(child: _buildTextField('Bedroom', _bedroomController, '1', keyboardType: TextInputType.number)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildTextField('Bathroom', _bathroomController, '1', keyboardType: TextInputType.number)),
                ],
              ),
              const SizedBox(height: 16),
              _buildTextField('Deskripsi', _deskripsiController, 'Ceritakan tentang villa ini...', maxLines: 4),
              const SizedBox(height: 32),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submit,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    elevation: 0,
                  ),
                  child: _isSubmitting 
                    ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : Text(
                        'Simpan Villa',
                        style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, color: Colors.white),
                      ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildImagePicker() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Foto Villa',
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        const SizedBox(height: 8),
        GestureDetector(
          onTap: _pickImage,
          child: Container(
            width: double.infinity,
            height: 180,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(24),
              border: Border.all(color: AppColors.border),
            ),
            child: _imageFile != null
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(24),
                    child: Image.file(_imageFile!, fit: BoxFit.cover),
                  )
                : (widget.villa?.imageUrl != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(24),
                        child: Image.network(widget.villa!.imageUrl!, fit: BoxFit.cover),
                      )
                    : Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.add_photo_alternate_outlined, size: 40, color: AppColors.accent),
                          const SizedBox(height: 8),
                          Text(
                            'Klik untuk pilih foto',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 12,
                              color: AppColors.textSecondary,
                            ),
                          ),
                        ],
                      )),
          ),
        ),
      ],
    );
  }

  Widget _buildTextField(String label, TextEditingController controller, String placeholder, {TextInputType keyboardType = TextInputType.text, int maxLines = 1}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          keyboardType: keyboardType,
          maxLines: maxLines,
          style: GoogleFonts.plusJakartaSans(fontSize: 14),
          decoration: InputDecoration(
            hintText: placeholder,
            hintStyle: GoogleFonts.plusJakartaSans(color: Colors.grey[400]),
            filled: true,
            fillColor: Colors.white,
            contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: AppColors.border),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: AppColors.border),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(16),
              borderSide: const BorderSide(color: AppColors.accent),
            ),
          ),
          validator: (value) => value == null || value.isEmpty ? 'Wajib diisi' : null,
        ),
      ],
    );
  }

  Widget _buildDropdown(String label) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        const SizedBox(height: 8),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.border),
          ),
          child: DropdownButtonHideUnderline(
            child: DropdownButton<String>(
              value: _tipeVilla,
              isExpanded: true,
              style: GoogleFonts.plusJakartaSans(fontSize: 14, color: AppColors.primary),
              items: [
                'Villa 1 Kamar Tidur',
                'Villa 2 Kamar Tidur',
                'Villa 3 Kamar Tidur',
                'Villa Keluarga (Family)',
                'Villa Presidential'
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
