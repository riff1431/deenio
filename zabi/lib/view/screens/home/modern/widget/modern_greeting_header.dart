// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:hijri/hijri_calendar.dart';
import 'package:intl/intl.dart';
import 'package:zabi/controller/package_prayer_time_controller.dart';
import 'package:zabi/controller/theme_controller.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/util/app_constants.dart';
import 'package:zabi/util/dimensions.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';

class ModernGreetingHeader extends StatelessWidget {
  final dynamic mosqueData;
  const ModernGreetingHeader({super.key, required this.mosqueData});

  String get _greeting {
    final hour = DateTime.now().hour;
    if (hour < 5) return 'tahajjud_time'.tr.isNotEmpty ? 'tahajjud_time'.tr : 'good_night'.tr;
    if (hour < 12) return 'good_morning'.tr;
    if (hour < 17) return 'good_afternoon'.tr;
    return 'good_evening'.tr;
  }

  String get _hijriDate {
    try {
      final hijri = HijriCalendar.fromDate(DateTime.now());
      return '${hijri.hDay} ${hijri.longMonthName} ${hijri.hYear} AH';
    } catch (_) {
      return DateFormat('EEEE, d MMM').format(DateTime.now());
    }
  }

  String get _gregorianDate {
    return DateFormat('EEE, d MMM').format(DateTime.now());
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final prayerTimeController = Get.find<PrayerTimeController>();

    final city = prayerTimeController.saveLocalStoreCity ??
        (prayerTimeController.currentAddress.isNotEmpty == true
            ? prayerTimeController.currentAddress.toString()
            : 'location'.tr);

    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: isDark
              ? [
                  const Color(0xFF0D1812),
                  const Color(0xFF14241B),
                  const Color(0xFF1A3326),
                ]
              : [
                  const Color(0xFF144D35),
                  const Color(0xFF1E6647),
                  const Color(0xFF2E7D5B),
                ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: const BorderRadius.only(
          bottomLeft: Radius.circular(32),
          bottomRight: Radius.circular(32),
        ),
        boxShadow: [
          BoxShadow(
            color: isDark
                ? Colors.black.withOpacity(0.4)
                : const Color(0xFF1B5E3F).withOpacity(0.25),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Stack(
        children: [
          // Mosque cover illustration — right side of header
          Positioned(
            right: 0,
            bottom: 0,
            top: 0,
            child: Opacity(
              opacity: isDark ? 0.45 : 0.65,
              child: Image.asset(
                Images.ModernIllustration_MosqueHeader,
                fit: BoxFit.fitHeight,
                errorBuilder: (_, _, _) => const SizedBox.shrink(),
              ),
            ),
          ),

          // Gradient fade overlay so text stays readable
          Positioned(
            left: 0,
            top: 0,
            bottom: 0,
            right: 0,
            child: DecoratedBox(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    isDark
                        ? const Color(0xFF0D1812)
                        : const Color(0xFF144D35),
                    (isDark
                            ? const Color(0xFF0D1812)
                            : const Color(0xFF144D35))
                        .withOpacity(0.6),
                    Colors.transparent,
                  ],
                  stops: const [0.0, 0.45, 1.0],
                  begin: Alignment.centerLeft,
                  end: Alignment.centerRight,
                ),
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(32),
                  bottomRight: Radius.circular(32),
                ),
              ),
            ),
          ),

          SafeArea(
            bottom: false,
            child: Padding(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 42),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // 1. Top Bar: Deenio Brand Logo & Actions
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      // Brand Logo & Name
                      Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Container(
                            height: 38,
                            width: 38,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.12),
                                  blurRadius: 8,
                                  offset: const Offset(0, 2),
                                ),
                              ],
                            ),
                            padding: const EdgeInsets.all(4),
                            child: Image.asset(
                              Images.Light_APP_LOGO,
                              fit: BoxFit.contain,
                            ),
                          ),
                          const SizedBox(width: 10),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                AppConstants.APP_NAME,
                                style: robotoBold.copyWith(
                                  color: Colors.white,
                                  fontSize: 20,
                                  letterSpacing: 0.5,
                                ),
                              ),
                              Text(
                                'deen_companion'.tr == 'deen_companion'
                                    ? 'Islamic Companion'
                                    : 'deen_companion'.tr,
                                style: robotoRegular.copyWith(
                                  color: Colors.white.withOpacity(0.75),
                                  fontSize: 10,
                                  letterSpacing: 0.4,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),

                      // Actions (Theme switch + Settings)
                      Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          _HeaderGlassButton(
                            icon: isDark
                                ? Icons.wb_sunny_rounded
                                : Icons.nightlight_round,
                            tooltip: 'light_or_dark_mode'.tr,
                            onTap: () =>
                                Get.find<ThemeController>().toggleTheme(),
                          ),
                          const SizedBox(width: 8),
                          _HeaderGlassButton(
                            icon: Icons.settings_outlined,
                            tooltip: 'settings'.tr,
                            onTap: () => Get.toNamed(RouteHelper.settings),
                          ),
                        ],
                      ),
                    ],
                  ),

                  const SizedBox(height: 20),

                  // 2. Greeting & Spiritual Salutation
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _greeting,
                              style: robotoMedium.copyWith(
                                fontSize: Dimensions.FONT_SIZE_DEFAULT,
                                color: Colors.white.withOpacity(0.85),
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              (mosqueData?.mosqueName ?? 'Assalamu Alaikum').toString(),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: robotoBold.copyWith(
                                fontSize: 24,
                                color: Colors.white,
                                height: 1.1,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'may_allah_bless_you'.tr,
                              style: robotoRegular.copyWith(
                                fontSize: Dimensions.FONT_SIZE_SMALL,
                                color: Colors.white.withOpacity(0.75),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),

                  const SizedBox(height: 16),

                  // 3. Location & Islamic Calendar Strip
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      // Location Chip
                      GestureDetector(
                        onTap: () async {
                          await prayerTimeController.getLocation();
                        },
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 5,
                          ),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.14),
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(
                              color: Colors.white.withOpacity(0.18),
                              width: 0.8,
                            ),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              const Icon(
                                Icons.location_on_rounded,
                                size: 14,
                                color: Colors.white,
                              ),
                              const SizedBox(width: 5),
                              ConstrainedBox(
                                constraints: const BoxConstraints(maxWidth: 140),
                                child: Text(
                                  city,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: robotoMedium.copyWith(
                                    fontSize: Dimensions.FONT_SIZE_SMALL,
                                    color: Colors.white,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),

                      // Hijri & Gregorian Date Chip
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 10,
                          vertical: 5,
                        ),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.14),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(
                            color: Colors.white.withOpacity(0.18),
                            width: 0.8,
                          ),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Icon(
                              Icons.calendar_month_rounded,
                              size: 14,
                              color: Colors.white,
                            ),
                            const SizedBox(width: 5),
                            Text(
                              '$_hijriDate • $_gregorianDate',
                              style: robotoMedium.copyWith(
                                fontSize: Dimensions.FONT_SIZE_SMALL,
                                color: Colors.white,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _HeaderGlassButton extends StatelessWidget {
  final IconData icon;
  final String tooltip;
  final VoidCallback onTap;

  const _HeaderGlassButton({
    required this.icon,
    required this.tooltip,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: tooltip,
      child: InkWell(
        borderRadius: BorderRadius.circular(20),
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(0.18),
            shape: BoxShape.circle,
            border: Border.all(
              color: Colors.white.withOpacity(0.25),
              width: 1,
            ),
          ),
          child: Icon(icon, size: 18, color: Colors.white),
        ),
      ),
    );
  }
}
