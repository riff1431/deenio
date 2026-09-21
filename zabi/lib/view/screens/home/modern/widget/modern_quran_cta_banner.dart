// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:zabi/helper/route_helper.dart';
import 'package:zabi/theme/modern_light_theme.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';

class ModernQuranCtaBanner extends StatelessWidget {
  const ModernQuranCtaBanner({super.key});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(22),
        gradient: LinearGradient(
          colors: isDark
              ? [
                  const Color(0xFF13241B),
                  const Color(0xFF1B3829),
                  const Color(0xFF224D37),
                ]
              : [
                  const Color(0xFF144D35),
                  const Color(0xFF1B6344),
                  const Color(0xFF2E7D5B),
                ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: [
          BoxShadow(
            color: isDark
                ? Colors.black.withOpacity(0.35)
                : const Color(0xFF1B5E3F).withOpacity(0.2),
            blurRadius: 16,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(22),
        child: Stack(
          children: [
            // Ambient light circles
            Positioned(
              right: -30,
              bottom: -30,
              child: Container(
                width: 140,
                height: 140,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Colors.white.withOpacity(0.06),
                ),
              ),
            ),

            Padding(
              padding: const EdgeInsets.all(20),
              child: Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 8,
                                vertical: 3,
                              ),
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.18),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Text(
                                'quran'.tr.toUpperCase(),
                                style: robotoBold.copyWith(
                                  color: Colors.white,
                                  fontSize: 9,
                                  letterSpacing: 0.8,
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'quran_cta_title'.tr,
                          style: robotoBold.copyWith(
                            color: Colors.white,
                            fontSize: 18,
                            height: 1.2,
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          'quran_cta_subtitle'.tr,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: robotoRegular.copyWith(
                            color: Colors.white.withOpacity(0.85),
                            fontSize: 12,
                            height: 1.3,
                          ),
                        ),
                        const SizedBox(height: 14),
                        ElevatedButton.icon(
                          onPressed: () => Get.toNamed(RouteHelper.suraList),
                          icon: const Icon(Icons.menu_book_rounded, size: 16),
                          label: Text(
                            'start_reading'.tr,
                            style: robotoBold.copyWith(fontSize: 12),
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.white,
                            foregroundColor: AppColorModern.emeraldDark,
                            elevation: 0,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(20),
                            ),
                            padding: const EdgeInsets.symmetric(
                              horizontal: 16,
                              vertical: 10,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 14),
                  Container(
                    width: 72,
                    height: 72,
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.12),
                      shape: BoxShape.circle,
                      border: Border.all(
                        color: Colors.white.withOpacity(0.2),
                        width: 1,
                      ),
                    ),
                    alignment: Alignment.center,
                    child: Image.asset(
                      Images.Nav_quran,
                      width: 44,
                      height: 44,
                      fit: BoxFit.contain,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
