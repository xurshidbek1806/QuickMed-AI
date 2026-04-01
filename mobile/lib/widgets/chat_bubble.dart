import 'package:flutter/material.dart';
import '../config/theme.dart';

class ChatBubble extends StatelessWidget {
  final String text;
  final bool isUser;
  final Widget? child;

  const ChatBubble({
    super.key,
    required this.text,
    required this.isUser,
    this.child,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        mainAxisAlignment:
            isUser ? MainAxisAlignment.end : MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (!isUser) _buildAvatar(),
          const SizedBox(width: 10),
          Flexible(
            child: Column(
              crossAxisAlignment:
                  isUser ? CrossAxisAlignment.end : CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 18,
                    vertical: 14,
                  ),
                  decoration: BoxDecoration(
                    gradient: isUser ? AppTheme.primaryGradient : null,
                    color: isUser ? null : AppTheme.bgCard.withValues(alpha: 0.8),
                    borderRadius: BorderRadius.only(
                      topLeft: const Radius.circular(20),
                      topRight: const Radius.circular(20),
                      bottomLeft: Radius.circular(isUser ? 20 : 4),
                      bottomRight: Radius.circular(isUser ? 4 : 20),
                    ),
                    border: isUser
                        ? null
                        : Border.all(
                            color: AppTheme.border.withValues(alpha: 0.4)),
                  ),
                  child: Text(
                    text,
                    style: TextStyle(
                      color: isUser ? Colors.white : AppTheme.textPrimary,
                      fontSize: 15,
                      height: 1.5,
                    ),
                  ),
                ),
                if (child != null) ...[
                  const SizedBox(height: 10),
                  child!,
                ],
              ],
            ),
          ),
          const SizedBox(width: 10),
          if (isUser) _buildUserAvatar(),
        ],
      ),
    );
  }

  Widget _buildAvatar() {
    return Container(
      width: 38,
      height: 38,
      decoration: BoxDecoration(
        gradient: AppTheme.primaryGradient,
        borderRadius: BorderRadius.circular(12),
      ),
      child: const Icon(Icons.favorite, color: Colors.white, size: 18),
    );
  }

  Widget _buildUserAvatar() {
    return Container(
      width: 38,
      height: 38,
      decoration: BoxDecoration(
        color: AppTheme.bgCardLight,
        borderRadius: BorderRadius.circular(12),
      ),
      child: const Icon(Icons.person, color: AppTheme.textSecondary, size: 18),
    );
  }
}
