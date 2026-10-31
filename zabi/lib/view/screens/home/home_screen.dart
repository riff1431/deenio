import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:zabi/controller/home_layout_controller.dart';
import 'package:zabi/controller/internet_check_controller.dart';
import 'package:zabi/controller/package_prayer_time_controller.dart';
import 'package:zabi/controller/prayer_time_adjustment.dart';
import 'package:zabi/controller/quran_settings_controller.dart';
import 'package:zabi/helper/salat_waqt_service.dart';
import 'package:zabi/util/app_constants.dart';
import 'package:zabi/view/screens/home/classic/classic_home_screen.dart';
import 'package:zabi/view/screens/home/modern/modern_home_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    _loadInitialData();
  }

  void _loadInitialData() {
    try {
      Get.find<InternetController>().checkConnection();
    } catch (_) {}
    try {
      Get.find<SettingsController>().fetchMosqueSettingsData();
    } catch (_) {}

    WidgetsBinding.instance.addPostFrameCallback((_) async {
      final prayerTimeController = Get.find<PrayerTimeController>();

      try {
        prayerTimeController.loadSwitchValue();
        prayerTimeController.loadPrayerTimeSettings();
      } catch (_) {}

      try {
        Get.find<SettingsController>().fetchMosqueSettingsData();
      } catch (_) {}

      try {
        await prayerTimeController.getLocation();
      } catch (e) {
        debugPrint('Error getting location: $e');
      }

      try {
        await _fetchPrayerTimes(prayerTimeController);
      } catch (e) {
        debugPrint('Error fetching prayer times: $e');
      }

      try {
        Get.find<PrayerTimeAdjustmentController>().init();
        SalatWaqtService.initializeSalatWaqt();
      } catch (e) {
        debugPrint('Error initializing salat adjustment: $e');
      }
    });
  }

  Future<void> _fetchPrayerTimes(
    PrayerTimeController prayerTimeController,
  ) async {
    final prefs = Get.find<SharedPreferences>();

    final isPrayerTme = prefs.getBool(AppConstants.isPrayerTme);
    final saveCityName = prefs.getString(AppConstants.saveCityName);

    await prayerTimeController.fetchPrayerTime(
      isManualPrayerTme: isPrayerTme ?? false,
      manualCity:
          saveCityName ?? prayerTimeController.currentAddress.toString(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Obx(() {
      final layout = Get.find<HomeLayoutController>().currentLayout.value;
      return AnimatedSwitcher(
        duration: const Duration(milliseconds: 400),
        transitionBuilder: (child, animation) => FadeTransition(
          opacity: animation,
          child: SlideTransition(
            position:
                Tween<Offset>(
                  begin: const Offset(0, 0.03),
                  end: Offset.zero,
                ).animate(
                  CurvedAnimation(parent: animation, curve: Curves.easeOut),
                ),
            child: child,
          ),
        ),
        child: layout == HomeLayoutController.classic
            ? const ClassicHomeScreen(key: ValueKey('classic'))
            : ModernHomeScreen(key: ValueKey('modern')),
      );
    });
  }
}
