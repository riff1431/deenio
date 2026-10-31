// ignore_for_file: deprecated_member_use

import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:zabi/controller/package_prayer_time_controller.dart';
import 'package:zabi/helper/date_converter.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/helper/translator_helper.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/dimensions.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';

class ModernNextPrayerCard extends StatefulWidget {
  final PrayerTimeController prayerTimeController;
  const ModernNextPrayerCard({super.key, required this.prayerTimeController});

  @override
  State<ModernNextPrayerCard> createState() => _ModernNextPrayerCardState();
}

class _ModernNextPrayerCardState extends State<ModernNextPrayerCard> {
  Timer? _ticker;
  Duration _remaining = Duration.zero;
  int? _lastActiveIndex;

  @override
  void initState() {
    super.initState();
    _recompute();
    _ticker = Timer.periodic(const Duration(seconds: 1), (_) => _recompute());
  }

  @override
  void dispose() {
    _ticker?.cancel();
    super.dispose();
  }

  void _recompute() {

    final prayerData = widget.prayerTimeController.prayerTimeModel?.data;
    final times = [
      prayerData?.fajrStart,
      prayerData?.zuhrStart,
      prayerData?.asrStart,
      prayerData?.maghribStart,
      prayerData?.ishaStart,
    ];
    final activeIndex = _activePrayerIndex(times);


    final timeStr = (times.isNotEmpty && times[activeIndex] != null)
        ? times[activeIndex]!
        : widget.prayerTimeController.currentWaktTime.value;
    try {
      final now = DateTime.now();
      var target = DateFormat('HH:mm').parse(timeStr);
      target = DateTime(
        now.year,
        now.month,
        now.day,
        target.hour,
        target.minute,
      );
      if (target.isBefore(now)) target = target.add(const Duration(days: 1));
      if (mounted) setState(() => _remaining = target.difference(now));
    } catch (_) {
      if (mounted) setState(() => _remaining = Duration.zero);
    }
  }

  String get _countdownText {
    final h = _remaining.inHours;
    final m = _remaining.inMinutes % 60;
    final s = _remaining.inSeconds % 60;
    if (h > 0) {
      return '${h.toString().padLeft(2, '0')}h ${m.toString().padLeft(2, '0')}m ${s.toString().padLeft(2, '0')}s';
    }
    return '${m.toString().padLeft(2, '0')}m ${s.toString().padLeft(2, '0')}s';
  }

  int _activePrayerIndex(List<String?> times) {
    final now = DateTime.now();
    for (var i = 0; i < times.length; i++) {
      final t = times[i];
      if (t == null) continue;
      try {
        final parsed = DateFormat('HH:mm').parse(t);
        final today = DateTime(
          now.year,
          now.month,
          now.day,
          parsed.hour,
          parsed.minute,
        );
        if (today.isAfter(now)) return i;
      } catch (_) {
        continue;
      }
    }
    return 0;
  }

