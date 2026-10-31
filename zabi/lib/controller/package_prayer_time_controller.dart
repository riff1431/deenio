// ignore_for_file: avoid_print, deprecated_member_use, strict_top_level_inference

import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_timezone/flutter_timezone.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:zabi/data/api/api_client.dart';
import 'package:zabi/data/model/response/city_model.dart';
import 'package:zabi/data/model/response/todays_prayer_time_model.dart';
import 'package:zabi/util/app_constants.dart';

class PrayerTimeController extends GetxController implements GetxService {
  final ApiClient apiClient;
  PrayerTimeController({required this.apiClient});

  // local variable
  RxBool isprayerTimeLoading = false.obs;
  PrayerTimeModel? prayerTimeModel;

  // Prayer Time
  TextEditingController citySearchController = TextEditingController();
  final RxString search = ''.obs;

  RxString fajrStart = "--".obs;
  RxString sunriseStart = "--".obs;
  RxString dhuhrStart = "--".obs;
  RxString asrStart = "--".obs;
  RxString magribStart = "--".obs;
  RxString ishaStart = "--".obs;
  RxString currentWaktTime = "--".obs;
  RxString currentWaqtName = "--".obs;

  RxString currentAddress = '--'.obs;
  RxString saveAddress = '--'.obs;
  RxBool isLocationDenied = false.obs;

  RxBool isPrayerTimes = false.obs;
  double? latitude;
  double? longitude;
  String? saveLocalStoreCity;

  Future<void> getLocation() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      saveLocalStoreCity = prefs.getString(AppConstants.saveCityName);

      if (!kIsWeb) {
        bool serviceEnabled = false;
        try {
          serviceEnabled = await Geolocator.isLocationServiceEnabled();
        } catch (_) {
          serviceEnabled = false;
        }

        if (!serviceEnabled) {
          try {
            await Geolocator.openLocationSettings();
          } catch (_) {}
        }

        LocationPermission permission = await Geolocator.checkPermission();
        if (permission == LocationPermission.denied) {
          permission = await Geolocator.requestPermission();
          if (permission == LocationPermission.denied) {
            await prefs.setBool("isLocationDenied", true);
            isLocationDenied.value = true;
            final isPrayerTme = prefs.getBool(AppConstants.isPrayerTme) ?? false;
            fetchPrayerTime(
              reload: false,
              isManualPrayerTme: isPrayerTme,
              manualCity: saveLocalStoreCity ?? "Dhaka",
            );
            return;
          }
        }

        if (permission == LocationPermission.deniedForever) {
          await prefs.setBool("isLocationDenied", true);
          isLocationDenied.value = true;
          final isPrayerTme = prefs.getBool(AppConstants.isPrayerTme) ?? false;
          fetchPrayerTime(
            reload: false,
            isManualPrayerTme: isPrayerTme,
            manualCity: saveLocalStoreCity ?? "Dhaka",
          );
          return;
        }
      }

