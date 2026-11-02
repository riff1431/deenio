// ignore_for_file: deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:get/get.dart';
import 'package:audio_service/audio_service.dart';
import 'package:zabi/controller/audio_player_controller.dart';
import 'package:zabi/util/dimensions.dart';
import 'package:zabi/util/images.dart';
import 'package:zabi/util/styles.dart';
import 'package:zabi/view/screens/audio/audio_player_sheet.dart';

class GlobalMiniPlayer extends StatelessWidget {
  const GlobalMiniPlayer({super.key});

  @override
  Widget build(BuildContext context) {
    if (!Get.isRegistered<AudioPlayerController>()) {
      return const SizedBox.shrink();
    }

    final controller = Get.find<AudioPlayerController>();

    return Obx(() {
      final mediaItem = controller.currentMediaItem.value;

      // Hide if no audio is selected or full screen player sheet is open
      if (mediaItem == null || controller.isFullScreenPlayerOpen.value) {
        return const SizedBox.shrink();
      }

      final isDark = Theme.of(context).brightness == Brightness.dark;
      final primaryColor = Theme.of(context).primaryColor;

      return Container(
        margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
        decoration: BoxDecoration(
          color: isDark ? const Color(0xFF1E232A) : Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: isDark
                ? Colors.white.withOpacity(0.08)
                : primaryColor.withOpacity(0.15),
            width: 1,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(isDark ? 0.35 : 0.08),
              blurRadius: 12,
              spreadRadius: 1,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Sleek progress bar at top of mini player
              _buildProgressBar(context, controller, primaryColor),

              // Player Content Row
              GestureDetector(
                onTap: () => _openFullScreenSheet(context, controller),
                behavior: HitTestBehavior.opaque,
                child: Padding(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10,
                    vertical: 8,
                  ),
                  child: Row(
                    children: [
                      // Album Art Thumbnail
                      _buildAlbumArt(context, mediaItem, isDark),

                      const SizedBox(width: 10),

                      // Surah Title & Reciter
                      Expanded(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              mediaItem.title,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: robotoMedium.copyWith(
                                fontSize: Dimensions.FONT_SIZE_DEFAULT,
                                fontWeight: FontWeight.w600,
                                color: isDark
                                    ? Colors.white
                                    : const Color(0xFF1B232A),
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              mediaItem.artist ?? 'unknown_artist_key'.tr,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: robotoRegular.copyWith(
                                fontSize: Dimensions.FONT_SIZE_EXTRA_SMALL,
                                color: isDark
                                    ? Colors.grey[400]
                                    : Colors.grey[600],
                              ),
                            ),
                          ],
                        ),
                      ),

                      const SizedBox(width: 8),

                      // Controls: Previous, Play/Pause, Next
                      _buildControls(context, controller, primaryColor),

                      const SizedBox(width: 4),

                      // Dismiss (X) button
                      IconButton(
                        icon: Icon(
                          Icons.close_rounded,
                          size: 18,
                          color: isDark ? Colors.grey[400] : Colors.grey[500],
                        ),
                        padding: const EdgeInsets.all(4),
                        constraints: const BoxConstraints(),
                        tooltip: 'Close player',
                        onPressed: () => controller.stopAndDismiss(),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      );
    });
  }

  Widget _buildProgressBar(
    BuildContext context,
    AudioPlayerController controller,
    Color primaryColor,
  ) {
    return Obx(() {
      final pos = controller.position.value.inMilliseconds.toDouble();
      final dur = controller.duration.value.inMilliseconds.toDouble();
      final progress =
          (dur > 0 && pos <= dur) ? (pos / dur).clamp(0.0, 1.0) : 0.0;

      return LinearProgressIndicator(
        value: progress,
        minHeight: 2.0,
        backgroundColor: Colors.transparent,
        valueColor: AlwaysStoppedAnimation<Color>(primaryColor),
      );
    });
  }

  Widget _buildAlbumArt(
    BuildContext context,
    MediaItem mediaItem,
    bool isDark,
  ) {
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(10),
        color: isDark ? Colors.grey[800] : Colors.grey[200],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(10),
        child: mediaItem.artUri != null
            ? Image.network(
                mediaItem.artUri.toString(),
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) =>
                    _buildPlaceholderIcon(context),
              )
            : _buildPlaceholderIcon(context),
      ),
    );
  }

  Widget _buildPlaceholderIcon(BuildContext context) {
    return Center(
      child: Icon(
        Icons.music_note_rounded,
        size: 20,
        color: Theme.of(context).primaryColor,
      ),
    );
  }

  Widget _buildControls(
    BuildContext context,
    AudioPlayerController controller,
    Color primaryColor,
  ) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        IconButton(
          icon: Icon(
            Icons.skip_previous_rounded,
            size: 22,
            color: primaryColor,
          ),
          padding: const EdgeInsets.all(3),
          constraints: const BoxConstraints(),
          onPressed: controller.skipToPrevious,
        ),
        const SizedBox(width: 4),
        Obx(() {
          final isPlaying = controller.isPlaying.value;
          return Container(
            width: 34,
            height: 34,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: primaryColor,
              boxShadow: [
                BoxShadow(
                  color: primaryColor.withOpacity(0.3),
                  blurRadius: 6,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: IconButton(
              icon: SvgPicture.asset(
                isPlaying ? Images.Pause_Icon : Images.Play_Icon,
                color: Colors.white,
                width: 14,
                height: 14,
              ),
              padding: EdgeInsets.zero,
              constraints: const BoxConstraints(),
              onPressed: controller.togglePlayPause,
            ),
          );
        }),
        const SizedBox(width: 4),
        IconButton(
          icon: Icon(
            Icons.skip_next_rounded,
            size: 22,
            color: primaryColor,
          ),
          padding: const EdgeInsets.all(3),
          constraints: const BoxConstraints(),
          onPressed: controller.skipToNext,
        ),
      ],
    );
  }

  void _openFullScreenSheet(
    BuildContext context,
    AudioPlayerController controller,
  ) {
    controller.isFullScreenPlayerOpen.value = true;
    final targetContext = Get.key.currentContext ?? context;

    showModalBottomSheet(
      context: targetContext,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => FullScreenBottomSheet(controller: controller),
    ).whenComplete(() {
      controller.isFullScreenPlayerOpen.value = false;
    });
  }
}
