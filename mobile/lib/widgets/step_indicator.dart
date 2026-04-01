import 'package:flutter/material.dart';
import '../config/theme.dart';

class StepIndicator extends StatelessWidget {
  final int currentStep;
  final int totalSteps;
  final List<String> labels;

  const StepIndicator({
    super.key,
    required this.currentStep,
    required this.totalSteps,
    required this.labels,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // Progress bar
        Container(
          height: 4,
          decoration: BoxDecoration(
            color: AppTheme.bgCard.withValues(alpha: 0.5),
            borderRadius: BorderRadius.circular(2),
          ),
          child: LayoutBuilder(
            builder: (context, constraints) {
              return Stack(
                children: [
                  AnimatedContainer(
                    duration: const Duration(milliseconds: 400),
                    curve: Curves.easeOutCubic,
                    width: constraints.maxWidth *
                        (currentStep / totalSteps),
                    height: 4,
                    decoration: BoxDecoration(
                      gradient: AppTheme.primaryGradient,
                      borderRadius: BorderRadius.circular(2),
                      boxShadow: [
                        BoxShadow(
                          color: AppTheme.primary.withValues(alpha: 0.4),
                          blurRadius: 6,
                          offset: const Offset(0, 0),
                        ),
                      ],
                    ),
                  ),
                ],
              );
            },
          ),
        ),
        const SizedBox(height: 10),
        // Step dots and labels
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: List.generate(totalSteps, (index) {
            final isActive = index < currentStep;
            final isCurrent = index == currentStep;
            return Expanded(
              child: Column(
                children: [
                  AnimatedContainer(
                    duration: const Duration(milliseconds: 300),
                    width: isCurrent ? 10 : 8,
                    height: isCurrent ? 10 : 8,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      gradient: (isActive || isCurrent)
                          ? AppTheme.primaryGradient
                          : null,
                      color: (!isActive && !isCurrent)
                          ? AppTheme.bgCard.withValues(alpha: 0.5)
                          : null,
                      boxShadow: isCurrent
                          ? [
                              BoxShadow(
                                color:
                                    AppTheme.primary.withValues(alpha: 0.5),
                                blurRadius: 8,
                              ),
                            ]
                          : null,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    labels.length > index ? labels[index] : '',
                    style: TextStyle(
                      color: (isActive || isCurrent)
                          ? AppTheme.primary
                          : AppTheme.textMuted,
                      fontSize: 10,
                      fontWeight:
                          isCurrent ? FontWeight.w600 : FontWeight.w400,
                    ),
                    textAlign: TextAlign.center,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            );
          }),
        ),
      ],
    );
  }
}