      Position? position;
      try {
        position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.medium,
          timeLimit: Duration(seconds: kIsWeb ? 2 : 4),
        );
      } catch (e) {
        debugPrint("Location fetch timeout or error: $e");
        if (!kIsWeb) {
          try {
            position = await Geolocator.getLastKnownPosition();
          } catch (_) {}
        }
      }

      if (position != null) {
        latitude = position.latitude;
        longitude = position.longitude;

        if (!kIsWeb) {
          try {
            List<Placemark> addressList = await placemarkFromCoordinates(
              position.latitude,
              position.longitude,
            );
            if (addressList.isNotEmpty) {
              final callAddress = addressList.first;
              currentAddress.value = GetPlatform.isIOS
                  ? (callAddress.administrativeArea ?? '')
                  : (callAddress.subAdministrativeArea ?? callAddress.locality ?? '');
            }
          } catch (_) {}
        }
      } else {
        latitude ??= 23.8103;
        longitude ??= 90.4125;
      }

      if (currentAddress.value.isEmpty || currentAddress.value == '--') {
        currentAddress.value = saveLocalStoreCity ?? (kIsWeb ? 'Dhaka' : 'Dhaka');
      }

      final isPrayerTme = prefs.getBool(AppConstants.isPrayerTme) ?? false;
      isPrayerTimes.value = isPrayerTme;

      fetchPrayerTime(
        reload: false,
        isManualPrayerTme: isPrayerTme,
        manualCity: saveLocalStoreCity ?? currentAddress.value,
      );

      update();
    } catch (e) {
      debugPrint("Error in getLocation: $e");
      fetchPrayerTime(
        reload: false,
        isManualPrayerTme: false,
        manualCity: "Dhaka",
      );
    }
  }

  // Calculation Methods
  final List<Map<String, String>> _calculationMethod = [
    {'id': '0', 'value': 'JAFARI'},
    {'id': '1', 'value': 'KARACHI'},
    {'id': '2', 'value': 'ISNA'},
    {'id': '3', 'value': 'MWL'},
    {'id': '4', 'value': 'MAKKAH'},
    {'id': '5', 'value': 'EGYPT'},
    {'id': '7', 'value': 'TEHRAN'},
    {'id': '8', 'value': 'GULF'},
    {'id': '9', 'value': 'KUWAIT'},
    {'id': '10', 'value': 'QATAR'},
    {'id': '11', 'value': 'SINGAPORE'},
    {'id': '12', 'value': 'FRANCE'},
    {'id': '13', 'value': 'TURKEY'},
    {'id': '14', 'value': 'RUSSIA'},
    {'id': '15', 'value': 'MOONSIGHTING'},
    {'id': '16', 'value': 'DUBAI'},
    {'id': '17', 'value': 'JAKIM'},
    {'id': '18', 'value': 'TUNISIA'},
    {'id': '19', 'value': 'ALGERIA'},
    {'id': '20', 'value': 'KEMENAG'},
    {'id': '21', 'value': 'MOROCCO'},
    {'id': '22', 'value': 'PORTUGAL'},
    {'id': '23', 'value': 'JORDAN'},
  ];

  List<Map<String, String>> get calculationMethod => _calculationMethod;
  String? _selectedCalculationMethod;
  String? get selectedCalculationMethod => _selectedCalculationMethod;

  // Madhab List
  final List<Map<String, String>> _prayerMadhabList = [
    {'id': 'STANDARD', 'value': 'STANDARD'},
    {'id': 'HANAFI', 'value': 'HANAFI'},
  ];

  List<Map<String, String>> get prayerMadhabList => _prayerMadhabList;

  String? _selectedPrayerMadhab;
  String? get selectedPrayerMadhab => _selectedPrayerMadhab;

  /// Load the settings from local storage or set default values.
  Future<void> loadPrayerTimeSettings() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    isManualPrayerTime.value =
        prefs.getBool(AppConstants.IS_MANUAL_PRAYER_TIME) ?? false;

    // Load Calculation Method
    String? savedMethod = prefs.getString('selectedCalculationMethod');
    if (savedMethod == null) {
      Map<String, String> defaultMethod = {'id': '1', 'value': 'KARACHI'};
      await prefs.setString('selectedCalculationMethod', defaultMethod['id']!);
      _selectedCalculationMethod = defaultMethod['id'];
    } else {
      _selectedCalculationMethod = savedMethod;
    }

    // Load Prayer Madhab
    String? savedMadhab = prefs.getString('selectedPrayerMadhab');
    if (savedMadhab == null) {
      Map<String, String> defaultMadhab = {
        'id': 'STANDARD',
        'value': 'STANDARD',
      };
      await prefs.setString('selectedPrayerMadhab', defaultMadhab['id']!);
      _selectedPrayerMadhab = defaultMadhab['id'];
    } else {
      _selectedPrayerMadhab = savedMadhab;
    }

    update();
  }

  /// Set a new calculation method and save it in local storage.
  Future<void> setSelectedCalculationMethod(String? value) async {
    if (value != null) {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setString('selectedCalculationMethod', value);
      _selectedCalculationMethod = value;
      update();
    }
  }

  /// Set a new prayer Madhab and save it in local storage.
  Future<void> setSelectedPrayerMadhab(String? value) async {
    if (value != null) {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setString('selectedPrayerMadhab', value);
      _selectedPrayerMadhab = value;
      update();
    }
  }

  var isManualPrayerTime = false.obs;
  // get prayer time from here
  Future<void> fetchPrayerTime({
    bool reload = true,
    bool isManualPrayerTme = false,
    String? manualCity,
  }) async {
    try {
      await loadPrayerTimeSettings();
      if (reload) {
        isprayerTimeLoading(true);
      }

      isManualPrayerTime.value = isManualPrayerTme;

      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setBool(
        AppConstants.IS_MANUAL_PRAYER_TIME,
        isManualPrayerTme,
      );

      saveLocalStoreCity = prefs.getString(AppConstants.saveCityName);
      saveAddress.value = saveLocalStoreCity ?? "";

      Position? position;
      if (!kIsWeb) {
        try {
          position = await Geolocator.getCurrentPosition(
            desiredAccuracy: LocationAccuracy.medium,
            timeLimit: const Duration(seconds: 4),
          );
        } catch (e) {
          try {
            position = await Geolocator.getLastKnownPosition();
          } catch (_) {}
        }
      }

      double lat = position?.latitude ?? (latitude ?? 23.8103);
      double lng = position?.longitude ?? (longitude ?? 90.4125);
      latitude = lat;
      longitude = lng;

      if (position != null && !kIsWeb) {
        try {
          List<Placemark> addressList = await placemarkFromCoordinates(
            lat,
            lng,
          );
          if (addressList.isNotEmpty) {
            final callAddress = addressList.first;
            currentAddress.value = GetPlatform.isIOS
                ? (callAddress.administrativeArea ?? '')
                : (callAddress.subAdministrativeArea ?? callAddress.locality ?? '');
          }
        } catch (_) {}
      }

      if (currentAddress.value.isEmpty || currentAddress.value == '--') {
        currentAddress.value = saveLocalStoreCity ?? (kIsWeb ? 'Dhaka' : 'Dhaka');
      }

      DateTime currentDate = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(currentDate);

      var prayerMethod = _selectedCalculationMethod ?? '1';
      var school = _selectedPrayerMadhab ?? 'STANDARD';

      String timezone = 'Asia/Dhaka';
      try {
        timezone = await FlutterTimezone.getLocalTimezone();
      } catch (_) {
        timezone = 'Asia/Dhaka';
      }
      if (timezone == 'Bangladesh Standard Time' || timezone.isEmpty) {
        timezone = 'Asia/Dhaka';
      }

      final cityParam = isManualPrayerTme
          ? (manualCity != null && manualCity.isNotEmpty && manualCity != '--' ? manualCity : currentAddress.value)
          : currentAddress.value;

      final response = await apiClient.postData(
        AppConstants.TODAYS_PRAYER_TIME,
        {
          "type": isManualPrayerTme ? "manual" : "automatic",
          "date": formattedDate,
          "city": cityParam == '--' ? 'Dhaka' : cityParam,
          "lat": "$lat",
          "lng": "$lng",
          "prayer_method": prayerMethod,
          "school": school,
          "timezone": timezone,
        },
      );

      if (response.statusCode == 200 && response.body != null) {
        prayerTimeModel = PrayerTimeModel.fromJson(response.body);
        prayerNameAndTimes();
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching data: $e");
      }
    } finally {
      isprayerTimeLoading(false);
      update();
    }
  }

  // Get Prayer Owakt Function
  prayerNameAndTimes() {
    String currentTime = DateFormat.Hms().format(DateTime.now());
    var finalCurrentTime = DateTime.parse('2000-01-01 $currentTime');
    var apiwaktTime = prayerTimeModel?.data;
    if (apiwaktTime == null) return;

    fajrStart.value = apiwaktTime.fajrStart ?? "--";
    sunriseStart.value = apiwaktTime.sunrise ?? "--";
    dhuhrStart.value = apiwaktTime.zuhrStart ?? "--";
    asrStart.value = apiwaktTime.asrStart ?? "--";
    magribStart.value = apiwaktTime.maghribStart ?? "--";
    ishaStart.value = apiwaktTime.ishaStart ?? "--";

    DateTime? safeParseTime(String? timeStr) {
      if (timeStr == null || !timeStr.contains(':')) return null;
      try {
        final parts = timeStr.trim().split(':');
        final h = parts[0].padLeft(2, '0');
        final m = parts.length > 1 ? parts[1].padLeft(2, '0') : '00';
        return DateTime.parse('2000-01-01 $h:$m:00');
      } catch (_) {
        return null;
      }
    }

    try {
      final fajrDt = safeParseTime(apiwaktTime.fajrStart);
      final sunriseDt = safeParseTime(apiwaktTime.sunrise);
      final zuhrDt = safeParseTime(apiwaktTime.zuhrStart);
      final asrDt = safeParseTime(apiwaktTime.asrStart);
      final maghribDt = safeParseTime(apiwaktTime.maghribStart);
      final ishaDt = safeParseTime(apiwaktTime.ishaStart);

      if (fajrDt != null && finalCurrentTime.isBefore(fajrDt)) {
        currentWaqtName.value = "fajr".tr;
        currentWaktTime.value = apiwaktTime.fajrStart.toString();
      } else if (sunriseDt != null && finalCurrentTime.isBefore(sunriseDt)) {
        currentWaqtName.value = "sunrise".tr;
        currentWaktTime.value = apiwaktTime.sunrise.toString();
      } else if (zuhrDt != null && finalCurrentTime.isBefore(zuhrDt)) {
        currentWaqtName.value = DateTime.now().weekday == DateTime.friday
            ? "jumuah".tr
            : "dhuhr".tr;
        currentWaktTime.value = apiwaktTime.zuhrStart.toString();
      } else if (asrDt != null && finalCurrentTime.isBefore(asrDt)) {
        currentWaqtName.value = "asr".tr;
        currentWaktTime.value = apiwaktTime.asrStart.toString();
      } else if (maghribDt != null && finalCurrentTime.isBefore(maghribDt)) {
        currentWaqtName.value = "magrib".tr;
        currentWaktTime.value = apiwaktTime.maghribStart.toString();
      } else if (ishaDt != null && finalCurrentTime.isBefore(ishaDt)) {
        currentWaqtName.value = "isha".tr;
        currentWaktTime.value = apiwaktTime.ishaStart.toString();
      } else {
        currentWaqtName.value = "fajr".tr;
        currentWaktTime.value = apiwaktTime.fajrStart.toString();
      }
    } catch (_) {}
    update();
  }

  RxBool isCityListLoading = false.obs;
  CityesModel? cityModelData;

  Future<void> cityCategoryListData() async {
    try {
      isCityListLoading(true);
      update();

      final response = await apiClient.getData(AppConstants.CITY_LIST);

      if (response.statusCode == 200) {
        cityModelData = CityesModel.fromJson(response.body);
      }
    } catch (e) {
      if (kDebugMode) {
        print("Error fetching data: $e");
      }
    } finally {
      isCityListLoading(false);
      update();
    }
  }

  // 12 or 24 hour format

  RxBool is24HourFormat = true.obs;

  Future<void> loadSwitchValue() async {
    final prefs = await SharedPreferences.getInstance();
    is24HourFormat.value = prefs.getBool('is24HrFormat') ?? true;
  }

  Future<void> updateSwitchValue(bool value) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('is24HrFormat', value);
    is24HourFormat.value = value;
  }
}
