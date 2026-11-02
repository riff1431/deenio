// ignore_for_file: deprecated_member_use

import 'package:audio_service/audio_service.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:zabi/controller/audio_player_controller.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/util/styles.dart';
import 'package:zabi/view/base/custom_app_bar.dart';
import '../../../util/dimensions.dart';

class AudioPlayerView extends StatefulWidget {
  final int? reciterId;
  final bool? appBackButton;

  const AudioPlayerView({super.key, this.reciterId, this.appBackButton});

  @override
  State<AudioPlayerView> createState() => _AudioPlayerViewState();
}

class _AudioPlayerViewState extends State<AudioPlayerView> {
  final AudioPlayerController _controller = Get.find<AudioPlayerController>();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      int effectiveId = 1;
      if (widget.reciterId != null) {
        effectiveId = widget.reciterId!;
      } else if (Get.arguments != null) {
        if (Get.arguments is int) {
          effectiveId = Get.arguments as int;
        } else {
          effectiveId = int.tryParse(Get.arguments.toString()) ?? 1;
        }
      }
      await _controller.loadAudioList(effectiveId.toString());
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        title: "audio_list_key".tr,
        isBackButtonExist: widget.appBackButton ?? false,
      ),
      body: Obx(() => _controller.isLoading.value
          ? const Center(child: DhuaShimmer())
          : _controller.audioData.isEmpty
              ? _buildEmptyAudioList()
              : _buildAudioList()),
    );
  }

  Widget _buildEmptyAudioList() {
    return Center(
      child: Text(
        "no_audio_found_key".tr,
        style: robotoMedium.copyWith(fontSize: Dimensions.FONT_SIZE_LARGE),
      ),
    );
  }

  Widget _buildAudioList() {
    return ListView.builder(
      padding: const EdgeInsets.only(
        top: Dimensions.PADDING_SIZE_EXTRA_SMALL,
        bottom: 95,
      ),
      itemCount: _controller.audioList.length,
      itemBuilder: (context, index) {
        final mediaItem = _controller.audioList[index];
        return _buildAudioListItem(mediaItem);
      },
    );
  }

  Widget _buildAudioListItem(MediaItem mediaItem) {
    return Padding(
      padding:
          const EdgeInsets.symmetric(horizontal: Dimensions.PADDING_SIZE_SMALL),
      child: Card(
        clipBehavior: Clip.antiAlias,
        color: Theme.of(context).cardColor,
        child: ListTile(
          leading: CircleAvatar(
            backgroundImage: mediaItem.artUri != null
                ? NetworkImage(mediaItem.artUri.toString())
                : null,
            child: mediaItem.artUri == null
                ? const Icon(Icons.music_note)
                : null,
          ),
          title: Text(
            mediaItem.title,
            style: robotoMedium.copyWith(color: Theme.of(context).primaryColor),
          ),
          subtitle: Text(
            mediaItem.artist ?? 'unknown_artist_key'.tr,
            style:
                robotoMedium.copyWith(color: Theme.of(context).disabledColor),
          ),
          onTap: () => _controller.playMediaItem(mediaItem),
        ),
      ),
    );
  }
}
