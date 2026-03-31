import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../cubit/company_cubit.dart';
import '../cubit/company_state.dart';
import 'company_form_page.dart';

class CompanyDetailPage extends StatefulWidget {
  final int companyId;
  const CompanyDetailPage({super.key, required this.companyId});

  @override
  State<CompanyDetailPage> createState() => _CompanyDetailPageState();
}

class _CompanyDetailPageState extends State<CompanyDetailPage> {
  bool _isOwner = false;
  bool _checkedOwner = false;

  @override
  void initState() {
    super.initState();
    context.read<CompanyCubit>().checkOwner(widget.companyId);
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<CompanyCubit, CompanyState>(
      listener: (context, state) {
        if (state is CompanyDeleted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Company deleted'), backgroundColor: Colors.red),
          );
          Navigator.pop(context);