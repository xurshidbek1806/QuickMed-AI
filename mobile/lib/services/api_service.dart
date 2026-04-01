import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import '../models/doctor.dart';
import '../models/message.dart';

class ApiService {
  final String baseUrl;

  ApiService({String? baseUrl}) : baseUrl = baseUrl ?? ApiConfig.baseUrl;

  Future<List<DiseaseOption>> fetchDiseases({String? ageCategory}) async {
    final uri = Uri.parse('$baseUrl/api/diseases').replace(
      queryParameters: {
        if (ageCategory != null) 'age_category': ageCategory,
      },
    );
    final response = await http.get(uri);

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final list = data['data'] as List? ?? data['diseases'] as List? ?? data as List? ?? [];
      return list.map((d) => DiseaseOption.fromJson(d)).toList();
    }
    return [];
  }

  Future<AnalysisResult?> analyzeSymptoms({
    required String gender,
    required String age,
    required String diseaseId,
    required String symptoms,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/api/chat/analyze'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: jsonEncode({
        'gender': gender,
        'age': age,
        'age_category': age,
        'disease_id': diseaseId,
        'symptoms': symptoms,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return AnalysisResult.fromJson(data);
    }
    return null;
  }

  Future<String?> transcribeVoice(String filePath) async {
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/api/chat/voice'),
    );
    request.files.add(await http.MultipartFile.fromPath('audio', filePath));

    final response = await request.send();
    if (response.statusCode == 200) {
      final body = await response.stream.bytesToString();
      final data = jsonDecode(body);
      return data['text'];
    }
    return null;
  }
}
