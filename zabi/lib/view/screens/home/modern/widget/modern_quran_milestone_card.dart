// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:get/get.dart';
import 'package:zabi/controller/quran_milestone_controller.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';

class ModernQuranMilestoneCard extends StatelessWidget {
  const ModernQuranMilestoneCard({super.key});

  @override
  Widget build(BuildContext context) {
    return GetX<QuranMilestoneController>(
      builder: (controller) {
        final read = controller.pagesReadToday.value;
        final goal = controller.dailyGoal.value;
        final ratio = controller.progressRatio;
        final remaining = (goal - read).clamp(0, goal);
        final isDark = Theme.of(context).brightness == Brightness.dark;

        return GestureDetector(
          onTap: () => Get.toNamed(RouteHelper.suraList),
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: isDark ? const Color(0xFF1B2620) : Colors.white,
              borderRadius: BorderRadius.circular(22),
              border: Border.all(
                color: isDark
                    ? Colors.white.withOpacity(0.08)
                    : AppColorModern.emerald.withOpacity(0.12),
                width: 1,
              ),
              boxShadow: [
                BoxShadow(
                  color: isDark
                      ? Colors.black.withOpacity(0.3)
                      : Colors.black.withOpacity(0.05),
                  blurRadius: 14,
                  offset: const Offset(0, 5),
                ),
              ],
            ),
            child: Row(
              children: [
                // 1. Circular Progress Indicator with icon & count
                _CircularProgress(ratio: ratio, read: read, goal: goal),

                const SizedBox(width: 16),

                // 2. Goal Stats & Action
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'quran_milestone_title'.tr,
                            style: robotoBold.copyWith(
                              fontSize: 15,
                              color: isDark ? Colors.white : AppColorModern.textPrimary,
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 8,
                              vertical: 3,
                            ),
                            decoration: BoxDecoration(
                              color: AppColorModern.emerald.withOpacity(0.12),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Text(
                              '${(ratio * 100).toStringAsFixed(0)}%',
                              style: robotoBold.copyWith(
                                fontSize: 11,
                                color: AppColorModern.emerald,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 3),
                      Text(
                        'quran_milestone_subtitle'.tr,
                        style: robotoRegular.copyWith(
                          fontSize: 11,
                          color: isDark ? Colors.white60 : AppColorModern.textSecondary,
                        ),
                      ),
                      const SizedBox(height: 10),

                      // Linear track
                      ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: LinearProgressIndicator(
                          value: ratio.clamp(0.0, 1.0),
                          minHeight: 6,
                          backgroundColor: isDark
                              ? Colors.white.withOpacity(0.1)
                              : AppColorModern.emeraldSoftBg,
                          valueColor: const AlwaysStoppedAnimation<Color>(
                            AppColorModern.emerald,
                          ),
                        ),
                      ),

                      const SizedBox(height: 10),

                      // Continue reading pill button
                      InkWell(
                        onTap: () => Get.toNamed(RouteHelper.suraList),
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 6,
                          ),
                          decoration: BoxDecoration(
                            color: isDark
                                ? Colors.white.withOpacity(0.06)
                                : AppColorModern.emeraldSoftBg,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Row(
                            children: [
                              SvgPicture.asset(
                                Images.ModernIcon_ReadQuran,
                                height: 16,
                                width: 16,
                                color: AppColorModern.emerald,
                              ),
                              const SizedBox(width: 6),
                              Expanded(
                                child: Text(
                                  remaining == 0
                                      ? 'quran_milestone_complete'.tr
                                      : '$remaining ${'quran_milestone_remaining'.tr}',
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: robotoMedium.copyWith(
                                    fontSize: 11,
                                    color: isDark ? Colors.white : AppColorModern.textPrimary,
                                  ),
                                ),
                              ),
                              const Icon(
                                Icons.arrow_forward_ios_rounded,
                                size: 11,
                                color: AppColorModern.emerald,
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}

class _CircularProgress extends StatelessWidget {
  final double ratio;
  final int read;
  final int goal;
  const _CircularProgress({
    required this.ratio,
    required this.read,
    required this.goal,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return SizedBox(
      width: 76,
      height: 76,
      child: Stack(
        alignment: Alignment.center,
        children: [
          SizedBox(
            width: 76,
            height: 76,
            child: CircularProgressIndicator(
              value: ratio.clamp(0.0, 1.0),
              strokeWidth: 6.5,
              strokeCap: StrokeCap.round,
              backgroundColor: isDark
                  ? Colors.white.withOpacity(0.12)
                  : AppColorModern.emeraldSoftBg,
              valueColor: const AlwaysStoppedAnimation(
                AppColorModern.emerald,
              ),
            ),
          ),
          Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              SvgPicture.asset(
                Images.Icon_Quran,
                width: 18,
                height: 18,
                color: AppColorModern.emerald,
              ),
              const SizedBox(height: 2),
              Text(
                '$read/$goal',
                style: robotoBold.copyWith(
                  fontSize: 11,
                  color: isDark ? Colors.white : AppColorModern.textPrimary,
                ),
              ),
              Text(
                'pages'.tr.isNotEmpty ? 'pages'.tr : 'pages',
                style: robotoRegular.copyWith(
                  fontSize: 9,
                  color: isDark ? Colors.white54 : AppColorModern.textSecondary,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
