import 'package:flutter/material.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../auth/login_screen.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _controller = PageController();
  bool isLastPage = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          PageView(
            controller: _controller,
            onPageChanged: (index) {
              setState(() => isLastPage = index == 2);
            },
            children: [
              _buildPage(
                color: Colors.white,
                title: 'Temukan Villa Impian Anda',
                subtitle: 'Pilih koleksi villa mewah terbaik dengan fasilitas kelas dunia.',
                image: Icons.house_rounded,
              ),
              _buildPage(
                color: Colors.white,
                title: 'Booking Mudah & Cepat',
                subtitle: 'Proses reservasi instan tanpa ribet dalam satu genggaman.',
                image: Icons.calendar_today_rounded,
              ),
              _buildPage(
                color: Colors.white,
                title: 'Pelayanan Eksklusif',
                subtitle: 'Nikmati kenyamanan maksimal dengan layanan manajemen villa profesional.',
                image: Icons.verified_user_rounded,
              ),
            ],
          ),
          
          // Navigation
          Container(
            alignment: const Alignment(0, 0.85),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                // Skip
                TextButton(
                  onPressed: () => _controller.jumpToPage(2),
                  child: const Text('Lewati', style: TextStyle(color: Color(0xFF64748B))),
                ),
                
                // Indicator
                SmoothPageIndicator(
                  controller: _controller,
                  count: 3,
                  effect: const ExpandingDotsEffect(
                    activeDotColor: Color(0xFF10B981),
                    dotColor: Color(0xFFE2E8F0),
                    dotHeight: 8,
                    dotWidth: 8,
                  ),
                ),
                
                // Next or Get Started
                isLastPage 
                ? TextButton(
                    onPressed: () async {
                      final prefs = await SharedPreferences.getInstance();
                      await prefs.setBool('showHome', true);
                      
                      if (context.mounted) {
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(builder: (context) => const LoginScreen()),
                        );
                      }
                    },
                    child: const Text('Mulai', style: TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF10B981))),
                  )
                : TextButton(
                    onPressed: () => _controller.nextPage(
                      duration: const Duration(milliseconds: 500),
                      curve: Curves.easeInOut,
                    ),
                    child: const Text('Lanjut', style: TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF0F172A))),
                  ),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildPage({
    required Color color,
    required String title,
    required String subtitle,
    required IconData image,
  }) {
    return Container(
      color: color,
      padding: const EdgeInsets.all(40),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(30),
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              borderRadius: BorderRadius.circular(30),
            ),
            child: Icon(image, size: 100, color: const Color(0xFF0F172A)),
          ),
          const SizedBox(height: 64),
          Text(
            title,
            textAlign: TextAlign.center,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 24),
          Text(
            subtitle,
            textAlign: TextAlign.center,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16,
              color: const Color(0xFF64748B),
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }
}
