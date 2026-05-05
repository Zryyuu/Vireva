import 'package:flutter/material.dart';

class AppColors {
  // Brand Colors - High-End Emerald Theme
  static const Color primary = Color(0xFF059669); // Emerald 600
  static const Color primaryDark = Color(0xFF064E3B); // Emerald 900
  static const Color primaryLight = Color(0xFFD1FAE5); // Emerald 100
  
  static const Color secondary = Color(0xFF0F172A); // Slate 900
  static const Color accent = Color(0xFF10B981); // Emerald 500
  
  // Neutral Palette
  static const Color background = Color(0xFFF8FAFC); // Very light slate
  static const Color surface = Color(0xFFFFFFFF);
  static const Color card = Color(0xFFFFFFFF);
  
  static const Color textPrimary = Color(0xFF0F172A); // Slate 900
  static const Color textSecondary = Color(0xFF475569); // Slate 600
  static const Color textMuted = Color(0xFF94A3B8); // Slate 400
  
  static const Color border = Color(0xFFE2E8F0); // Slate 200
  static const Color white = Colors.white;
  static const Color error = Color(0xFFEF4444); // Red 500
  static const Color success = Color(0xFF10B981); // Emerald 500

  // Gradients
  static const Gradient emeraldGradient = LinearGradient(
    colors: [Color(0xFF059669), Color(0xFF10B981)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const Gradient darkGradient = LinearGradient(
    colors: [Color(0xFF0F172A), Color(0xFF1E293B)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}

class AppSpacing {
  static const double p4 = 4.0;
  static const double p8 = 8.0;
  static const double p12 = 12.0;
  static const double p16 = 16.0;
  static const double p20 = 20.0;
  static const double p24 = 24.0;
  static const double p32 = 32.0;
  static const double p48 = 48.0;
  
  static const double radius = 28.0; // Extra rounded for premium feel
  static const double radiusSm = 14.0;
  static const double radiusLg = 40.0;
}
