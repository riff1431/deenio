// ignore_for_file: avoid_print, strict_top_level_inference

import 'dart:convert';

import 'package:get/get.dart';
import 'package:http/http.dart' as http;

import 'package:zabi/data/model/response/hadis_book_model.dart';
import 'package:zabi/data/model/response/hadith_chapter_model.dart';
import 'package:zabi/data/model/response/hadith_model.dart';
import 'package:zabi/util/app_constants.dart';
import 'package:zabi/view/base/custom_snackbar.dart';

class HadithController extends GetxController {
  // loading flags – plain bool, toggled via update()
  bool isLoadingHadithChapterBool = false;
  bool isLoadingHadithBool = false;

  // keep RxBool aliases so existing code that reads .value keeps compiling
  bool get isLoadingHadithChapterVal => isLoadingHadithChapterBool;
  bool get isLoadingHadithVal => isLoadingHadithBool;

  // hadith api key
  String get hadithApiKey => AppConstants.HADITH_API_KEY;

  @override
  void onInit() {
    getHadishBookNameData();
    super.onInit();
  }

  HadisBookModel? hadisBookModel;
  HadithChapterModel? hadithChapterModel;
  HadithModel? hadithModel;

  // ──────────────────────────────────────────────
  //  BOOKS
  // ──────────────────────────────────────────────
  Future<void> getHadishBookNameData() async {
    isLoadingHadithChapterBool = true;
    update();
    try {
      final response = await http.get(
        Uri.parse(
            "${AppConstants.HADITH_BASE_URL}/api/books?apiKey=$hadithApiKey"),
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        hadisBookModel = HadisBookModel.fromJson(data);
      } else {
        showCustomSnackBar(
            data['message'] ?? "Failed to load hadith books",
            isError: true);
      }
    } catch (error) {
      print("Error fetching hadith books: $error");
    } finally {
      isLoadingHadithChapterBool = false;
      update();
    }
  }

  // ──────────────────────────────────────────────
  //  CHAPTERS
  // ──────────────────────────────────────────────
  Future<void> getHadithBookChaptersData(dynamic bookSlug) async {
    hadithChapterModel = null;
    isLoadingHadithChapterBool = true;
    update();
    try {
      final response = await http.get(
        Uri.parse(
            "${AppConstants.HADITH_BASE_URL}/api/$bookSlug/chapters?apiKey=$hadithApiKey"),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        hadithChapterModel = HadithChapterModel.fromJson(data);
      } else {
        final data = jsonDecode(response.body);
        showCustomSnackBar(
            data['message'] ?? "Failed to load chapters",
            isError: true);
      }
    } catch (error) {
      print("Error fetching hadith chapters: $error");
    } finally {
      isLoadingHadithChapterBool = false;
      update();
    }
  }

  // ──────────────────────────────────────────────
  //  HADITHS
  // ──────────────────────────────────────────────
  Future<void> getAllHadithData(dynamic bookName, dynamic chapter) async {
    hadithModel = null;
    isLoadingHadithBool = true;
    update();
    try {
      final response = await http.get(
        Uri.parse(
            "${AppConstants.HADITH_BASE_URL}/api/hadiths/?apiKey=$hadithApiKey&book=$bookName&chapter=$chapter&paginate=500"),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        hadithModel = HadithModel.fromJson(data);
      } else {
        showCustomSnackBar("no_hadith_found_in_this_chapter".tr,
            isError: true);
      }
    } catch (error) {
      print("Error fetching hadiths: $error");
    } finally {
      isLoadingHadithBool = false;
      update();
    }
  }
}
