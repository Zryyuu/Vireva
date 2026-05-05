import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../core/app_constants.dart';

class PaymentScreen extends StatefulWidget {
  final String snapToken;
  final Function(bool success) onFinish;

  const PaymentScreen({
    super.key,
    required this.snapToken,
    required this.onFinish,
  });

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  late final WebViewController _controller;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    
    // URL Snap Midtrans (Sandbox)
    final String snapUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/${widget.snapToken}';

    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
            setState(() => _isLoading = true);
          },
          onPageFinished: (String url) {
            setState(() => _isLoading = false);
            
            // Deteksi jika pembayaran selesai (berdasarkan URL redirect Midtrans)
            if (url.contains('finish') || url.contains('success')) {
              widget.onFinish(true);
            } else if (url.contains('error') || url.contains('failed')) {
              widget.onFinish(false);
            }
          },
        ),
      )
      ..loadRequest(Uri.parse(snapUrl));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Pembayaran Villa',
          style: GoogleFonts.spaceGrotesk(
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close, color: AppColors.primary),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: Stack(
        children: [
          WebViewWidget(controller: _controller),
          if (_isLoading)
            const Center(
              child: CircularProgressIndicator(color: AppColors.accent),
            ),
        ],
      ),
    );
  }
}