  _ChipStatus _chipStatus(int index, int activeIndex) {
    if (index < activeIndex) return _ChipStatus.passed;
    if (index == activeIndex) return _ChipStatus.active;
    return _ChipStatus.upcoming;
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final controller = widget.prayerTimeController;
    final is24HourFormat = controller.is24HourFormat.value;
    final prayerData = controller.prayerTimeModel?.data;

    final times = [
      prayerData?.fajrStart,
      prayerData?.zuhrStart,
      prayerData?.asrStart,
      prayerData?.maghribStart,
      prayerData?.ishaStart,
    ];
    final activeIndex = _activePrayerIndex(times);



    final prayerNames = [
      'fajr'.tr,
      (prayerData?.isJumma == true || DateTime.now().weekday == DateTime.friday)
          ? 'jumuah'.tr
          : 'dhuhr'.tr,
      'asr'.tr,
      'magrib'.tr,
      'isha'.tr,
    ];
    final currentWaqt = prayerNames[activeIndex];




    final nextFormattedTime = DateConverter.formatPrayerTime(
      (times[activeIndex] ?? controller.currentWaktTime.value),
      is24HourFormat,
    );

    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: isDark
              ? [
                  const Color(0xFF162B1F),
                  const Color(0xFF1E3A2B),
                  const Color(0xFF264C38),
                ]
              : [
                  const Color(0xFF1B5E3F),
                  const Color(0xFF26734E),
                  const Color(0xFF2E7D5B),
                ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: Colors.white.withOpacity(isDark ? 0.1 : 0.15),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: isDark
                ? Colors.black.withOpacity(0.4)
                : const Color(0xFF1B5E3F).withOpacity(0.28),
            blurRadius: 18,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. Top Section: Next Prayer Header & Live Countdown Pill
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 16, 16, 12),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // Next Prayer badge
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.18),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(
                          color: Colors.white.withOpacity(0.25),
                          width: 0.8,
                        ),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Container(
                            width: 6,
                            height: 6,
                            decoration: const BoxDecoration(
                              shape: BoxShape.circle,
                              color: Color(0xFF55E69C),
                            ),
                          ),
                          const SizedBox(width: 6),
                          Text(
                            'next_prayer'.tr.toUpperCase(),
                            style: robotoBold.copyWith(
                              color: Colors.white,
                              fontSize: 10,
                              letterSpacing: 0.8,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      currentWaqt,
                      style: robotoBold.copyWith(
                        color: Colors.white,
                        fontSize: 18,
                      ),
                    ),
                  ],
                ),

                // Countdown Pill & Adhan Bell
                Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.black.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          const Icon(
                            Icons.timer_outlined,
                            size: 13,
                            color: Colors.white70,
                          ),
                          const SizedBox(width: 4),
                          Text(
                            translateText(_countdownText),
                            style: robotoMedium.copyWith(
                              color: Colors.white,
                              fontSize: 12,
                              letterSpacing: 0.5,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 8),
                    // Adhan sound toggle
                    GestureDetector(
                      onTap: () => Get.toNamed(RouteHelper.settings),
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.16),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.notifications_active_outlined,
                          size: 15,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          // 2. Center: Big Prayer Time Display & Adhan Time
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 18),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              crossAxisAlignment: CrossAxisAlignment.baseline,
              textBaseline: TextBaseline.alphabetic,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.baseline,
                  textBaseline: TextBaseline.alphabetic,
                  children: [
                    Text(
                      translateText(nextFormattedTime),
                      style: robotoBold.copyWith(
                        color: Colors.white,
                        fontSize: 34,
                        letterSpacing: -0.5,
                        height: 1.0,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      'adhan'.tr,
                      style: robotoRegular.copyWith(
                        color: Colors.white.withOpacity(0.7),
                        fontSize: Dimensions.FONT_SIZE_SMALL,
                      ),
                    ),
                  ],
                ),
                GestureDetector(
                  onTap: () => Get.toNamed(RouteHelper.prayerAdjustment),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        'prayer_times'.tr,
                        style: robotoMedium.copyWith(
                          color: Colors.white.withOpacity(0.85),
                          fontSize: Dimensions.FONT_SIZE_EXTRA_SMALL,
                        ),
                      ),
                      const SizedBox(width: 2),
                      const Icon(
                        Icons.chevron_right_rounded,
                        size: 16,
                        color: Colors.white70,
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 14),

          // Divider
          Container(
            height: 1,
            margin: const EdgeInsets.symmetric(horizontal: 16),
            color: Colors.white.withOpacity(0.12),
          ),

          // 3. 5-Prayer Daily Row
          Padding(
            padding: const EdgeInsets.fromLTRB(12, 12, 12, 14),
            child: Row(
              children: [
                Expanded(
                  child: _PrayerItem(
                    icon: Images.ModernPrayer_FajrSunrise,
                    name: 'fajr'.tr,
                    time: prayerData?.fajrStart ?? '00:00',
                    is24HourFormat: is24HourFormat,
                    status: _chipStatus(0, activeIndex),
                  ),
                ),
                Expanded(
                  child: _PrayerItem(
                    icon: Images.ModernPrayer_DhuhrSun,
                    name: prayerData?.isJumma == true
                        ? 'jumuah'.tr
                        : 'dhuhr'.tr,
                    time: prayerData?.zuhrStart ?? '00:00',
                    is24HourFormat: is24HourFormat,
                    status: _chipStatus(1, activeIndex),
                  ),
                ),
                Expanded(
                  child: _PrayerItem(
                    icon: Images.ModernPrayer_AsrCloudy,
                    name: 'asr'.tr,
                    time: prayerData?.asrStart ?? '00:00',
                    is24HourFormat: is24HourFormat,
                    status: _chipStatus(2, activeIndex),
                  ),
                ),
                Expanded(
                  child: _PrayerItem(
                    icon: Images.ModernPrayer_MaghribSunset,
                    name: 'magrib'.tr,
                    time: prayerData?.maghribStart ?? '00:00',
                    is24HourFormat: is24HourFormat,
                    status: _chipStatus(3, activeIndex),
                  ),
                ),
                Expanded(
                  child: _PrayerItem(
                    icon: Images.ModernPrayer_IshaMoon,
                    name: 'isha'.tr,
                    time: prayerData?.ishaStart ?? '00:00',
                    is24HourFormat: is24HourFormat,
                    status: _chipStatus(4, activeIndex),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

enum _ChipStatus { passed, active, upcoming }

class _PrayerItem extends StatelessWidget {
  final String icon;
  final String name;
  final String time;
  final bool is24HourFormat;
  final _ChipStatus status;

  const _PrayerItem({
    required this.icon,
    required this.name,
    required this.time,
    required this.is24HourFormat,
    required this.status,
  });

  @override
  Widget build(BuildContext context) {
    final isActive = status == _ChipStatus.active;
    final isPassed = status == _ChipStatus.passed;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 3),
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 4),
      decoration: BoxDecoration(
        color: isActive
            ? Colors.white
            : (isPassed
                ? Colors.black.withOpacity(0.08)
                : Colors.white.withOpacity(0.06)),
        borderRadius: BorderRadius.circular(14),
        boxShadow: isActive
            ? [
                BoxShadow(
                  color: Colors.black.withOpacity(0.15),
                  blurRadius: 8,
                  offset: const Offset(0, 3),
                ),
              ]
            : null,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          SvgPicture.asset(
            icon,
            height: 22,
            width: 22,
            color: isActive ? AppColorModern.emeraldDark : Colors.white,
          ),
          const SizedBox(height: 5),
          Text(
            name,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: robotoMedium.copyWith(
              fontSize: 11,
              color: isActive
                  ? AppColorModern.emeraldDark
                  : (isPassed ? Colors.white54 : Colors.white),
            ),
          ),
          const SizedBox(height: 3),
          Text(
            translateText(DateConverter.formatPrayerTime(time, is24HourFormat)),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: robotoBold.copyWith(
              fontSize: 10,
              color: isActive
                  ? AppColorModern.emeraldDark
                  : (isPassed ? Colors.white38 : Colors.white.withOpacity(0.85)),
            ),
          ),
          const SizedBox(height: 4),
          _StatusDot(status: status, isActive: isActive),
        ],
      ),
    );
  }
}

class _StatusDot extends StatelessWidget {
  final _ChipStatus status;
  final bool isActive;
  const _StatusDot({required this.status, required this.isActive});

  @override
  Widget build(BuildContext context) {
    if (isActive) {
      return Container(
        width: 5,
        height: 5,
        decoration: const BoxDecoration(
          color: AppColorModern.emerald,
          shape: BoxShape.circle,
        ),
      );
    }
    if (status == _ChipStatus.passed) {
      return const Icon(
        Icons.check,
        size: 10,
        color: Colors.white38,
      );
    }
    return Container(
      width: 4,
      height: 4,
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.4),
        shape: BoxShape.circle,
      ),
    );
  }
}
