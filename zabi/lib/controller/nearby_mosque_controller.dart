// ignore_for_file: deprecated_member_use

import 'dart:async';
import 'dart:convert';
import 'dart:math';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'package:permission_handler/permission_handler.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:zabi/util/app_constants.dart';
import 'package:zabi/view/base/custom_snackbar.dart';

class NearbyMosqueController extends GetxController {
  GlobalKey<FormState> nearbyMosqueFormKey = GlobalKey<FormState>();

  // Get Address Variable =====>
  var location = '';
  double? latitude;
  double? longitude;
  RxString currentAddress = '--'.obs;
  late StreamSubscription<Position> streamSubscription;
  late LocationSettings locationSettings;
  RxBool isLocationDenied = false.obs;

  // local variable
  var userLocation = ''.obs;
  var places = [].obs;
  RxBool isLoading = false.obs;
  List<String> splitKm = [];
  int kmToRadious = 2;

  var km = '2  KM'.obs;

  @override
  void onInit() {
    super.onInit();
    loadKmDropdownValue(km.value);
  }

  Future<void> getLocation() async {
    bool serviceEnabled = false;
    SharedPreferences prefs = await SharedPreferences.getInstance();
    LocationPermission permission;

    // Check if location services are enabled
    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      await Geolocator.openLocationSettings();
      return;
    }

    // Check location permission status
    permission = await Geolocator.checkPermission();

    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        showCustomSnackBar(
          "for_getting_Automatic_Prayer_Time_Nearby_Mosque_Qibla_Compass_need_to_enable_location_permission"
              .tr,
          isError: true,
        );

        await prefs.setBool("isLocationDenied", true);
        bool? storedFontSize = prefs.getBool("isLocationDenied");
        if (storedFontSize != null) {
          isLocationDenied.value = storedFontSize;
        }

