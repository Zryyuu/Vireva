import 'package:flutter/material.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../core/app_constants.dart';
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
    final theme = Theme.of(context);
    
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
                theme: theme,
                title: 'Temukan Villa\nImpian Anda',
                subtitle: 'Pilih koleksi villa mewah terbaik dengan fasilitas kelas dunia.',
                icon: Icons.house_rounded,
              ),
              _buildPage(
                theme: theme,
                title: 'Booking Mudah\n& Cepat',
                subtitle: 'Proses reservasi instan tanpa ribet dalam satu genggaman.',
                icon: Icons.calendar_today_rounded,
              ),
              _buildPage(
                theme: theme,
                title: 'Pelayanan\nEksklusif',
                subtitle: 'Nikmati kenyamanan maksimal dengan layanan manajemen villa profesional.',
                icon: Icons.verified_user_rounded,
              ),
            ],
          ),
          
          Positioned(
            bottom: AppSpacing.p48,
            left: AppSpacing.p24,
            right: AppSpacing.p24,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                SmoothPageIndicator(
                  controller: _controller,
                  count: 3,
                  effect: const ExpandingDotsEffect(
                    activeDotColor: AppColors.primary,
                    dotColor: AppColors.border,
                    dotHeight: 8,
                    dotWidth: 8,
                    spacing: 8,
                  ),
                ),
                
                GestureDetector(
                  onTap: () async {
                    if (isLastPage) {
                      final prefs = await SharedPreferences.getInstance();
                      await prefs.setBool('showHome', true);
                      if (context.mounted) {
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(builder: (context) => const LoginScreen()),
                        );
                      }
                    } else {
                      _controller.nextPage(
                        duration: const Duration(milliseconds: 500),
                        curve: Curves.easeInOut,
                      );
                    }
                  },
                  child: Container(
                    padding: const EdgeInsets.all(AppSpacing.p16),
                    decoration: const BoxDecoration(
                      color: AppColors.primary,
                      shape: BoxShape.circle,
                    ),
                    child: Icon(
                      isLastPage ? Icons.check : Icons.arrow_forward_ios_rounded,
                      color: Colors.white,
                      size: 20,
                    ),
                  ),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildPage({
    required ThemeData theme,
    required String title,
    required String subtitle,
    required IconData icon,
  }) {
    return Container(
      padding: const EdgeInsets.all(AppSpacing.p32),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            height: 280,
            width: double.infinity,
            decoration: BoxDecoration(
              color: AppColors.surface,
              borderRadius: BorderRadius.circular(AppSpacing.radiusLg),
              border: Border.all(color: AppColors.border, width: 1),
            ),
            child: Icon(icon, size: 120, color: AppColors.primary),
          ),
          const SizedBox(height: AppSpacing.p48),
          Text(
            title,
            textAlign: TextAlign.center,
            style: theme.textTheme.displayLarge,
          ),
          const SizedBox(height: AppSpacing.p24),
          Text(
            subtitle,
            textAlign: TextAlign.center,
            style: theme.textTheme.bodyLarge?.copyWith(
              color: AppColors.textSecondary,
              height: 1.6,
            ),
          ),
        ],
      ),
    );
  }
}
