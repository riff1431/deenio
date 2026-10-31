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
  // local variable
  RxBool isLoadingHadithChapter = false.obs;
  RxBool isLoadingHadith = false.obs;

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

  // get hadith book list api call here
  getHadishBookNameData() async {
    isLoadingHadithChapter(true);
    update();
    try {
      final response = await http.get(
        Uri.parse(
          "${AppConstants.HADITH_BASE_URL}/api/books?apiKey=$hadithApiKey",
        ),
      );

      var data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        hadisBookModel = HadisBookModel.fromJson(data);
      } else {
        showCustomSnackBar(data['message'] ?? "Failed to load hadith books",
            isError: true);
      }
    } catch (error) {
      print("Error fetching data: $error");
    } finally {
      isLoadingHadithChapter(false);
      update();
    }
  }

  // get hadith chaptar here
  getHadithBookChaptersData(arguments) async {
    isLoadingHadithChapter(true);
    update();
    try {
      final response = await http.get(
        Uri.parse(
          "${AppConstants.HADITH_BASE_URL}/api/$arguments/chapters?apiKey=$hadithApiKey",
        ),
      );
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        hadithChapterModel = HadithChapterModel.fromJson(data);
      } else {
        var data = jsonDecode(response.body);
        showCustomSnackBar(data['message'] ?? "Failed to load chapters",
            isError: true);
      }
    } catch (error) {
      print("Error fetching data: $error");
    } finally {
      isLoadingHadithChapter(false);
      update();
    }
  }

  // get hadith data here
  getAllHadithData(bookName, chapter) async {
    isLoadingHadith(true);
    update();
    try {
      final response = await http.get(
        Uri.parse(
          "${AppConstants.HADITH_BASE_URL}/api/hadiths/?apiKey=$hadithApiKey&book=$bookName&chapter=$chapter&paginate=500",
        ),
      );
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        hadithModel = HadithModel.fromJson(data);
      } else {
        showCustomSnackBar("no_hadith_found_in_this_chapter".tr, isError: true);
      }
    } catch (error) {
      print("Error fetching data: $error");
    } finally {
      isLoadingHadith(false);
      update();
    }
  }
}
