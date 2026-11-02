import 'package:audio_service/audio_service.dart';
import 'package:flutter/foundation.dart';
import 'package:get/get.dart';
import 'package:zabi/data/api/api_client.dart';
import 'package:zabi/data/model/response/reciters_model.dart';
import 'package:zabi/helper/audio_handler.dart';
import 'package:zabi/helper/audio_service_helper.dart';
import 'package:zabi/util/app_constants.dart';

class AudioPlayerController extends GetxController {
  final ApiClient apiClient;

  AudioPlayerController({required this.apiClient});

  // Lazy getter: ensures audioHandler is only accessed after AudioService.init() and typed as AudioPlayerHandler
  AudioPlayerHandler get _audioHandler =>
      AudioServiceHelper.audioHandler as AudioPlayerHandler;

  // Reciter API call
  RxBool isRecitersLoading = false.obs;
  RecitersModel? recitersListApiData;

  // Reciter audio list
  RxBool isAudioLoading = false.obs;
  List<dynamic> audioData = [];

  // Reactive audio list & player state
  final RxList<MediaItem> audioList = <MediaItem>[].obs;
  final Rx<MediaItem?> currentMediaItem = Rx<MediaItem?>(null);
  final RxBool isLoading = false.obs;
  final RxBool isPlaying = false.obs;
  final Rx<Duration> position = Duration.zero.obs;
  final Rx<Duration> duration = Duration.zero.obs;

  // Global persistent mini-player navigation & visibility state
  final RxBool isFullScreenPlayerOpen = false.obs;
  final RxString currentRoute = ''.obs;

  bool get isBottomNavActive =>
      currentRoute.value == '/bottomNavbar' ||
      currentRoute.value == '/home' ||
      currentRoute.value.isEmpty;

  @override
  void onInit() {
    super.onInit();
    _listenToAudioService();
  }

  void _listenToAudioService() {
    // Listen to media item updates from AudioHandler
    _audioHandler.mediaItem.listen((item) {
      if (item != null) {
        currentMediaItem.value = item;
        duration.value = item.duration ?? Duration.zero;
        update();
      }
    });

    // Listen to playback state (playing, buffering, position)
    _audioHandler.playbackState.listen((state) {
      isPlaying.value = state.playing;
      position.value = state.updatePosition;
      update();
    });
  }

  void updateRoute(String? route) {
    if (route != null && route.isNotEmpty) {
      currentRoute.value = route;
      update();
    }
  }

  Future<void> fetchReciterData() async {
    try {
      isRecitersLoading(true);
      final response = await apiClient.getData(AppConstants.RECITERS);

      if (response.statusCode == 200) {
        recitersListApiData = RecitersModel.fromJson(response.body);
        if (kDebugMode) {
          print("Reciters List: ${recitersListApiData!.data!.length}");
        }
      } else {
        if (kDebugMode) {
          print("Error fetching reciters: ${response.statusCode}");
        }
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching reciter data: $e");
      }
    } finally {
      isRecitersLoading(false);
      update();
    }
  }

  Future<void> fetchAudio(String id) async {
    try {
      audioData.clear();
      isAudioLoading(true);

      final safeId = (id.isEmpty || id == 'null') ? '1' : id;
      final response =
          await apiClient.getData(AppConstants.AUDIO_LIST + safeId);

      if (response.statusCode == 200 &&
          response.body is Map &&
          response.body["data"] is List) {
        audioData = response.body["data"];
        if (kDebugMode) {
          print("Audio List fetched: ${audioData.length} items");
        }
      } else {
        if (kDebugMode) {
          print("Error fetching audio list: ${response.statusCode}");
        }
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching audio: $e");
      }
    } finally {
      isAudioLoading(false);
      update();
    }
  }

  Future<void> loadAudioList(String id) async {
    try {
      isLoading(true);
      update();

      await fetchAudio(id);

      audioList.clear();

      for (var audio in audioData) {
        final int? audioDuration = audio['duration'] != null
            ? audio['duration'] is int
                ? audio['duration']
                : int.tryParse(audio['duration'].toString())
            : 0;

        final mediaItem = MediaItem(
          id: audio['path']?.toString() ?? '',
          album: audio['sura_name']?.toString() ?? 'Quran',
          title: audio['sura_name']?.toString() ?? 'Unknown Surah',
          artist: audio['reciter_name']?.toString() ?? 'Unknown Reciter',
          duration: Duration(milliseconds: audioDuration ?? 0),
          artUri: audio['reciter_avatar'] != null
              ? Uri.tryParse(audio['reciter_avatar'].toString())
              : null,
        );
        audioList.add(mediaItem);
      }

      await _audioHandler.loadAudioFromApi(audioData);
    } catch (e) {
      if (kDebugMode) {
        print('Error loading audio list: $e');
      }
    } finally {
      isLoading(false);
      update();
    }
  }

  Future<void> playMediaItem(MediaItem mediaItem) async {
    currentMediaItem.value = mediaItem;
    isPlaying.value = true;
    update();
    await _audioHandler.playMediaItem(mediaItem);
    update();
  }

  void togglePlayPause() async {
    if (isPlaying.value) {
      await _audioHandler.pause();
    } else {
      if (currentMediaItem.value != null) {
        await _audioHandler.play();
      } else if (audioList.isNotEmpty) {
        await playMediaItem(audioList[0]);
      }
    }
  }

  Future<void> skipToNext() async => await _audioHandler.skipToNext();

  Future<void> skipToPrevious() async => await _audioHandler.skipToPrevious();

  Future<void> seekToPosition(double value) async {
    if (value >= 0 && value <= duration.value.inMilliseconds) {
      await _audioHandler.seek(Duration(milliseconds: value.toInt()));
    }
  }

  Future<void> stopAndDismiss() async {
    try {
      await _audioHandler.stop();
    } catch (e) {
      if (kDebugMode) print('Error stopping audio: $e');
    }
    currentMediaItem.value = null;
    isPlaying.value = false;
    position.value = Duration.zero;
    update();
  }
}
