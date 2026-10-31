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
  // Reactive loading flags — Obx will auto-update UI
  final RxBool isLoadingBooks = false.obs;
  final RxBool isLoadingChapters = false.obs;
  final RxBool isLoadingHadith = false.obs;

  // Reactive data holders
  final Rx<HadisBookModel?> booksRx = Rx<HadisBookModel?>(null);
  final Rx<HadithChapterModel?> chaptersRx = Rx<HadithChapterModel?>(null);
  final Rx<HadithModel?> hadithsRx = Rx<HadithModel?>(null);

  // Keep old names for any remaining references
  HadisBookModel? get hadisBookModel => booksRx.value;
  HadithChapterModel? get hadithChapterModel => chaptersRx.value;
  HadithModel? get hadithModel => hadithsRx.value;
  set hadisBookModel(HadisBookModel? v) => booksRx.value = v;
  set hadithChapterModel(HadithChapterModel? v) => chaptersRx.value = v;
  set hadithModel(HadithModel? v) => hadithsRx.value = v;

  String get hadithApiKey => AppConstants.HADITH_API_KEY;

  @override
  void onInit() {
    super.onInit();
    getHadishBookNameData();
  }

  // ──────────────────────────────────────────────
  //  BOOKS
  // ──────────────────────────────────────────────
  Future<void> getHadishBookNameData() async {
    isLoadingBooks.value = true;
    try {
      final response = await http.get(Uri.parse(
          "${AppConstants.HADITH_BASE_URL}/api/books?apiKey=$hadithApiKey"));
      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        booksRx.value = HadisBookModel.fromJson(data);
      } else {
        showCustomSnackBar(data['message'] ?? "Failed to load hadith books",
            isError: true);
      }
    } catch (e) {
      print("Error fetching hadith books: $e");
    } finally {
      isLoadingBooks.value = false;
    }
  }

  // ──────────────────────────────────────────────
  //  CHAPTERS
  // ──────────────────────────────────────────────
  Future<void> getHadithBookChaptersData(dynamic bookSlug) async {
    chaptersRx.value = null;
    isLoadingChapters.value = true;
    try {
      final response = await http.get(Uri.parse(
          "${AppConstants.HADITH_BASE_URL}/api/$bookSlug/chapters?apiKey=$hadithApiKey"));
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        chaptersRx.value = HadithChapterModel.fromJson(data);
      } else {
        final data = jsonDecode(response.body);
        showCustomSnackBar(data['message'] ?? "Failed to load chapters",
            isError: true);
      }
    } catch (e) {
      print("Error fetching hadith chapters: $e");
    } finally {
      isLoadingChapters.value = false;
    }
  }

  // ──────────────────────────────────────────────
  //  HADITHS
  // ──────────────────────────────────────────────
  Future<void> getAllHadithData(dynamic bookName, dynamic chapter) async {
    hadithsRx.value = null;
    isLoadingHadith.value = true;
    try {
      final url =
          "${AppConstants.HADITH_BASE_URL}/api/hadiths/?apiKey=$hadithApiKey&book=$bookName&chapter=$chapter&paginate=500";
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        hadithsRx.value = HadithModel.fromJson(data);
      } else {
        showCustomSnackBar("no_hadith_found_in_this_chapter".tr,
            isError: true);
      }
    } catch (e) {
      print("Error fetching hadiths: $e");
    } finally {
      isLoadingHadith.value = false;
    }
  }
}
