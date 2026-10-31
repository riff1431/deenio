// ignore_for_file: deprecated_member_use

import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:zabi/controller/package_prayer_time_controller.dart';
import 'package:zabi/controller/quran_settings_controller.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';
import 'package:zabi/view/screens/ai_islamic_assistant/ai_islamic_assistant.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_greeting_header.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_info_cards.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_next_prayer_card.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_quick_actions.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_quran_cta_banner.dart';
import 'package:zabi/view/screens/home/modern/widget/modern_quran_milestone_card.dart';

class ModernHomeScreen extends StatelessWidget {
  const ModernHomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return GetBuilder<SettingsController>(
      builder: (settingsController) {
        return GetBuilder<PrayerTimeController>(
          builder: (prayerTimeController) {
            final isLoading = _isLoadingState(
              settingsController,
              prayerTimeController,
            );
            final mosqueData = settingsController.mosqueSettingsApiData?.data;

            return Scaffold(
              extendBodyBehindAppBar: true,
              backgroundColor: isDark
                  ? const Color(0xFF0F1713)
                  : const Color(0xFFF6F8F6),
              body: isLoading
                  ? const SafeArea(child: DashbordShimmerScreen())
                  : _buildBody(context, mosqueData, prayerTimeController),
              floatingActionButton: _buildAiFab(context),
              floatingActionButtonLocation:
                  FloatingActionButtonLocation.endFloat,
            );
          },
        );
      },
    );
  }

  bool _isLoadingState(
    SettingsController settingsController,
    PrayerTimeController prayerTimeController,
  ) {
    return settingsController.isMosqueSettingsLoading.value &&
        settingsController.mosqueSettingsApiData == null;
  }

  Widget _buildBody(
    BuildContext context,
    dynamic mosqueData,
    PrayerTimeController prayerTimeController,
  ) {
    return SingleChildScrollView(
      physics: const ClampingScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          ModernGreetingHeader(mosqueData: mosqueData),
          Transform.translate(
            offset: const Offset(0, -22),
            child: Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: 16,
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  ModernNextPrayerCard(
                    prayerTimeController: prayerTimeController,
                  ),
                  const SizedBox(height: 14),
                  const ModernQuranMilestoneCard(),
                  const SizedBox(height: 14),
                  const ModernQuickActions(),
                  const SizedBox(height: 14),
                  const IslamicFeatureGrid(),
                  const SizedBox(height: 14),
                  const ModernQuranCtaBanner(),
                  const SizedBox(height: 130),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAiFab(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return GestureDetector(
      onTap: () => Get.to(() => const AiIslamicAssistantScreen()),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(28),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(28),
              gradient: LinearGradient(
                colors: isDark
                    ? [
                        const Color(0xFF1E5B3D).withOpacity(0.9),
                        const Color(0xFF14402A).withOpacity(0.9),
                      ]
                    : [
                        AppColorModern.emerald,
                        AppColorModern.emeraldDark,
                      ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              border: Border.all(
                color: Colors.white.withOpacity(0.25),
                width: 1,
              ),
              boxShadow: [
                BoxShadow(
                  color: AppColorModern.emerald.withOpacity(0.35),
                  blurRadius: 16,
                  offset: const Offset(0, 6),
                ),
              ],
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 28,
                  height: 28,
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.2),
                    shape: BoxShape.circle,
                  ),
                  alignment: Alignment.center,
                  child: Image.asset(Images.aiAssistant, width: 18, height: 18),
                ),
                const SizedBox(width: 8),
                Text(
                  'ask_ai_key'.tr.isNotEmpty ? 'ask_ai_key'.tr : 'Ask AI',
                  style: robotoBold.copyWith(
                    color: Colors.white,
                    fontSize: 13,
                    letterSpacing: 0.3,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
