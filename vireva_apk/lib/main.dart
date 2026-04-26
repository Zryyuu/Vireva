import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:provider/provider.dart';
import 'core/app_theme.dart';
import 'providers/auth_provider.dart';
import 'providers/villa_provider.dart';
import 'providers/booking_provider.dart';
import 'providers/admin_provider.dart';
import 'views/onboarding/onboarding_screen.dart';
import 'views/auth/login_screen.dart';
import 'views/home/home_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final prefs = await SharedPreferences.getInstance();
  final showHome = prefs.getBool('showHome') ?? false;

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()..checkAuth()),
        ChangeNotifierProvider(create: (_) => VillaProvider()),
        ChangeNotifierProvider(create: (_) => BookingProvider()),
        ChangeNotifierProvider(create: (_) => AdminProvider()),
      ],
      child: MyApp(showHome: showHome),
    ),
  );
}

class MyApp extends StatelessWidget {
  final bool showHome;
  
  const MyApp({super.key, required this.showHome});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    
    return MaterialApp(
      title: 'Vireva Luxury',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: !showHome 
          ? const OnboardingScreen() 
          : (auth.isAuthenticated ? const HomeScreen() : const LoginScreen()),
    );
  }
}
