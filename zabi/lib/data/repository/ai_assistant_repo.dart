import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:zabi/util/app_constants.dart';

class AiAssistantRepo {
  Future<String> askAI({required String question}) async {
    final response = await http.post(
      Uri.parse('${AppConstants.BASE_URL}/api/ai/chat'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({"message": question}),
    );

    if (response.statusCode != 200) {
      throw Exception("API Error: ${response.statusCode}");
    }

    final data = jsonDecode(response.body);

    if (data['success'] != true) {
      throw Exception(data['error'] ?? 'Unknown error');
    }

    return data['reply'];
  }
}