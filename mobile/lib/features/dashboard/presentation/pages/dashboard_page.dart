import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../companies/presentation/cubit/company_cubit.dart';
import '../../../companies/presentation/widgets/company_switcher.dart';
import '../../../profile/presentation/cubit/profile_cubit.dart';
import '../../../profile/presentation/cubit/profile_state.dart';
import '../../../auth/presentation/pages/auth_check_page.dart';
import '../../../companies/presentation/pages/companies_list_page.dart';
import '../../../../logic/cubits/auth_cubit.dart';
import 'dashboards_list_page.dart';

class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  @override
  void initState() {
    super.initState();
