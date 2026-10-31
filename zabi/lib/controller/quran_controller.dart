import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:zabi/data/model/response/juz_list_model.dart';
import 'package:zabi/data/model/response/sifat_name_details_model.dart';
import 'package:zabi/data/model/response/sifat_name_list_model.dart';
import 'package:zabi/data/model/response/sura_detile_model.dart';
import 'package:zabi/data/model/response/sura_list_model.dart';
import 'package:zabi/data/repository/sifatname_list_repo.dart';

class QuranController extends GetxController implements GetxService {
  final QuranRepo quranRepo;
  QuranController({required this.quranRepo});

  @override
  void onInit() {
    super.onInit();
    loadCachedData();
  }

  void loadCachedData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      if (suraListApiData == null) {
        final cachedSura = prefs.getString('cached_sura_list');
        if (cachedSura != null) {
          suraListApiData = SuraListModel.fromJson(jsonDecode(cachedSura));
        } else {
          final localJson =
              await rootBundle.loadString('assets/quran/surah_list.json');
          suraListApiData = SuraListModel.fromJson(jsonDecode(localJson));
        }
        update();
      }
      if (juzListApiData == null) {
        final cachedJuz = prefs.getString('cached_juz_list');
        if (cachedJuz != null) {
          juzListApiData = JuzListModel.fromJson(jsonDecode(cachedJuz));
        } else {
          final localJson =
              await rootBundle.loadString('assets/quran/juzz_list.json');
          juzListApiData = JuzListModel.fromJson(jsonDecode(localJson));
        }
        update();
      }
    } catch (e) {
      debugPrint("Error loading cached/bundled Quran data: $e");
    }
  }

// local variable
  RxBool isSifatNameListLoading = false.obs;
  SifatNameListModel? sifatNameApiData;

// get dua list form here
  Future<void> fetchSifatNameListData() async {
    try {
      isSifatNameListLoading(true);

      final response = await quranRepo.getSifatNameRepo();

      if (response.statusCode == 200) {
        sifatNameApiData = SifatNameListModel.fromJson(response.body);
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching data: $e");
      }
    } finally {
      isSifatNameListLoading(false);
      update();
    }
  }

  RxBool isSifatNameDetailsLoading = false.obs;
  SifatNameDetailsModel? sifatnameDetailsApidata;

// get dua details function
  Future<void> fetchSifatNameDetailsData({String? sifatNameId}) async {
    try {
      isSifatNameDetailsLoading(true);

      final response =
          await quranRepo.getSifatNameDetailsRepo(sifatNameId.toString());

      if (response.statusCode == 200) {
        sifatnameDetailsApidata = SifatNameDetailsModel.fromJson(response.body);
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching data: $e");
      }
    } finally {
      isSifatNameDetailsLoading(false);
      update();
    }
  }

  // local variable
  SuraListModel? suraListApiData;
  SuraDetaileModel? suraDetaileApiData;
  JuzListModel? juzListApiData;

  RxBool isJuzListLoading = false.obs;
  RxBool isSuraListLoading = false.obs;
  RxBool isSuraDetaileLoading = false.obs;
  int? suraNumber;

// get sura list function
  Future<void> fetchSuraListData({String? translatorId, bool reload = false}) async {
    if (!reload && suraListApiData != null && suraListApiData!.data != null && suraListApiData!.data!.isNotEmpty) {
      return;
    }
    if (isSuraListLoading.value) return;

    try {
      if (suraListApiData == null) {
        isSuraListLoading(true);
      }
      final prefs = await SharedPreferences.getInstance();
      var selectedTranslatorId =
          translatorId ?? prefs.getString('selectedTranslatorId') ?? '1';

      final response =
          await quranRepo.getSuraListRepo(selectedTranslatorId.toString());

      if (response.statusCode == 200 && response.body != null) {
        suraListApiData = SuraListModel.fromJson(response.body);
        try {
          await prefs.setString('cached_sura_list', jsonEncode(response.body));
        } catch (_) {}
      } else if (suraListApiData == null) {
        final localJson =
            await rootBundle.loadString('assets/quran/surah_list.json');
        suraListApiData = SuraListModel.fromJson(jsonDecode(localJson));
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching sura list: $e");
      }
      if (suraListApiData == null) {
        try {
          final localJson =
              await rootBundle.loadString('assets/quran/surah_list.json');
          suraListApiData = SuraListModel.fromJson(jsonDecode(localJson));
        } catch (_) {}
      }
    } finally {
      isSuraListLoading(false);
      update();
    }
  }

// get sura detail function
  Future<void> fetchSuraDetaileData(
      {String? suraId, String? translatorId}) async {
    try {
      isSuraDetaileLoading(true);
      update();
      final prefs = await SharedPreferences.getInstance();
      var selectedTranslatorId =
          translatorId ?? prefs.getString('selectedTranslatorId') ?? '1';

      final response = await quranRepo.getSuraDetailsRepo(
          suraId.toString(), selectedTranslatorId.toString());

      if (response.statusCode == 200) {
        suraDetaileApiData = SuraDetaileModel.fromJson(response.body);
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching data: $e");
      }
    } finally {
      isSuraDetaileLoading(false);
      update();
    }
  }

// get juz list form here
  Future<void> fetchJuzListData({String? translatorId, bool reload = false}) async {
    if (!reload && juzListApiData != null && juzListApiData!.data != null && juzListApiData!.data!.isNotEmpty) {
      return;
    }
    if (isJuzListLoading.value) return;

    try {
      if (juzListApiData == null) {
        isJuzListLoading(true);
        update();
      }
      final prefs = await SharedPreferences.getInstance();
      var selectedTranslatorId =
          translatorId ?? prefs.getString('selectedTranslatorId') ?? '1';

      final response =
          await quranRepo.getJuzListRepo(selectedTranslatorId.toString());

      if (response.statusCode == 200 && response.body != null) {
        juzListApiData = JuzListModel.fromJson(response.body);
        try {
          await prefs.setString('cached_juz_list', jsonEncode(response.body));
        } catch (_) {}
      } else if (juzListApiData == null) {
        final localJson =
            await rootBundle.loadString('assets/quran/juzz_list.json');
        juzListApiData = JuzListModel.fromJson(jsonDecode(localJson));
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching juz list: $e");
      }
      if (juzListApiData == null) {
        try {
          final localJson =
              await rootBundle.loadString('assets/quran/juzz_list.json');
          juzListApiData = JuzListModel.fromJson(jsonDecode(localJson));
        } catch (_) {}
      }
    } finally {
      isJuzListLoading(false);
      update();
    }
  }
}
