import 'package:flutter/material.dart';

enum BadgeType { primary, info, danger, defaultType, warning, success }
enum BadgeSize { defaultSize, md, lg }

class AppBadge extends StatelessWidget {
  final Widget? child;
  final bool rounded;