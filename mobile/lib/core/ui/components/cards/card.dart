import 'package:flutter/material.dart';

enum CardType { primary, secondary, success, danger, warning, info, defaultType }

class AppCard extends StatelessWidget {
  final CardType? type;
  final Widget? image;
  final Widget? header;
  final Widget? child;