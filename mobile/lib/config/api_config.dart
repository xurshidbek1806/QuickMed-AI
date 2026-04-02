class ApiConfig {
  /// WiFi: telefon va kompyuter bitta tarmoqda bo'lishi kerak
  /// USB debug: adb reverse tcp:8082 tcp:8082 bo'lsa localhost ishlatiladi
  static const String baseUrl = 'http://192.168.213.3:8082';

  static const String diseases = '/api/diseases';
  static const String chatAnalyze = '/api/chat/analyze';
  static const String chatVoice = '/api/chat/voice';
  static const String banners = '/api/banners';
}
