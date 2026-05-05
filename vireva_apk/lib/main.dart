import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'core/app_theme.dart';
import 'providers/auth_provider.dart';
import 'views/onboarding/onboarding_screen.dart';
import 'views/auth/login_screen.dart';
import 'views/home/home_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final prefs = await SharedPreferences.getInstance();
  final showHome = prefs.getBool('showHome') ?? false;

  runApp(
    ProviderScope(
      child: MyApp(showHome: showHome),
    ),
  );
}

class MyApp extends ConsumerStatefulWidget {
  final bool showHome;
  
  const MyApp({super.key, required this.showHome});

  @override
  ConsumerState<MyApp> createState() => _MyAppState();
}

class _MyAppState extends ConsumerState<MyApp> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(authProvider.notifier).checkAuth();
    });
  }

  @override
  Widget build(BuildContext context) {
    final auth = ref.watch(authProvider);
    
    return MaterialApp(
      key: ValueKey(auth.isAuthenticated),
      title: 'Vireva Luxury',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: auth.isAuthenticated 
          ? const HomeScreen() 
          : (!widget.showHome ? const OnboardingScreen() : const LoginScreen()),
    );
  }
}
