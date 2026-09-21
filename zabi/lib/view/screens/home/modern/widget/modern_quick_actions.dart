// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';
import 'package:zabi/view/screens/category/category_screen.dart';

class ModernQuickActions extends StatelessWidget {
  const ModernQuickActions({super.key});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    final items = [
      _QuickAction(
        icon: Images.ModernIcon_ReadQuran,
        label: 'quran'.tr,
        route: RouteHelper.suraList,
        accentColor: const Color(0xFF2E7D5B),
      ),
      _QuickAction(
        icon: Images.ModernIcon_AudioQuran,
        label: 'audio_quran'.tr,
        route: RouteHelper.recters,
        accentColor: const Color(0xFF0F766E),
      ),
      _QuickAction(
        icon: Images.ModernIcon_Duas,
        label: 'dua'.tr,
        route: RouteHelper.dua,
        accentColor: const Color(0xFFD97706),
      ),
      _QuickAction(
        icon: Images.ModernIcon_Dhikr,
        label: 'dikir'.tr,
        route: RouteHelper.dhikr,
        accentColor: const Color(0xFF4F46E5),
      ),
      _QuickAction(
        icon: Images.ModernIcon_QiblaKaaba,
        label: 'compass'.tr,
        route: RouteHelper.compass,
        accentColor: const Color(0xFF059669),
        tint: false,
      ),
    ];

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF1B2620) : Colors.white,
        borderRadius: BorderRadius.circular(22),
        border: Border.all(
          color: isDark
              ? Colors.white.withOpacity(0.08)
              : AppColorModern.emerald.withOpacity(0.1),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: isDark
                ? Colors.black.withOpacity(0.3)
                : Colors.black.withOpacity(0.04),
            blurRadius: 14,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header Row
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'quick_actions'.tr,
                style: robotoBold.copyWith(
                  fontSize: 15,
                  color: isDark ? Colors.white : AppColorModern.textPrimary,
                ),
              ),
              GestureDetector(
                onTap: () => Get.to(() => CategoryScreen(appBackButton: true)),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      'view_all'.tr,
                      style: robotoMedium.copyWith(
                        fontSize: 12,
                        color: AppColorModern.emerald,
                      ),
                    ),
                    const SizedBox(width: 3),
                    const Icon(
                      Icons.arrow_forward_rounded,
                      size: 13,
                      color: AppColorModern.emerald,
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),

          // 5-item Balanced Row
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: items.map((item) {
              return Expanded(
                child: _QuickActionTile(
                  icon: item.icon,
                  label: item.label,
                  route: item.route,
                  accentColor: item.accentColor,
                  tint: item.tint,
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }
}

class _QuickAction {
  final String icon;
  final String label;
  final String route;
  final Color accentColor;
  final bool tint;

  _QuickAction({
    required this.icon,
    required this.label,
    required this.route,
    required this.accentColor,
    this.tint = true,
  });
}

class _QuickActionTile extends StatelessWidget {
  final String icon;
  final String label;
  final String route;
  final Color accentColor;
  final bool tint;

  const _QuickActionTile({
    required this.icon,
    required this.label,
    required this.route,
    required this.accentColor,
    required this.tint,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return InkWell(
      borderRadius: BorderRadius.circular(16),
      onTap: () => Get.toNamed(route),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 2),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 52,
              height: 52,
              decoration: BoxDecoration(
                color: isDark
                    ? accentColor.withOpacity(0.18)
                    : accentColor.withOpacity(0.09),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(
                  color: accentColor.withOpacity(isDark ? 0.3 : 0.2),
                  width: 1,
                ),
              ),
              alignment: Alignment.center,
              child: SvgPicture.asset(
                icon,
                width: 26,
                height: 26,
                color: tint ? accentColor : null,
              ),
            ),
            const SizedBox(height: 7),
            Text(
              label,
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: robotoMedium.copyWith(
                fontSize: 11,
                color: isDark ? Colors.white70 : AppColorModern.textPrimary,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
