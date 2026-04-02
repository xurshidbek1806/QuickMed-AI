import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:record/record.dart';
import 'package:path_provider/path_provider.dart';
import 'package:permission_handler/permission_handler.dart';
import '../config/theme.dart';
import '../models/message.dart';
import '../models/doctor.dart';
import '../services/api_service.dart';
import '../widgets/chat_bubble.dart';
import '../widgets/option_chip.dart';
import '../widgets/step_indicator.dart';
import '../widgets/gradient_button.dart';

class ChatScreen extends StatefulWidget {
  const ChatScreen({super.key});

  @override
  State<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> with TickerProviderStateMixin {
  final ScrollController _scrollController = ScrollController();
  final TextEditingController _textController = TextEditingController();
  final ApiService _api = ApiService();
  final AudioRecorder _recorder = AudioRecorder();

  int _step = 0; // 0=gender, 1=age, 2=disease, 3=symptoms, 4=result
  String? _gender;
  String? _ageCategory;
  String? _diseaseId;
  bool _isLoading = false;

  final List<ChatMessage> _messages = [];
  List<DiseaseOption> _diseases = [];
  List<DiseaseOption> _filteredDiseases = [];
  AnalysisResult? _result;
  bool _isRecording = false;

  final List<String> _stepLabels = [
    'Jins',
    'Yosh',
    'Kasallik',
    'Simptom',
    'Natija',
  ];

  static const _ageCategories = [
    {'value': 'godak', 'label': 'Chaqaloq', 'desc': '0-1 yosh'},
    {'value': 'bola', 'label': 'Bola', 'desc': '1-12 yosh'},
    {'value': 'osmir', 'label': 'O\'smir', 'desc': '12-18 yosh'},
    {'value': 'yoshlar', 'label': 'Yoshlar', 'desc': '18-30 yosh'},
    {'value': 'orta_yosh', 'label': 'O\'rta yosh', 'desc': '30-60 yosh'},
    {'value': 'keksalar', 'label': 'Keksalar', 'desc': '60+ yosh'},
  ];

  @override
  void initState() {
    super.initState();
    _textController.addListener(_onTextChanged);
    _addBotMessage('Assalomu alaykum!\nMen QuickMedAI — sun\'iy intellekt yordamchingizman. Keling, avval jinsingizni tanlang.');
  }

  void _onTextChanged() {
    if (_step == 2 && _diseases.isNotEmpty) {
      final query = _textController.text.trim().toLowerCase();
      setState(() {
        if (query.length < 2) {
          _filteredDiseases = [];
        } else {
          final words = query.split(RegExp(r'\s+')).where((w) => w.length >= 2).toList();
          if (words.isEmpty) {
            _filteredDiseases = [];
          } else {
            _filteredDiseases = _diseases.where((d) {
              final name = d.name.toLowerCase();
              final cat = d.category.toLowerCase();
              return words.any((w) => name.contains(w) || cat.contains(w));
            }).toList();
          }
        }
      });
    }
  }

  void _addBotMessage(String text) {
    setState(() {
      _messages.add(ChatMessage(text: text, isUser: false));
    });
    _scrollToBottom();
  }

  void _addUserMessage(String text) {
    setState(() {
      _messages.add(ChatMessage(text: text, isUser: true));
    });
    _scrollToBottom();
  }

  void _scrollToBottom() {
    Future.delayed(const Duration(milliseconds: 100), () {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent + 100,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOutCubic,
        );
      }
    });
  }

  void _selectGender(String gender) {
    _gender = gender;
    _addUserMessage(gender == 'male' ? 'Erkak' : 'Ayol');
    setState(() => _step = 1);
    _addBotMessage('Yaxshi! Endi yosh guruhingizni tanlang.');
  }

  void _selectAge(String category) {
    final cat = _ageCategories.firstWhere((c) => c['value'] == category);
    _ageCategory = category;
    _addUserMessage('${cat['label']} (${cat['desc']})');
    setState(() {
      _step = 2;
      _diseases = [];
    });
    _addBotMessage('Kasalliklar yuklanmoqda...');
    _loadDiseasesForAge(category);
  }

  Future<void> _loadDiseasesForAge(String ageCategory) async {
    try {
      final loaded = await _api.fetchDiseases(ageCategory: ageCategory);
      setState(() {
        _diseases = loaded;
        if (_messages.isNotEmpty && _messages.last.text == 'Kasalliklar yuklanmoqda...') {
          _messages.removeLast();
        }
      });
      _addBotMessage('Qaysi kasallik bo\'yicha murojaat qilmoqchisiz?');
    } catch (_) {
      _addBotMessage('Kasalliklarni yuklashda xatolik. Qayta urinib ko\'ring.');
    }
  }

  void _selectDisease(DiseaseOption disease) {
    _diseaseId = disease.id;
    _addUserMessage(disease.name);
    _textController.clear();
    setState(() {
      _filteredDiseases = [];
      _step = 3;
    });
    _addBotMessage('Endi simptomlaringizni batafsil yozib bering yoki mikrofon tugmasini bosib ovoz orqali aytib bering.');
  }

  void _handleInputSubmit() {
    if (_step == 2) {
      _handleDiseaseInput();
    } else if (_step == 3) {
      _submitSymptoms();
    }
  }

  void _handleDiseaseInput() {
    final text = _textController.text.trim();
    if (text.isEmpty) return;

    // Split input into words and match each word against disease names
    final words = text.toLowerCase().split(RegExp(r'\s+')).where((w) => w.length >= 3).toList();
    if (words.isEmpty) {
      _addUserMessage(text);
      _textController.clear();
      _addBotMessage('Iltimos, kasallik nomini kiriting (kamida 3 harf).');
      return;
    }

    // Score diseases by how many words match
    final scored = <DiseaseOption, int>{};
    for (final d in _diseases) {
      final name = d.name.toLowerCase();
      final cat = d.category.toLowerCase();
      int score = 0;
      for (final w in words) {
        if (name.contains(w) || cat.contains(w)) score++;
      }
      if (score > 0) scored[d] = score;
    }

    // Sort by score descending
    final matches = scored.entries.toList()
      ..sort((a, b) => b.value.compareTo(a.value));
    final matchedDiseases = matches.map((e) => e.key).toList();

    if (matchedDiseases.length == 1) {
      _selectDisease(matchedDiseases.first);
    } else if (matchedDiseases.isNotEmpty) {
      _addUserMessage(text);
      _textController.clear();
      setState(() {
        _filteredDiseases = matchedDiseases;
      });
      _addBotMessage('${matchedDiseases.length} ta kasallik topildi. Birini tanlang:');
    } else {
      _addUserMessage(text);
      _textController.clear();
      // Show all diseases as fallback
      setState(() {
        _filteredDiseases = _diseases.take(10).toList();
      });
      _addBotMessage('"$text" bo\'yicha kasallik topilmadi. Quyidagilardan birini tanlang:');
    }
  }

  Future<void> _submitSymptoms() async {
    final symptoms = _textController.text.trim();
    if (symptoms.isEmpty) return;
    _textController.clear();
    _addUserMessage(symptoms);
    setState(() {
      _isLoading = true;
    });

    _addBotMessage('⏳ Tahlil qilinmoqda...');

    try {
      final result = await _api.analyzeSymptoms(
        gender: _gender!,
        age: _ageCategory!,
        diseaseId: _diseaseId!,
        symptoms: symptoms,
      );
      setState(() {
        _result = result;
        _step = 4;
        _isLoading = false;
        // Remove the "analyzing" message
        _messages.removeLast();
      });
      _addBotMessage(result?.analysis ?? 'Natija olinmadi.');
    } catch (e) {
      setState(() => _isLoading = false);
      _messages.removeLast();
      _addBotMessage('Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring.');
    }
  }

  Future<void> _toggleRecording() async {
    if (_isRecording) {
      // Stop recording and send to API
      final path = await _recorder.stop();
      setState(() => _isRecording = false);

      if (path == null) {
        _addBotMessage('Ovoz yozib olinmadi. Qayta urinib ko\'ring.');
        return;
      }

      _addUserMessage('🎤 Ovozli xabar');
      _addBotMessage('⏳ Ovoz tanib olinmoqda...');

      try {
        final text = await _api.transcribeVoice(path);
        // Remove "recognizing" message
        if (_messages.isNotEmpty) _messages.removeLast();

        if (text != null && text.isNotEmpty) {
          if (_step == 2) {
            // Disease search step — put text in field and handle
            _textController.text = text;
            _handleDiseaseInput();
          } else if (_step == 3) {
            // Symptoms step — submit directly
            _addUserMessage(text);
            setState(() => _isLoading = true);
            _addBotMessage('⏳ Tahlil qilinmoqda...');
            try {
              final result = await _api.analyzeSymptoms(
                gender: _gender!,
                age: _ageCategory!,
                diseaseId: _diseaseId!,
                symptoms: text,
              );
              setState(() {
                _result = result;
                _step = 4;
                _isLoading = false;
                _messages.removeLast();
              });
              _addBotMessage(result?.analysis ?? 'Natija olinmadi.');
            } catch (e) {
              setState(() => _isLoading = false);
              _messages.removeLast();
              _addBotMessage('Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring.');
            }
          }
        } else {
          _addBotMessage('Ovozni tanib bo\'lmadi. Qayta urinib ko\'ring.');
        }
      } catch (e) {
        if (_messages.isNotEmpty && _messages.last.text.contains('tanib olinmoqda')) {
          _messages.removeLast();
        }
        _addBotMessage('Ovozni yuborishda xatolik. Qayta urinib ko\'ring.');
      }
    } else {
      // Start recording
      final status = await Permission.microphone.request();
      if (!status.isGranted) {
        _addBotMessage('Mikrofon ruxsati berilmadi. Sozlamalardan ruxsat bering.');
        return;
      }

      final dir = await getTemporaryDirectory();
      final path = '${dir.path}/voice_${DateTime.now().millisecondsSinceEpoch}.m4a';

      await _recorder.start(
        const RecordConfig(encoder: AudioEncoder.aacLc),
        path: path,
      );
      setState(() => _isRecording = true);
    }
  }

  void _restart() {
    setState(() {
      _step = 0;
      _gender = null;
      _ageCategory = null;
      _diseaseId = null;
      _result = null;
      _messages.clear();
      _diseases.clear();
      _filteredDiseases.clear();
      _isLoading = false;
      _textController.clear();
    });
    _addBotMessage('Assalomu alaykum! 👋\nMen QuickMedAI — sun\'iy intellekt yordamchingizman. Keling, avval jinsingizni tanlang.');
  }

  @override
  void dispose() {
    _textController.removeListener(_onTextChanged);
    _scrollController.dispose();
    _textController.dispose();
    _recorder.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(gradient: AppTheme.bgGradient),
        child: SafeArea(
          child: Column(
            children: [
              // Header
              _buildHeader(),

              // Step indicator
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                child: StepIndicator(
                  currentStep: _step,
                  totalSteps: 5,
                  labels: _stepLabels,
                ),
              ),

              // Chat messages
              Expanded(child: _buildMessages()),

              // Input area
              _buildInputArea(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Container(
      padding: const EdgeInsets.fromLTRB(8, 8, 16, 8),
      child: Row(
        children: [
          IconButton(
            onPressed: () => Navigator.of(context).pop(),
            icon: const Icon(
              Icons.arrow_back_ios_rounded,
              color: AppTheme.textPrimary,
              size: 20,
            ),
          ),
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              gradient: AppTheme.primaryGradient,
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(
              Icons.auto_awesome_rounded,
              color: Colors.white,
              size: 20,
            ),
          ),
          const SizedBox(width: 12),
          const Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'QuickMedAI',
                  style: TextStyle(
                    color: AppTheme.textPrimary,
                    fontSize: 17,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                Text(
                  'AI Tashxis Yordamchisi',
                  style: TextStyle(
                    color: AppTheme.secondary,
                    fontSize: 12,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          IconButton(
            onPressed: _restart,
            icon: Container(
              width: 34,
              height: 34,
              decoration: BoxDecoration(
                color: AppTheme.bgCard.withValues(alpha: 0.6),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(
                Icons.refresh_rounded,
                color: AppTheme.textSecondary,
                size: 18,
              ),
            ),
          ),
        ],
      ),
    ).animate().fadeIn(duration: 300.ms);
  }

  Widget _buildMessages() {
    return ListView.builder(
      controller: _scrollController,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      physics: const BouncingScrollPhysics(),
      itemCount: _messages.length + (_step < 4 ? 1 : 0) + (_result != null ? 1 : 0),
      itemBuilder: (context, index) {
        // Chat messages
        if (index < _messages.length) {
          final msg = _messages[index];
          return ChatBubble(
            text: msg.text,
            isUser: msg.isUser,
          ).animate().fadeIn(duration: 300.ms).slideY(begin: 0.1, end: 0);
        }

        // Interactive widgets based on step
        if (index == _messages.length && _step < 4) {
          return _buildStepWidget();
        }

        // Result with doctors
        if (_result != null) {
          return _buildResultSection();
        }

        return const SizedBox.shrink();
      },
    );
  }

  Widget _buildStepWidget() {
    switch (_step) {
      case 0:
        return _buildGenderSelection();
      case 1:
        return _buildAgeSelection();
      case 2:
        return _buildDiseaseSelection();
      default:
        return const SizedBox.shrink();
    }
  }

  Widget _buildAgeSelection() {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Wrap(
        spacing: 8,
        runSpacing: 8,
        children: _ageCategories.map((cat) {
          return OptionChip(
            label: '${cat['label']} (${cat['desc']})',
            isSelected: _ageCategory == cat['value'],
            onTap: () => _selectAge(cat['value']!),
          );
        }).toList(),
      ),
    ).animate().fadeIn(delay: 200.ms, duration: 400.ms);
  }

  Widget _buildGenderSelection() {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        children: [
          Expanded(
            child: _GenderCard(
              icon: Icons.male_rounded,
              label: 'Erkak',
              isSelected: _gender == 'male',
              onTap: () => _selectGender('male'),
              color: const Color(0xFF0EA5E9),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: _GenderCard(
              icon: Icons.female_rounded,
              label: 'Ayol',
              isSelected: _gender == 'female',
              onTap: () => _selectGender('female'),
              color: const Color(0xFFF472B6),
            ),
          ),
        ],
      ),
    ).animate().fadeIn(delay: 200.ms, duration: 400.ms).slideY(begin: 0.2, end: 0);
  }

  Widget _buildDiseaseSelection() {
    if (_diseases.isEmpty) {
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(20),
          child: CircularProgressIndicator(
            color: AppTheme.primary,
            strokeWidth: 2,
          ),
        ),
      );
    }

    // Show filtered suggestions only when user typed something
    if (_filteredDiseases.isEmpty) {
      return const SizedBox.shrink();
    }

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Wrap(
        spacing: 8,
        runSpacing: 8,
        children: _filteredDiseases.take(6).map((d) {
          return OptionChip(
            label: d.name,
            isSelected: false,
            onTap: () => _selectDisease(d),
          );
        }).toList(),
      ),
    ).animate().fadeIn(duration: 200.ms);
  }

  Widget _buildResultSection() {
    if (_result == null) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.only(top: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (_result!.doctors.isNotEmpty) ...[
            const Padding(
              padding: EdgeInsets.only(bottom: 12, left: 4),
              child: Text(
                'Tavsiya etilgan shifokorlar:',
                style: TextStyle(
                  color: AppTheme.textPrimary,
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
            ..._result!.doctors.map((doctor) {
              return _buildDoctorResultCard(doctor);
            }),
          ],
          const SizedBox(height: 16),
          GradientButton(
            onPressed: _restart,
            label: 'Yangi suhbat boshlash',
            icon: Icons.refresh_rounded,
          ),
          const SizedBox(height: 20),
        ],
      ),
    ).animate().fadeIn(delay: 300.ms, duration: 500.ms).slideY(begin: 0.15, end: 0);
  }

  Widget _buildDoctorResultCard(Doctor doctor) {
    return GestureDetector(
      onTap: () {
        Navigator.of(context).pushNamed('/doctor', arguments: doctor);
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 10),
        decoration: AppTheme.glassCard,
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                gradient: AppTheme.primaryGradient,
                borderRadius: BorderRadius.circular(14),
              ),
              child: const Icon(
                Icons.medical_services_rounded,
                color: Colors.white,
                size: 24,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    doctor.fullName,
                    style: const TextStyle(
                      color: AppTheme.textPrimary,
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    doctor.specialty,
                    style: const TextStyle(
                      color: AppTheme.secondary,
                      fontSize: 12,
                    ),
                  ),
                  Text(
                    doctor.clinicName,
                    style: TextStyle(
                      color: AppTheme.textMuted.withValues(alpha: 0.6),
                      fontSize: 11,
                    ),
                  ),
                ],
              ),
            ),
            const Icon(
              Icons.arrow_forward_ios_rounded,
              color: AppTheme.primary,
              size: 16,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInputArea() {
    // Gender, age and result steps have no text input
    if (_step == 0 || _step == 1 || _step == 4) {
      return const SizedBox.shrink();
    }

    // Wait for diseases to load before showing input at step 2
    if (_step == 2 && _diseases.isEmpty) {
      return const SizedBox.shrink();
    }

    final hintText = _step == 2
        ? 'Kasallik nomini yozing...'
        : 'Simptomlaringizni yozing...';

    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
      decoration: BoxDecoration(
        color: AppTheme.bgCard.withValues(alpha: 0.3),
        border: Border(
          top: BorderSide(
            color: AppTheme.border.withValues(alpha: 0.2),
          ),
        ),
      ),
      child: Row(
        children: [
          // Mic button
          GestureDetector(
            onTap: _toggleRecording,
            child: AnimatedContainer(
              duration: const Duration(milliseconds: 200),
              width: 44,
              height: 44,
              margin: const EdgeInsets.only(right: 10),
              decoration: BoxDecoration(
                gradient: _isRecording ? AppTheme.accentGradient : null,
                color: _isRecording
                    ? null
                    : AppTheme.bgCard.withValues(alpha: 0.6),
                borderRadius: BorderRadius.circular(13),
                border: Border.all(
                  color: _isRecording
                      ? Colors.transparent
                      : AppTheme.border.withValues(alpha: 0.3),
                ),
                boxShadow: _isRecording
                    ? [
                        BoxShadow(
                          color: AppTheme.accent.withValues(alpha: 0.4),
                          blurRadius: 12,
                        ),
                      ]
                    : null,
              ),
              child: Icon(
                _isRecording ? Icons.stop_rounded : Icons.mic_rounded,
                color:
                    _isRecording ? Colors.white : AppTheme.textSecondary,
                size: 22,
              ),
            ),
          ),

          // Text input
          Expanded(
            child: Container(
              decoration: BoxDecoration(
                color: AppTheme.bgCard.withValues(alpha: 0.6),
                borderRadius: BorderRadius.circular(14),
                border: Border.all(
                  color: AppTheme.border.withValues(alpha: 0.3),
                ),
              ),
              child: TextField(
                controller: _textController,
                style: const TextStyle(
                  color: AppTheme.textPrimary,
                  fontSize: 15,
                ),
                decoration: InputDecoration(
                  hintText: hintText,
                  hintStyle: TextStyle(
                    color: AppTheme.textMuted.withValues(alpha: 0.5),
                    fontSize: 14,
                  ),
                  border: InputBorder.none,
                  contentPadding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 12,
                  ),
                ),
                keyboardType: TextInputType.text,
                maxLines: _step == 2 ? 1 : 3,
                minLines: 1,
                onSubmitted: (_) => _handleInputSubmit(),
              ),
            ),
          ),

          const SizedBox(width: 10),

          // Send button
          GestureDetector(
            onTap: _isLoading
                ? null
                : _handleInputSubmit,
            child: Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                gradient:
                    _isLoading ? null : AppTheme.primaryGradient,
                color: _isLoading
                    ? AppTheme.bgCard.withValues(alpha: 0.5)
                    : null,
                borderRadius: BorderRadius.circular(13),
                boxShadow: _isLoading
                    ? null
                    : [
                        BoxShadow(
                          color:
                              AppTheme.primary.withValues(alpha: 0.3),
                          blurRadius: 8,
                          offset: const Offset(0, 3),
                        ),
                      ],
              ),
              child: _isLoading
                  ? const Center(
                      child: SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: AppTheme.primary,
                        ),
                      ),
                    )
                  : const Icon(
                      Icons.send_rounded,
                      color: Colors.white,
                      size: 20,
                    ),
            ),
          ),
        ],
      ),
    ).animate().fadeIn(duration: 300.ms).slideY(begin: 0.1, end: 0);
  }
}

class _GenderCard extends StatelessWidget {
  final IconData icon;
  final String label;
  final bool isSelected;
  final VoidCallback onTap;
  final Color color;

  const _GenderCard({
    required this.icon,
    required this.label,
    required this.isSelected,
    required this.onTap,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(vertical: 28),
        decoration: BoxDecoration(
          gradient: isSelected
              ? LinearGradient(
                  colors: [color, color.withValues(alpha: 0.7)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                )
              : null,
          color: isSelected ? null : AppTheme.bgCard.withValues(alpha: 0.5),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected
                ? Colors.transparent
                : AppTheme.border.withValues(alpha: 0.3),
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: color.withValues(alpha: 0.35),
                    blurRadius: 16,
                    offset: const Offset(0, 6),
                  ),
                ]
              : null,
        ),
        child: Column(
          children: [
            Icon(
              icon,
              color: isSelected ? Colors.white : color,
              size: 44,
            ),
            const SizedBox(height: 8),
            Text(
              label,
              style: TextStyle(
                color: isSelected ? Colors.white : AppTheme.textPrimary,
                fontSize: 16,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
