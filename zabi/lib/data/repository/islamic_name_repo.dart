import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:zabi/util/app_constants.dart';
import '../model/response/islamic_name_model.dart';

class IslamicNameRepo {
  Future<List<IslamicName>> generateNames({
    required String gender,
    required String origin,
    String? meaningTheme,
    String? startsWithLetter,
    int count = 15,
  }) async {
    final response = await http.post(
      Uri.parse('${AppConstants.BASE_URL}/api/ai/generate-names'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        "gender": gender == 'any' ? 'boy' : gender,
        "theme": meaningTheme,
      }),
    );

    if (response.statusCode != 200) {
      final err = jsonDecode(response.body);
      debugPrint("Error: $err");
      throw Exception(
        err['error'] ?? 'API error ${response.statusCode}',
      );
    }

    final Map<String, dynamic> data = jsonDecode(response.body);
    debugPrint("RAW RESPONSE: ${response.body}");

    if (data['success'] == true && data['names'] != null) {
      final List<dynamic> namesList = data['names'] as List<dynamic>;
      return namesList.map((e) {
        // Map backend response format to IslamicName model
        final mapped = <String, dynamic>{
          'arabic': e['arabic'] ?? '',
          'english': e['name'] ?? e['english'] ?? '',
          'meaning': e['meaning'] ?? '',
          'origin': e['origin'] ?? origin,
          'gender': gender == 'any' ? 'boy' : gender,
          'quranicReference': e['quranicReference'],
        };
        return IslamicName.fromJson(mapped);
      }).toList();
    }

    debugPrint("No names returned. Full data: $data");
    return [];
  }
}
