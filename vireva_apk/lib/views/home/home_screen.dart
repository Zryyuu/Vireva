import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/app_constants.dart';
import '../../providers/auth_provider.dart';
import '../../providers/villa_provider.dart';
import '../../providers/admin_provider.dart';
import '../../providers/booking_provider.dart';
import 'widgets/guest_dashboard.dart';
import 'widgets/admin_dashboard.dart';
import 'widgets/booking_history_list.dart';

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
      final auth = ref.read(authProvider);
      if (auth.user?.role == 'tamu') {
        ref.read(villaProvider.notifier).fetchVillas();
        ref.read(bookingProvider.notifier).fetchBookings();
      } else {
        ref.read(adminProvider.notifier).fetchStats();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final auth = ref.watch(authProvider);
    
    if (!auth.isAuthenticated) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(color: AppColors.primary),
        ),
      );
    }

    final role = auth.user?.role ?? 'tamu';
    
    if (role != 'tamu') {
      return Scaffold(
        backgroundColor: AppColors.background,
        body: const SafeArea(child: AdminDashboard()),
      );
    }

    final List<Widget> guestScreens = [
      const GuestDashboard(),
      const BookingHistoryList(),
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
              icon: Icon(Icons.explore_outlined),
              activeIcon: Icon(Icons.explore_rounded),
              label: 'JELAJAH',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.history_outlined),
              activeIcon: Icon(Icons.history_rounded),
              label: 'PESANANKU',
            ),
          ],
        ),
      ),
    );
  }
}
