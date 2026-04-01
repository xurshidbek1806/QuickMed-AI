class ChatMessage {
  final String text;
  final bool isUser;
  final String? type;
  final List<ChatOption>? options;
  final List<DiseaseOption>? diseases;

  ChatMessage({
    required this.text,
    required this.isUser,
    this.type,
    this.options,
    this.diseases,
  });
}

class ChatOption {
  final String label;
  final String value;
  final String? icon;

  ChatOption({required this.label, required this.value, this.icon});
}

class DiseaseOption {
  final String id;
  final String name;
  final String category;

  DiseaseOption({required this.id, required this.name, required this.category});

  factory DiseaseOption.fromJson(Map<String, dynamic> json) {
    return DiseaseOption(
      id: json['id'].toString(),
      name: json['name'] ?? '',
      category: json['category'] ?? '',
    );
  }
}
