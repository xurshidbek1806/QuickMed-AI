import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';
import '../config/theme.dart';
import '../widgets/gradient_button.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _controller = PageController();
  int _currentPage = 0;

  final List<_OnboardingData> _pages = [
    _OnboardingData(
      icon: Icons.chat_bubble_rounded,
      title: 'AI Suhbat',
      subtitle:
          'Sun\'iy intellekt bilan suhbatlashib, simptomlaringizni aniqlang va dastlabki tashxis oling.',
      gradient: AppTheme.primaryGradient,
    ),
    _OnboardingData(
      icon: Icons.mic_rounded,
      title: 'Ovozli Kiritish',
      subtitle:
          'Yozishni xohlamasiangizmi? Simptomlaringizni ovoz orqali aytib bering — tizim tushunadi.',
      gradient: const LinearGradient(
        colors: [Color(0xFF06B6D4), Color(0xFF0EA5E9)],
      ),
    ),
    _OnboardingData(
      icon: Icons.medical_services_rounded,
      title: 'Shifokor Tavsiyasi',
      subtitle:
          'Sizga mos shifokorlar ro\'yxatini oling va eng yaqin klinikani toping.',
      gradient: const LinearGradient(
        colors: [Color(0xFF8B5CF6), Color(0xFFA78BFA)],
      ),
    ),
  ];

  void _goToHome() {
    Navigator.of(context).pushReplacementNamed('/home');
  }

  @override
  void dispose() {
    _controller.dispose();
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
              // Skip button
              Align(
                alignment: Alignment.topRight,
                child: TextButton(
                  onPressed: _goToHome,
                  child: Text(
                    'O\'tkazib yuborish',
                    style: TextStyle(
                      color: AppTheme.textMuted.withValues(alpha: 0.7),
                      fontSize: 14,
                    ),
                  ),
                ),
              ),

              // Pages
              Expanded(
                child: PageView.builder(
                  controller: _controller,
                  itemCount: _pages.length,
                  onPageChanged: (index) {
                    setState(() => _currentPage = index);
                  },
                  itemBuilder: (context, index) {
                    final page = _pages[index];
                    return Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 32),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          // Icon container
                          Container(
                            width: 120,
                            height: 120,
                            decoration: BoxDecoration(
                              gradient: page.gradient,
                              borderRadius: BorderRadius.circular(36),
                              boxShadow: [
                                BoxShadow(
                                  color: AppTheme.primary
                                      .withValues(alpha: 0.3),
                                  blurRadius: 40,
                                  offset: const Offset(0, 15),
                                ),
                              ],
                            ),
                            child: Icon(
                              page.icon,
                              color: Colors.white,
                              size: 56,
                            ),
                          )
                              .animate(key: ValueKey('icon_$index'))
                              .scale(
                                begin: const Offset(0.6, 0.6),
                                end: const Offset(1.0, 1.0),
                                duration: 500.ms,
                                curve: Curves.elasticOut,
                              )
                              .fadeIn(duration: 300.ms),

                          const SizedBox(height: 48),

                          // Title
                          Text(
                            page.title,
                            textAlign: TextAlign.center,
                            style: const TextStyle(
                              color: AppTheme.textPrimary,
                              fontSize: 30,
                              fontWeight: FontWeight.w800,
                              letterSpacing: -0.5,
                            ),
                          )
                              .animate(key: ValueKey('title_$index'))
                              .fadeIn(delay: 200.ms, duration: 400.ms)
                              .slideY(begin: 0.2, end: 0),

                          const SizedBox(height: 16),

                          // Subtitle
                          Text(
                            page.subtitle,
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              color: AppTheme.textSecondary
                                  .withValues(alpha: 0.85),
                              fontSize: 16,
                              fontWeight: FontWeight.w400,
                              height: 1.5,
                            ),
                          )
                              .animate(key: ValueKey('sub_$index'))
                              .fadeIn(delay: 350.ms, duration: 400.ms)
                              .slideY(begin: 0.2, end: 0),
                        ],
                      ),
                    );
                  },
                ),
              ),

              // Bottom section
              Padding(
                padding: const EdgeInsets.fromLTRB(32, 0, 32, 40),
                child: Column(
                  children: [
                    // Page indicator
                    SmoothPageIndicator(
                      controller: _controller,
                      count: _pages.length,
                      effect: ExpandingDotsEffect(
                        dotColor: AppTheme.bgCard.withValues(alpha: 0.5),
                        activeDotColor: AppTheme.primary,
                        dotHeight: 8,
                        dotWidth: 8,
                        expansionFactor: 3.5,
                        spacing: 6,
                      ),
                    ),
                    const SizedBox(height: 36),

                    // Next / Get Started button
                    GradientButton(
                      onPressed: () {
                        if (_currentPage == _pages.length - 1) {
                          _goToHome();
                        } else {
                          _controller.nextPage(
                            duration: const Duration(milliseconds: 400),
                            curve: Curves.easeOutCubic,
                          );
                        }
                      },
                      label: _currentPage == _pages.length - 1
                          ? 'Boshlash'
                          : 'Keyingi',
                      icon: _currentPage == _pages.length - 1
                          ? Icons.arrow_forward_rounded
                          : Icons.arrow_forward_ios_rounded,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _OnboardingData {
  final IconData icon;
  final String title;
  final String subtitle;
  final Gradient gradient;

  _OnboardingData({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.gradient,
  });
}
