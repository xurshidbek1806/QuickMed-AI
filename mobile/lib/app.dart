import 'package:flutter/material.dart';
import 'config/theme.dart';
import 'models/doctor.dart';
import 'screens/splash_screen.dart';
import 'screens/onboarding_screen.dart';
import 'screens/home_screen.dart';
import 'screens/chat_screen.dart';
import 'screens/result_screen.dart';
import 'screens/doctor_detail_screen.dart';

class QuickMedAIApp extends StatelessWidget {
  const QuickMedAIApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'QuickMedAI',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      initialRoute: '/',
      onGenerateRoute: (settings) {
        switch (settings.name) {
          case '/':
            return _buildRoute(const SplashScreen(), settings);
          case '/onboarding':
            return _buildRoute(const OnboardingScreen(), settings);
          case '/home':
            return _buildRoute(const HomeScreen(), settings);
          case '/chat':
            return _buildRoute(const ChatScreen(), settings);
          case '/result':
            final result = settings.arguments as AnalysisResult;
            return _buildRoute(ResultScreen(result: result), settings);
          case '/doctor':
            final doctor = settings.arguments as Doctor;
            return _buildRoute(DoctorDetailScreen(doctor: doctor), settings);
          default:
            return _buildRoute(const HomeScreen(), settings);
        }
      },
    );
  }

  PageRouteBuilder _buildRoute(Widget page, RouteSettings settings) {
    return PageRouteBuilder(
      settings: settings,
      pageBuilder: (context, animation, secondaryAnimation) => page,
      transitionsBuilder: (context, animation, secondaryAnimation, child) {
        final curved = CurvedAnimation(
          parent: animation,
          curve: Curves.easeOutCubic,
        );
        return FadeTransition(
          opacity: curved,
          child: SlideTransition(
            position: Tween<Offset>(
              begin: const Offset(0.05, 0),
              end: Offset.zero,
            ).animate(curved),
            child: child,
          ),
        );
      },
      transitionDuration: const Duration(milliseconds: 300),
    );
  }
}
