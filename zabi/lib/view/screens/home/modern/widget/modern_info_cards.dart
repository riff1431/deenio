// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:get/get.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';

class IslamicFeatureGrid extends StatelessWidget {
  const IslamicFeatureGrid({super.key});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final hour = DateTime.now().hour;
    final isMorning = hour < 15;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Section Title
        Padding(
          padding: const EdgeInsets.only(bottom: 12),
          child: Text(
            'daily_essentials'.tr == 'daily_essentials' ? 'Daily Essentials' : 'daily_essentials'.tr,
            style: robotoBold.copyWith(
              fontSize: 15,
              color: isDark ? Colors.white : AppColorModern.textPrimary,
            ),
          ),
        ),

        // Bento Grid Row 1: Featured Hadith + Column (Adhkar & Mosque)
        SizedBox(
          height: 180,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // 1. Featured Hadith Card (Large)
              Expanded(
                flex: 55,
                child: _BentoCard(
                  title: 'hadith_of_the_day_title'.tr,
                  subtitle: '"The most beloved people to Allah are those who are most beneficial to people."',
                  caption: 'Sahih Al-Jami',
                  iconAsset: Images.ModernIllustration_HadithOfDay,
                  isIllustration: true,
                  backgroundColor: isDark ? const Color(0xFF2A2214) : const Color(0xFFFFFBEB),
                  borderColor: isDark ? const Color(0xFF4D3D20) : const Color(0xFFFDE68A),
                  accentColor: const Color(0xFFD97706),
                  onTap: () => Get.toNamed(RouteHelper.hadithBookName),
                ),
              ),

              const SizedBox(width: 10),

              // 2. Column: Daily Adhkar & Nearby Mosques
              Expanded(
                flex: 45,
                child: Column(
                  children: [
                    Expanded(
                      child: _BentoCard(
                        title: isMorning
                            ? ('morning_adhkar'.tr == 'morning_adhkar' ? 'Morning Adhkar' : 'morning_adhkar'.tr)
                            : ('evening_adhkar'.tr == 'evening_adhkar' ? 'Evening Adhkar' : 'evening_adhkar'.tr),
                        subtitle: 'daily_adhkar_subtitle'.tr,
                        iconAsset: Images.ModernIllustration_AdhkarScene,
                        isIllustration: true,
                        backgroundColor: isDark ? const Color(0xFF142B1D) : const Color(0xFFF0FDF4),
                        borderColor: isDark ? const Color(0xFF224A32) : const Color(0xFFBBF7D0),
                        accentColor: const Color(0xFF16A34A),
                        onTap: () => Get.toNamed(RouteHelper.dhikr),
                      ),
                    ),
                    const SizedBox(height: 10),
                    Expanded(
                      child: _BentoCard(
                        title: 'masjid_nearby_title'.tr,
                        subtitle: 'masjid_nearby_subtitle'.tr,
                        iconAsset: Images.ModernIllustration_MasjidNearby,
                        isIllustration: true,
                        backgroundColor: isDark ? const Color(0xFF122624) : const Color(0xFFF0FDFA),
                        borderColor: isDark ? const Color(0xFF1F423F) : const Color(0xFF99F6E4),
                        accentColor: const Color(0xFF0D9488),
                        onTap: () => Get.toNamed(RouteHelper.nearByMosque),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),

        const SizedBox(height: 10),

        // Bento Grid Row 2: Qibla Compass + Zakat Calculator
        Row(
          children: [
            Expanded(
              child: _CompactBentoCard(
                title: 'qibla_direction_title'.tr,
                subtitle: 'qibla_direction_subtitle'.tr,
                iconAsset: Images.Icon_Qibla,
                isSvg: true,
                backgroundColor: isDark ? const Color(0xFF132821) : const Color(0xFFECFDF5),
                borderColor: isDark ? const Color(0xFF1F4337) : const Color(0xFFA7F3D0),
                accentColor: const Color(0xFF059669),
                onTap: () => Get.toNamed(RouteHelper.compass),
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _CompactBentoCard(
                title: 'zakat_calculator_title'.tr,
                subtitle: 'zakat_calculator_subtitle'.tr,
                iconAsset: Images.Icon_Zakat,
                isSvg: true,
                backgroundColor: isDark ? const Color(0xFF1D222A) : const Color(0xFFF8FAFC),
                borderColor: isDark ? const Color(0xFF2F3745) : const Color(0xFFE2E8F0),
                accentColor: const Color(0xFF6366F1),
                onTap: () => Get.toNamed(RouteHelper.zakatCalculator),
              ),
            ),
          ],
        ),
      ],
    );
  }
}

class _BentoCard extends StatelessWidget {
  final String title;
  final String subtitle;
  final String? caption;
  final String iconAsset;
  final bool isIllustration;
  final Color backgroundColor;
  final Color borderColor;
  final Color accentColor;
  final VoidCallback onTap;

  const _BentoCard({
    required this.title,
    required this.subtitle,
    this.caption,
    required this.iconAsset,
    this.isIllustration = false,
    required this.backgroundColor,
    required this.borderColor,
    required this.accentColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Material(
      color: backgroundColor,
      borderRadius: BorderRadius.circular(18),
      child: InkWell(
        borderRadius: BorderRadius.circular(18),
        onTap: onTap,
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(18),
            border: Border.all(color: borderColor, width: 1),
          ),
          padding: const EdgeInsets.all(12),
          child: Stack(
            children: [
              // Decorative illustration in bottom-right corner
              Positioned(
                right: -4,
                bottom: -4,
                child: Opacity(
                  opacity: isDark ? 0.55 : 0.75,
                  child: Image.asset(
                    iconAsset,
                    width: 72,
                    height: 72,
                    fit: BoxFit.contain,
                    errorBuilder: (_, _, _) => const SizedBox.shrink(),
                  ),
                ),
              ),
              // Text content
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Text(
                          title,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: robotoBold.copyWith(
                            fontSize: 13,
                            color: isDark ? Colors.white : const Color(0xFF1E293B),
                          ),
                        ),
                      ),
                      Icon(
                        Icons.arrow_outward_rounded,
                        size: 14,
                        color: accentColor,
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Expanded(
                    child: Text(
                      subtitle,
                      maxLines: 3,
                      overflow: TextOverflow.ellipsis,
                      style: robotoRegular.copyWith(
                        fontSize: 11,
                        height: 1.3,
                        color: isDark ? Colors.white70 : const Color(0xFF475569),
                      ),
                    ),
                  ),
                  if (caption != null)
                    Text(
                      caption!,
                      style: robotoMedium.copyWith(
                        fontSize: 10,
                        color: accentColor,
                      ),
                    ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _CompactBentoCard extends StatelessWidget {
  final String title;
  final String subtitle;
  final String iconAsset;
  final bool isSvg;
  final Color backgroundColor;
  final Color borderColor;
  final Color accentColor;
  final VoidCallback onTap;

  const _CompactBentoCard({
    required this.title,
    required this.subtitle,
    required this.iconAsset,
    this.isSvg = false,
    required this.backgroundColor,
    required this.borderColor,
    required this.accentColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Material(
      color: backgroundColor,
      borderRadius: BorderRadius.circular(18),
      child: InkWell(
        borderRadius: BorderRadius.circular(18),
        onTap: onTap,
        child: Container(
          height: 80,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(18),
            border: Border.all(color: borderColor, width: 1),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
          child: Row(
            children: [
              Container(
                width: 38,
                height: 38,
                decoration: BoxDecoration(
                  color: accentColor.withOpacity(isDark ? 0.2 : 0.12),
                  borderRadius: BorderRadius.circular(12),
                ),
                alignment: Alignment.center,
                child: isSvg
                    ? SvgPicture.asset(iconAsset, width: 20, height: 20, color: accentColor)
                    : Image.asset(iconAsset, width: 22, height: 22, fit: BoxFit.contain),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      title,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: robotoBold.copyWith(
                        fontSize: 12,
                        color: isDark ? Colors.white : const Color(0xFF1E293B),
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      subtitle,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: robotoRegular.copyWith(
                        fontSize: 10,
                        color: isDark ? Colors.white60 : const Color(0xFF64748B),
                      ),
                    ),
                  ],
                ),
              ),
              Icon(
                Icons.chevron_right_rounded,
                size: 16,
                color: accentColor,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
