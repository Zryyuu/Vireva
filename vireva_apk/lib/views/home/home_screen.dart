import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/app_constants.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../viewmodels/villa_viewmodel.dart';
import '../../viewmodels/admin_viewmodel.dart';
import '../../viewmodels/booking_viewmodel.dart';
import 'widgets/beranda_tab.dart';
import 'widgets/explore_tab.dart';
import 'widgets/admin_dashboard.dart';
import 'widgets/booking_history_list.dart';
import '../profile/profile_screen.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      final auth = ref.read(authViewModelProvider);
      if (auth.user?.isUser ?? true) {
        ref.read(villaViewModelProvider.notifier).fetchVillas();
        ref.read(bookingViewModelProvider.notifier).fetchBookings();
      } else {
        ref.read(adminViewModelProvider.notifier).fetchBookings();
        ref.read(villaViewModelProvider.notifier).fetchVillas();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final auth = ref.watch(authViewModelProvider);
    
    if (!auth.isAuthenticated) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(color: AppColors.primary),
        ),
      );
    }

    if (auth.user?.isAdmin ?? false) {
      return Scaffold(
        backgroundColor: AppColors.background,
        body: const SafeArea(child: AdminDashboard()),
      );
    }

    final List<Widget> guestScreens = [
      const BerandaTab(),
      const ExploreTab(),
      const BookingHistoryList(),
      const ProfileScreen(),
    ];

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: guestScreens[_currentIndex],
      ),
      bottomNavigationBar: Container(
        decoration: const BoxDecoration(
          border: Border(top: BorderSide(color: AppColors.border, width: 1)),
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          onTap: (index) => setState(() => _currentIndex = index),
          backgroundColor: Colors.white,
          elevation: 0,
          selectedItemColor: AppColors.primary,
          unselectedItemColor: AppColors.textSecondary,
          selectedLabelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
          unselectedLabelStyle: const TextStyle(fontSize: 12),
          type: BottomNavigationBarType.fixed,
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.dashboard_outlined),
              activeIcon: Icon(Icons.dashboard_rounded),
              label: 'BERANDA',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.explore_outlined),
              activeIcon: Icon(Icons.explore_rounded),
              label: 'JELAJAH',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.history_outlined),
              activeIcon: Icon(Icons.history_rounded),
              label: 'PESANANKU',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.person_outline),
              activeIcon: Icon(Icons.person_rounded),
              label: 'PROFIL',
            ),
          ],
        ),
      ),
    );
  }
}
