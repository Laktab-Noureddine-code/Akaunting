import 'package:flutter/material.dart';

class AkauntingSelectOption {
  final String key;
  final String value;

  AkauntingSelectOption({required this.key, required this.value});
}

class AkauntingSelect extends StatelessWidget {
  final String? title;
  final String? placeholder;
  final bool isRequired;
  final String? error;
  final List<AkauntingSelectOption> options;
  final String? value;
  final void Function(String?)? onChanged;
  final bool disabled;
  final IconData? icon;

  const AkauntingSelect({
    super.key,
    this.title,
    this.placeholder,
    this.isRequired = false,
    this.error,
    required this.options,
    this.value,
    this.onChanged,
    this.disabled = false,
    this.icon,
  });

  @override
  Widget build(BuildContext context) {
