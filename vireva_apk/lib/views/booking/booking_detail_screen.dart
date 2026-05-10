import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:screenshot/screenshot.dart';
import 'package:blue_thermal_printer/blue_thermal_printer.dart';
import 'package:image/image.dart' as img;
import 'package:esc_pos_utils_plus/esc_pos_utils_plus.dart';
import 'package:flutter/services.dart';
import '../../core/app_constants.dart';
import '../../models/booking_model.dart';
import '../../viewmodels/booking_viewmodel.dart';

class BookingDetailScreen extends ConsumerStatefulWidget {
  final BookingModel booking;

  const BookingDetailScreen({super.key, required this.booking});

  @override
  ConsumerState<BookingDetailScreen> createState() => _BookingDetailScreenState();
}

class _BookingDetailScreenState extends ConsumerState<BookingDetailScreen> {
  final ImagePicker _picker = ImagePicker();
  final ScreenshotController _screenshotController = ScreenshotController();
  final BlueThermalPrinter bluetooth = BlueThermalPrinter.instance;
  bool _isUploading = false;
  bool _isPrinting = false; // Mencegah pencet berulang

  Future<void> _printReceipt() async {
    if (_isPrinting) return;

    // 1. Cek apakah Bluetooth aktif
    bool? isOn = await bluetooth.isOn;
    if (isOn == false) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Bluetooth mati. Silakan aktifkan dulu.'),
          action: SnackBarAction(
            label: 'SETTING',
            onPressed: () => bluetooth.openSettings,
          ),
        ),
      );
      return;
    }

    setState(() => _isPrinting = true);

    try {
      final Uint8List? imageBytes = await _screenshotController.capture();
      if (imageBytes == null) {
        setState(() { _isPrinting = false; });
        return;
      }

      setState(() { _isPrinting = false; }); // Lepas state untuk dialog
      _showBluetoothDevicePicker(imageBytes);

    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal menyiapkan struk: $e'), backgroundColor: Colors.red),
        );
        setState(() => _isPrinting = false);
      }
    }
  }

  Future<void> _showBluetoothDevicePicker(Uint8List imageBytes) async {
    List<BluetoothDevice> devices = [];
    try {
      devices = await bluetooth.getBondedDevices();
    } catch (e) {
      debugPrint("Error getting devices: $e");
    }

    if (!mounted) return;

    if (devices.isEmpty) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Tidak ada perangkat Bluetooth yang di-pairing. Pastikan sudah pair di HP.'),
          backgroundColor: Colors.orange,
        ),
      );
      return;
    }

    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (context) {
        return SingleChildScrollView(
          padding: const EdgeInsets.symmetric(vertical: 24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text('Pilih Printer Bluetooth', style: GoogleFonts.spaceGrotesk(fontSize: 16, fontWeight: FontWeight.bold)),
              const SizedBox(height: 8),
              Text('Hanya perangkat yang sudah di-pairing', style: TextStyle(fontSize: 12, color: Colors.grey[600])),
              const SizedBox(height: 16),
              ...devices.map((device) => ListTile(
                leading: const Icon(Icons.print_rounded, color: AppColors.primary),
                title: Text(device.name ?? 'Unknown Device'),
                subtitle: Text(device.address ?? ''),
                onTap: () {
                  Navigator.pop(context);
                  _connectAndPrint(device, imageBytes);
                },
              )),
            ],
          ),
        );
      }
    );
  }

  Future<void> _connectAndPrint(BluetoothDevice device, Uint8List imageBytes) async {
    if (_isPrinting) return;
    setState(() => _isPrinting = true);

    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Menghubungkan ke ${device.name}...')),
    );

    try {
      bool? isConnected = await bluetooth.isConnected;
      if (isConnected == false) {
        await bluetooth.connect(device);
      }

      final booking = widget.booking;
      final profile = await CapabilityProfile.load();
      final generator = Generator(PaperSize.mm58, profile);
      List<int> bytes = [];

      // 1. Logo kecil (160px — ringan & stabil untuk printer RP58B)
      try {
        final ByteData data = await rootBundle.load('assets/images/logo.png');
        final Uint8List logoBytes = data.buffer.asUint8List();
        img.Image? logo = img.decodeImage(logoBytes);
        if (logo != null) {
          logo = img.copyResize(logo, width: 160);
          logo = img.grayscale(logo);
          bytes += generator.image(logo, align: PosAlign.center);
        }
      } catch (e) {
        debugPrint("Gagal muat logo: $e");
      }

      // 2. Header teks
      bytes += generator.text('VIREVA VILLA',
          styles: const PosStyles(align: PosAlign.center, bold: true, height: PosTextSize.size2, width: PosTextSize.size2));
      bytes += generator.text('Curahbamban, Tanggul Wetan', styles: const PosStyles(align: PosAlign.center));
      bytes += generator.text('0851-9814-9402', styles: const PosStyles(align: PosAlign.center));
      bytes += generator.text('================================', styles: const PosStyles(align: PosAlign.center));

      // 3. Detail booking
      bytes += generator.row([
        PosColumn(text: 'INVOICE', width: 5),
        PosColumn(text: '#VRV-${booking.id.toString().padLeft(5, '0')}', width: 7, styles: const PosStyles(align: PosAlign.right)),
      ]);
      bytes += generator.row([
        PosColumn(text: 'TANGGAL', width: 5),
        PosColumn(text: booking.createdAt?.split('T')[0] ?? '-', width: 7, styles: const PosStyles(align: PosAlign.right)),
      ]);
      bytes += generator.row([
        PosColumn(text: 'TAMU', width: 5),
        PosColumn(text: booking.tamu?.namaTamu.toUpperCase() ?? 'TAMU', width: 7, styles: const PosStyles(align: PosAlign.right)),
      ]);

      bytes += generator.text('--------------------------------');
      bytes += generator.text(booking.villa?.nama ?? 'VILLA', styles: const PosStyles(bold: true));
      bytes += generator.row([
        PosColumn(text: 'CHECK-IN', width: 6),
        PosColumn(text: _formatDate(booking.tanggalCheckin), width: 6, styles: const PosStyles(align: PosAlign.right)),
      ]);
      bytes += generator.row([
        PosColumn(text: 'CHECK-OUT', width: 6),
        PosColumn(text: _formatDate(booking.tanggalCheckout), width: 6, styles: const PosStyles(align: PosAlign.right)),
      ]);
      bytes += generator.row([
        PosColumn(text: 'DURASI', width: 6),
        PosColumn(text: '${booking.totalHari} MALAM', width: 6, styles: const PosStyles(align: PosAlign.right)),
      ]);

      bytes += generator.text('================================');
      bytes += generator.row([
        PosColumn(text: 'TOTAL', width: 6, styles: const PosStyles(bold: true)),
        PosColumn(text: booking.formattedTotalBiaya, width: 6, styles: const PosStyles(align: PosAlign.right, bold: true)),
      ]);
      bytes += generator.row([
        PosColumn(text: 'STATUS', width: 6),
        PosColumn(text: 'LUNAS', width: 6, styles: const PosStyles(align: PosAlign.right, bold: true)),
      ]);

      bytes += generator.text('================================', styles: const PosStyles(align: PosAlign.center));
      bytes += generator.feed(1);
      bytes += generator.text('TERIMA KASIH', styles: const PosStyles(align: PosAlign.center, bold: true));
      bytes += generator.text('Simpan struk sebagai bukti', styles: const PosStyles(align: PosAlign.center));
      bytes += generator.feed(3);

      // 4. Kirim ke printer
      await bluetooth.writeBytes(Uint8List.fromList(bytes));

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Berhasil mencetak!'), backgroundColor: Colors.green),
        );
      }
    } catch (e) {
      debugPrint("Bluetooth Error: $e");
      try { await bluetooth.disconnect(); } catch (_) {}

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal mencetak: $e'), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) setState(() => _isPrinting = false);
    }
  }


  Future<void> _pickAndUploadImage() async {
    final XFile? image = await _picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 70,
    );

    if (image != null) {
      setState(() => _isUploading = true);
      final success = await ref
          .read(bookingViewModelProvider.notifier)
          .uploadBukti(widget.booking.id, image.path);
      
      if (mounted) {
        setState(() => _isUploading = false);
        if (success) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Bukti pembayaran berhasil diupload!')),
          );
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    // Watch the specific booking from state to get updates after upload
    final bookings = ref.watch(bookingViewModelProvider).bookings;
    final booking = bookings.firstWhere(
      (b) => b.id == widget.booking.id,
      orElse: () => widget.booking,
    );

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('Detail Reservasi', style: GoogleFonts.spaceGrotesk(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        foregroundColor: AppColors.secondary,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppSpacing.p24),
        child: Column(
          children: [
            _buildStatusCard(booking),
            const SizedBox(height: 24),
            _buildVillaCard(booking),
            const SizedBox(height: 24),
            if (booking.isPending && !booking.hasBukti) ...[
              _buildNoProofBanner(),
              const SizedBox(height: 16),
              _buildPaymentInstructions(),
            ] else if (booking.isPending && booking.hasBukti)
              _buildWaitingVerification()
            else if (booking.isSettled || booking.isActive || booking.isCompleted) ...[
              Screenshot(
                controller: _screenshotController,
                child: _buildThermalReceiptView(booking),
              ),
              const SizedBox(height: 24),
              _buildPrintActionButton(),
            ],
            const SizedBox(height: 40),
          ],
        ),
      ),
      bottomNavigationBar: (booking.isPending && !booking.hasBukti)
          ? _buildBottomAction()
          : null,
    );
  }

  Widget _buildNoProofBanner() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
      decoration: BoxDecoration(
        color: AppColors.error.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.error.withValues(alpha: 0.2)),
      ),
      child: const Row(
        children: [
          Icon(Icons.warning_amber_rounded, color: AppColors.error, size: 20),
          SizedBox(width: 12),
          Expanded(
            child: Text(
              'ANDA BELUM MENGUNGGAH BUKTI PEMBAYARAN',
              style: TextStyle(color: AppColors.error, fontWeight: FontWeight.w900, fontSize: 10),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatusCard(BookingModel booking) {
    Color statusColor = AppColors.primary;
    if (booking.isCancelled) statusColor = AppColors.error;
    if (booking.isPending) statusColor = AppColors.textMuted;
    if (booking.isActive) statusColor = Colors.blue;

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('STATUS PEMESANAN', style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: AppColors.textSecondary)),
              const SizedBox(height: 4),
              Text(
                booking.statusLabel.toUpperCase(),
                style: GoogleFonts.spaceGrotesk(
                  fontWeight: FontWeight.w900,
                  fontSize: 16,
                  color: statusColor,
                ),
              ),
            ],
          ),
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: statusColor.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(
              booking.isSettled ? Icons.check_circle_rounded : Icons.info_outline_rounded,
              color: statusColor,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildVillaCard(BookingModel booking) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  booking.villa?.nama ?? 'Villa',
                  style: GoogleFonts.spaceGrotesk(fontWeight: FontWeight.w900, fontSize: 18),
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.calendar_today_rounded, size: 14, color: AppColors.primary),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        '${_formatDate(booking.tanggalCheckin)} - ${_formatDate(booking.tanggalCheckout)}',
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: AppColors.textSecondary),
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('${booking.totalHari} Malam', style: const TextStyle(fontWeight: FontWeight.bold)),
                    Text(
                      booking.formattedTotalBiaya,
                      style: GoogleFonts.spaceGrotesk(fontWeight: FontWeight.w900, fontSize: 18, color: AppColors.primary),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}';
    } catch (_) {
      return dateStr;
    }
  }

  Widget _buildPaymentInstructions() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.account_balance_rounded, color: AppColors.secondary),
              const SizedBox(width: 12),
              Text('Instruksi Pembayaran', style: GoogleFonts.spaceGrotesk(fontWeight: FontWeight.bold, fontSize: 16)),
            ],
          ),
          const SizedBox(height: 20),
          const Text(
            'Silakan transfer sesuai nominal ke rekening berikut:',
            style: TextStyle(fontSize: 13, color: AppColors.textSecondary),
          ),
          const SizedBox(height: 16),
          _buildBankDetail('SeaBank', '901880332521'),
          _buildBankDetail('Atas Nama', 'VIREVA VILLA'),
          const SizedBox(height: 20),
          const Text(
            '* Pastikan nominal transfer sesuai dan segera upload bukti pembayaran.',
            style: TextStyle(fontSize: 11, fontStyle: FontStyle.italic, color: Colors.amber),
          ),
        ],
      ),
    );
  }

  Widget _buildBankDetail(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
          Text(value, style: GoogleFonts.spaceGrotesk(fontWeight: FontWeight.w900, color: AppColors.secondary)),
        ],
      ),
    );
  }

  Widget _buildWaitingVerification() {
    return Container(
      padding: const EdgeInsets.all(32),
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.amber.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(AppSpacing.radius),
        border: Border.all(color: Colors.amber.withValues(alpha: 0.2)),
      ),
      child: const Column(
        children: [
          Icon(Icons.hourglass_empty_rounded, size: 48, color: Colors.amber),
          SizedBox(height: 16),
          Text(
            'Menunggu Verifikasi',
            style: TextStyle(fontWeight: FontWeight.w900, color: Colors.amber),
          ),
          SizedBox(height: 8),
          Text(
            'Bukti pembayaran Anda sedang diperiksa oleh admin.',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 12, color: AppColors.textSecondary),
          ),
        ],
      ),
    );
  }

  Widget _buildThermalReceiptView(BookingModel booking) {
    return Container(
      width: 384, // Paksa lebar standard printer 58mm
      padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 10), // Padding tipis saja
      decoration: const BoxDecoration(
        color: Colors.white,
      ),
      child: Column(
        children: [
          // Logo in Receipt
          Image.asset('assets/images/logo.png', height: 60), // Gedein dikit
          const SizedBox(height: 12),
          const Text(
            'VIREVA VILLA',
            style: TextStyle(fontFamily: 'Courier', fontWeight: FontWeight.bold, fontSize: 24), // Gedein
          ),
          const Text(
            'Curahbamban, Tanggul Wetan\n0851-9814-9402',
            textAlign: TextAlign.center,
            style: TextStyle(fontFamily: 'Courier', fontSize: 14), // Gedein
          ),
          const SizedBox(height: 20),
          const Text('================================', style: TextStyle(fontFamily: 'Courier', fontSize: 16)),
          _buildThermalRow('INVOICE', '#VRV-${booking.id.toString().padLeft(5, '0')}'),
          _buildThermalRow('TANGGAL', booking.createdAt?.split('T')[0] ?? '-'),
          _buildThermalRow('TAMU', booking.tamu?.namaTamu ?? 'TAMU'),
          const Text('--------------------------------', style: TextStyle(fontFamily: 'Courier', fontSize: 16)),
          _buildThermalRow('UNIT', booking.villa?.nama ?? '-'),
          _buildThermalRow('DURASI', '${booking.totalHari} MALAM'),
          _buildThermalRow('CHECK-IN', _formatDate(booking.tanggalCheckin)),
          _buildThermalRow('CHECK-OUT', _formatDate(booking.tanggalCheckout)),
          const Text('================================', style: TextStyle(fontFamily: 'Courier', fontSize: 16)),
          const SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('TOTAL', style: TextStyle(fontFamily: 'Courier', fontWeight: FontWeight.bold, fontSize: 18)),
              Text(booking.formattedTotalBiaya, style: const TextStyle(fontFamily: 'Courier', fontWeight: FontWeight.bold, fontSize: 18)),
            ],
          ),
          const SizedBox(height: 4),
          _buildThermalRow('STATUS', 'LUNAS'),
          const SizedBox(height: 30),
           const Text('TERIMA KASIH', style: TextStyle(fontFamily: 'Courier', fontWeight: FontWeight.bold, fontSize: 16)),
          const Text('SIMPAN STRUK SEBAGAI BUKTI', style: TextStyle(fontFamily: 'Courier', fontSize: 10)),
        ],
      ),
    );
  }

  Widget _buildThermalRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(fontFamily: 'Courier', fontSize: 14)),
          Expanded(
            child: Text(
              value,
              textAlign: TextAlign.right,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontFamily: 'Courier', fontSize: 14, fontWeight: FontWeight.bold),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPrintActionButton() {
    return ElevatedButton(
      onPressed: _printReceipt,
      style: ElevatedButton.styleFrom(
        backgroundColor: AppColors.secondary,
        minimumSize: const Size(double.infinity, 56),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
      ),
      child: const Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.print_rounded, color: Colors.white),
          SizedBox(width: 12),
          Text('CETAK STRUK THERMAL', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  Widget _buildBottomAction() {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.p24),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: AppColors.border)),
      ),
      child: ElevatedButton(
        onPressed: _isUploading ? null : _pickAndUploadImage,
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.secondary,
          minimumSize: const Size(double.infinity, 56),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(AppSpacing.radius)),
        ),
        child: _isUploading
            ? const CircularProgressIndicator(color: Colors.white)
            : const Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.upload_rounded, color: Colors.white),
                  SizedBox(width: 12),
                  Text('UPLOAD BUKTI PEMBAYARAN', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                ],
              ),
      ),
    );
  }
}