        return;
      }
    } else if (permission == LocationPermission.deniedForever) {
      showCustomSnackBar(
        "for_getting_Automatic_Prayer_Time_Nearby_Mosque_Qibla_Compass_need_to_enable_location_permission"
            .tr,
        isError: true,
        onTap: () => openAppSettings(),
      );

      await prefs.setBool("isLocationDenied", true);
      bool? storedFontSize = prefs.getBool("isLocationDenied");
      if (storedFontSize != null) {
        isLocationDenied.value = storedFontSize;
      }
      return;
    }

    if (serviceEnabled) {
      try {
        Position position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high,
        );
        userLocation.value = '${position.latitude},${position.longitude}';
        searchNearbyPlaces();
        update();
      } catch (e) {
        await prefs.setBool("isLocationDenied", true);
        bool? storedFontSize = prefs.getBool("isLocationDenied");
        if (storedFontSize != null) {
          isLocationDenied.value = storedFontSize;
        }
        showCustomSnackBar(
          "for_getting_Automatic_Prayer_Time_Nearby_Mosque_Qibla_Compass_need_to_enable_location_permission"
              .tr,
          isError: true,
          onTap: () => openAppSettings(),
        );
      }
    }
  }

  // ── High-Speed Multi-Tier Search Engine ──
  void searchNearbyPlaces() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.setBool("isLocationDenied", false);
    bool? storedFontSize = prefs.getBool("isLocationDenied");
    if (storedFontSize != null) {
      isLocationDenied.value = storedFontSize;
    }

    try {
      isLoading(true);

      // Parse user's coordinates
      final parts = userLocation.value.split(',');
      if (parts.length < 2) {
        isLoading(false);
        return;
      }
      final double userLat = double.tryParse(parts[0].trim()) ?? 23.8103;
      final double userLng = double.tryParse(parts[1].trim()) ?? 90.4125;

      // ── Tier 1: Deenio Backend Proxy API (with server-side OSM fallback) ──
      bool loadedFromBackend = false;
      try {
        final backendUrl =
            '${AppConstants.BASE_URL}${AppConstants.NEARBY_MOSQUES_BACKEND_URL}?lat=$userLat&lng=$userLng&radius=$kmToRadious';
        final response = await http
            .get(Uri.parse(backendUrl))
            .timeout(const Duration(seconds: 4));

        if (response.statusCode == 200) {
          final decoded = json.decode(response.body);
          if (decoded['status'] == true &&
              decoded['results'] != null &&
              (decoded['results'] as List).isNotEmpty) {
            places.value = decoded['results'];
            loadedFromBackend = true;
            if (kDebugMode) {
              print(
                  "Nearby Mosques: Successfully loaded ${places.length} mosques from backend (${decoded['source']}).");
            }
          }
        }
      } catch (_) {
        // Fallback gracefully without console noise
      }

      if (loadedFromBackend) {
        isLoading(false);
        return;
      }

      // ── Tier 2: OpenStreetMap Nominatim (High-speed pre-indexed search, 300-600ms) ──
      bool loadedFromNominatim = false;
      try {
        final double dLat = kmToRadious / 111.0;
        final double cosLat = max(0.1, cos(userLat * pi / 180.0));
        final double dLng = kmToRadious / (111.0 * cosLat);
        final double left = userLng - dLng;
        final double right = userLng + dLng;
        final double top = userLat + dLat;
        final double bottom = userLat - dLat;

        final nomUrl =
            'https://nominatim.openstreetmap.org/search?q=mosque&format=json&bounded=1&viewbox=$left,$top,$right,$bottom&limit=30';
        final nomResponse = await http.get(
          Uri.parse(nomUrl),
          headers: {'User-Agent': 'DeenioApp/1.0 (https://deenio.huipper.com; support@deenio.com)'},
        ).timeout(const Duration(seconds: 4));

        if (nomResponse.statusCode == 200) {
          final nomDecoded = json.decode(nomResponse.body) as List?;
          if (nomDecoded != null && nomDecoded.isNotEmpty) {
            List<Map<String, dynamic>> parsedList = [];
            for (var item in nomDecoded) {
              final double lat = double.tryParse('${item['lat']}') ?? 0.0;
              final double lng = double.tryParse('${item['lon']}') ?? 0.0;
              if (lat == 0.0 || lng == 0.0) continue;

              final double distanceMeters =
                  Geolocator.distanceBetween(userLat, userLng, lat, lng);
              String name = item['name'] ?? '';
              if (name.isEmpty && item['display_name'] != null) {
                name = item['display_name'].toString().split(',')[0].trim();
              }
              if (name.isEmpty) name = 'Mosque (মসজিদ)';

              parsedList.add({
                'name': name,
                'vicinity': item['display_name'] ?? 'Nearby Mosque',
                'lat': lat,
                'lng': lng,
                'distance_meters': distanceMeters.round(),
                'distance_text': formatDistance(distanceMeters),
                'geometry': {
                  'location': {'lat': lat, 'lng': lng}
                }
              });
            }

            parsedList.sort((a, b) => (a['distance_meters'] as int)
                .compareTo(b['distance_meters'] as int));
            places.value = parsedList;
            loadedFromNominatim = true;
            if (kDebugMode) {
              print(
                  "Nearby Mosques: Successfully loaded ${places.length} mosques via OpenStreetMap Nominatim.");
            }
          }
        }
      } catch (_) {
        // Proceed to tertiary fallback if needed
      }

      if (loadedFromNominatim) {
        isLoading(false);
        return;
      }

      // ── Tier 3: OpenStreetMap Overpass (Deep Relational Fallback) ──
      try {
        final int radiusMeters = kmToRadious * 1000;
        final query =
            '[out:json][timeout:6];(node["amenity"="place_of_worship"]["religion"="muslim"](around:$radiusMeters,$userLat,$userLng);way["amenity"="place_of_worship"]["religion"="muslim"](around:$radiusMeters,$userLat,$userLng););out center 35;';

        final osmResponse = await http.post(
          Uri.parse('https://overpass-api.de/api/interpreter'),
          headers: {'User-Agent': 'DeenioApp/1.0 (https://deenio.huipper.com)'},
          body: {'data': query},
        ).timeout(const Duration(seconds: 5));

        if (osmResponse.statusCode == 200) {
          final osmDecoded = json.decode(osmResponse.body);
          final elements = osmDecoded['elements'] as List?;
          if (elements != null && elements.isNotEmpty) {
            List<Map<String, dynamic>> parsedList = [];
            for (var el in elements) {
              final tags = el['tags'] ?? {};
              final double lat = (el['lat'] ?? (el['center']?['lat'] ?? 0.0))
                  .toDouble();
              final double lng = (el['lon'] ?? (el['center']?['lon'] ?? 0.0))
                  .toDouble();
              if (lat == 0.0 || lng == 0.0) continue;

              final double distanceMeters =
                  Geolocator.distanceBetween(userLat, userLng, lat, lng);
              final String name = tags['name'] ??
                  tags['name:bn'] ??
                  tags['name:en'] ??
                  'Jame Masjid';
              final String vicinity = tags['addr:street'] ??
                  tags['addr:suburb'] ??
                  tags['addr:city'] ??
                  tags['name:en'] ??
                  'Nearby Mosque';

              parsedList.add({
                'name': name,
                'vicinity': vicinity,
                'lat': lat,
                'lng': lng,
                'distance_meters': distanceMeters.round(),
                'distance_text': formatDistance(distanceMeters),
                'geometry': {
                  'location': {'lat': lat, 'lng': lng}
                }
              });
            }

            parsedList.sort((a, b) => (a['distance_meters'] as int)
                .compareTo(b['distance_meters'] as int));
            places.value = parsedList;
          }
        }
      } catch (_) {}
    } catch (e) {
      if (kDebugMode) {
        print("Near By Mosque General Error: $e");
      }
    } finally {
      isLoading(false);
      update();
    }
  }

  String formatDistance(double meters) {
    if (meters < 1000) {
      return "${meters.round()} m away";
    }
    return "${(meters / 1000).toStringAsFixed(1)} km away";
  }

  // search by distance function
  void loadKmDropdownValue(String newValue) async {
    km.value = newValue;
    splitKm = km.split(" ");
    kmToRadious = int.parse(splitKm[0]);
    update();
  }
}
