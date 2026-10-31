import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:get/get.dart';
import 'package:path/path.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:sqflite/sqflite.dart';
import 'package:zabi/data/model/response/bookmark_model.dart';
import 'package:zabi/view/base/custom_snackbar.dart';

class BookMarkController extends GetxController {
  // Data store by model
  List<BookMark> bookMarks = <BookMark>[];
  // Local variable
  RxBool isLoading = true.obs;

  static const String _webStorageKey = 'web_quran_bookmarks';

  @override
  void onInit() {
    super.onInit();
    loadBookMarks();
  }

  // data load store function
  Future<void> loadBookMarks() async {
    isLoading.value = true;
    try {
      if (kIsWeb) {
        final prefs = Get.isRegistered<SharedPreferences>()
            ? Get.find<SharedPreferences>()
            : await SharedPreferences.getInstance();
        final String? raw = prefs.getString(_webStorageKey);
        if (raw != null && raw.isNotEmpty) {
          final List decoded = jsonDecode(raw);
          bookMarks = decoded
              .map((map) => BookMark.fromMap(Map<String, dynamic>.from(map)))
              .toList();
        } else {
          bookMarks = [];
        }
      } else {
        final Database db = await _initDatabase();
        final List<Map<String, dynamic>> bookMarkMaps =
            await db.query('bookMarks');
        bookMarks = bookMarkMaps.map((map) => BookMark.fromMap(map)).toList();
      }
    } catch (e) {
      if (kDebugMode) {
        print("bookmark load error: $e");
      }
      bookMarks = [];
    }

    if (kDebugMode) {
      print("bookmark Data ==> ${bookMarks.length}");
    }
    isLoading.value = false;
    update();
  }

  // data storage function for native mobile platforms
  Future<Database> _initDatabase() async {
    final String dbPath = await getDatabasesPath();
    final String path = join(dbPath, 'bookMarks.db');
    return openDatabase(
      path,
      version: 1,
      onCreate: (db, version) async {
        await db.execute('''
          CREATE TABLE bookMarks(
            id INTEGER PRIMARY KEY,
            serial_number TEXT,
            sura_name TEXT,
            verses_number TEXT,
            arabic_name TEXT,
            translated_name TEXT,
            page_key TEXT,
            page_number TEXT
          )
        ''');
      },
    );
  }

  // data insert function
  Future<void> insertBookMark(BookMark bookMark) async {
    update();
    try {
      if (kIsWeb) {
        final prefs = Get.isRegistered<SharedPreferences>()
            ? Get.find<SharedPreferences>()
            : await SharedPreferences.getInstance();
        bookMarks.removeWhere((b) => b.id == bookMark.id);
        bookMarks.add(bookMark);
        final listMaps = bookMarks.map((b) => b.toMap()).toList();
        await prefs.setString(_webStorageKey, jsonEncode(listMaps));
      } else {
        final Database db = await _initDatabase();
        await db.insert(
          'bookMarks',
          bookMark.toMap(),
          conflictAlgorithm: ConflictAlgorithm.replace,
        );
      }
      await loadBookMarks();
      showCustomSnackBar("book_mark_successfully_added".tr, isError: false);
    } catch (e) {
      if (kDebugMode) {
        print("bookmark insert error: $e");
      }
    }
    update();
  }

  // update function
  Future<void> updateBookMark(BookMark bookMark) async {
    try {
      if (kIsWeb) {
        final prefs = Get.isRegistered<SharedPreferences>()
            ? Get.find<SharedPreferences>()
            : await SharedPreferences.getInstance();
        final index = bookMarks.indexWhere((b) => b.id == bookMark.id);
        if (index != -1) {
          bookMarks[index] = bookMark;
        } else {
          bookMarks.add(bookMark);
        }
        final listMaps = bookMarks.map((b) => b.toMap()).toList();
        await prefs.setString(_webStorageKey, jsonEncode(listMaps));
      } else {
        final Database db = await _initDatabase();
        await db.update(
          'bookMarks',
          bookMark.toMap(),
          where: 'id = ?',
          whereArgs: [bookMark.id],
        );
      }
      await loadBookMarks();
    } catch (e) {
      if (kDebugMode) {
        print("bookmark update error: $e");
      }
    }
  }

  // delete function
  Future<void> deleteBookMark(int id) async {
    try {
      if (kIsWeb) {
        final prefs = Get.isRegistered<SharedPreferences>()
            ? Get.find<SharedPreferences>()
            : await SharedPreferences.getInstance();
        bookMarks.removeWhere((b) => b.id == id);
        final listMaps = bookMarks.map((b) => b.toMap()).toList();
        await prefs.setString(_webStorageKey, jsonEncode(listMaps));
      } else {
        final Database db = await _initDatabase();
        await db.delete('bookMarks', where: 'id = ?', whereArgs: [id]);
      }
      await loadBookMarks();
      showCustomSnackBar("book_mark_successfully_deleted".tr, isError: false);
    } catch (e) {
      if (kDebugMode) {
        print("bookmark delete error: $e");
      }
    }
  }
}
