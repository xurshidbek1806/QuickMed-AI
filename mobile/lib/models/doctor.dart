class Doctor {
  final String id;
  final String fullName;
  final String specialty;
  final String? phone;
  final String? photo;
  final String clinicName;
  final int? experience;
  final String? locationUrl;

  Doctor({
    required this.id,
    required this.fullName,
    required this.specialty,
    this.phone,
    this.photo,
    required this.clinicName,
    this.experience,
    this.locationUrl,
  });

  factory Doctor.fromJson(Map<String, dynamic> json) {
    return Doctor(
      id: json['id'].toString(),
      fullName: json['name'] ?? json['full_name'] ?? '',
      specialty: json['specialization'] ?? json['specialty'] ?? '',
      phone: json['phone_number'] ?? json['phone'],
      photo: json['photo'],
      clinicName: json['clinic_name'] ?? json['clinic']?['name'] ?? '',
      experience: json['experience'] != null
          ? int.tryParse(json['experience'].toString())
          : null,
      locationUrl: json['location_url'],
    );
  }
}

class AnalysisResult {
  final String analysis;
  final List<Doctor> doctors;
  final String diseaseName;

  AnalysisResult({
    required this.analysis,
    required this.doctors,
    required this.diseaseName,
  });

  factory AnalysisResult.fromJson(Map<String, dynamic> json) {
    return AnalysisResult(
      analysis: json['analysis'] ?? '',
      doctors: (json['doctors'] as List?)
              ?.map((d) => Doctor.fromJson(d))
              .toList() ??
          [],
      diseaseName: json['disease']?['name'] ?? json['disease_name'] ?? '',
    );
  }
}
