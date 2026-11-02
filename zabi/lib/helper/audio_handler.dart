// ignore_for_file: deprecated_member_use

import 'package:audio_service/audio_service.dart';
import 'package:flutter/foundation.dart';
import 'package:just_audio/just_audio.dart';

class AudioPlayerHandler extends BaseAudioHandler with SeekHandler {
  final AudioPlayer _player = AudioPlayer();
  final List<MediaItem> _mediaItems = [];
  int _currentIndex = 0;

  AudioPlayerHandler() {
    _initializePlayer();
  }

  Future<void> _initializePlayer() async {
    _player.playbackEventStream.map(_transformEvent).pipe(playbackState);

    // Auto-advance to next audio when current track finishes
    _player.playerStateStream.listen((state) {
      if (state.processingState == ProcessingState.completed) {
        skipToNext();
      }
    });
  }

  // Load audio list fetched from API into the handler
  Future<void> loadAudioFromApi(List<dynamic> audioData) async {
    _mediaItems.clear();

    for (var audio in audioData) {
      final int? audioDuration = audio['duration'] != null
          ? audio['duration'] is int
              ? audio['duration']
              : int.tryParse(audio['duration'].toString())
          : 0;

      final item = MediaItem(
        id: audio['path']?.toString() ?? '',
        album: audio['sura_name']?.toString() ?? 'Quran',
        title: audio['sura_name']?.toString() ?? 'Unknown Surah',
        artist: audio['reciter_name']?.toString() ?? 'Unknown Reciter',
        duration: Duration(milliseconds: audioDuration ?? 0),
        artUri: audio['reciter_avatar'] != null
            ? Uri.tryParse(audio['reciter_avatar'].toString())
            : null,
      );
      _mediaItems.add(item);
    }

    queue.add(_mediaItems);
  }

  @override
  Future<void> playMediaItem(MediaItem mediaItem) async {
    final index = _mediaItems.indexWhere((m) => m.id == mediaItem.id);
    if (index != -1) {
      _currentIndex = index;
    }
    this.mediaItem.add(mediaItem);
    try {
      if (mediaItem.id.isNotEmpty) {
        await _player.setAudioSource(AudioSource.uri(Uri.parse(mediaItem.id)));
        await _player.play();
      }
    } catch (e) {
      if (kDebugMode) {
        print('Error playing media item: $e');
      }
    }
  }

  @override
  Future<void> play() => _player.play();

  @override
  Future<void> pause() => _player.pause();

  @override
  Future<void> seek(Duration position) => _player.seek(position);

  @override
  Future<void> stop() async {
    await _player.stop();
    mediaItem.add(null);
    playbackState.add(playbackState.value.copyWith(
      playing: false,
      processingState: AudioProcessingState.idle,
    ));
  }

  @override
  Future<void> skipToNext() async {
    if (_mediaItems.isEmpty) return;
    if (_currentIndex + 1 < _mediaItems.length) {
      _currentIndex++;
      await playMediaItem(_mediaItems[_currentIndex]);
    }
  }

  @override
  Future<void> skipToPrevious() async {
    if (_mediaItems.isEmpty) return;
    if (_currentIndex - 1 >= 0) {
      _currentIndex--;
      await playMediaItem(_mediaItems[_currentIndex]);
    }
  }

  PlaybackState _transformEvent(PlaybackEvent event) {
    return PlaybackState(
      controls: [
        MediaControl.skipToPrevious,
        if (_player.playing) MediaControl.pause else MediaControl.play,
        MediaControl.stop,
        MediaControl.skipToNext,
      ],
      systemActions: const {
        MediaAction.seek,
        MediaAction.seekForward,
        MediaAction.seekBackward,
      },
      androidCompactActionIndices: const [0, 1, 3],
      processingState: const {
        ProcessingState.idle: AudioProcessingState.idle,
        ProcessingState.loading: AudioProcessingState.loading,
        ProcessingState.buffering: AudioProcessingState.buffering,
        ProcessingState.ready: AudioProcessingState.ready,
        ProcessingState.completed: AudioProcessingState.completed,
      }[_player.processingState] ?? AudioProcessingState.idle,
      playing: _player.playing,
      updatePosition: _player.position,
      bufferedPosition: _player.bufferedPosition,
      speed: _player.speed,
      queueIndex: _currentIndex,
    );
  }
}
