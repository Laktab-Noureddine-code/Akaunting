import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../logic/cubits/auth_cubit.dart';
import '../../../core/auth/permission_service.dart';
import '../../../features/accounts/presentation/pages/accounts_list_page.dart';
import '../../../features/transactions/presentation/pages/transactions_list_page.dart';
import '../../../features/transfers/presentation/pages/transfers_list_page.dart';
import '../../../features/reconciliations/presentation/pages/reconciliations_list_page.dart';
import '../../../features/categories/presentation/pages/categories_list_page.dart';
import '../../../features/currencies/presentation/pages/currencies_list_page.dart';
import '../../../features/taxes/presentation/pages/taxes_list_page.dart';
import '../../../features/contacts/presentation/pages/contacts_page.dart';
import '../../../features/documents/presentation/pages/documents_list_page.dart';
import '../../../features/translations/presentation/pages/translations_page.dart';
import '../../../features/auth/presentation/pages/auth_check_page.dart';
import '../../../features/users/presentation/pages/users_list_page.dart';

class AppDrawer extends StatelessWidget {
  final int currentIndex;
  final ValueChanged<int> onTabSelected;

  const AppDrawer({
    super.key,
    required this.currentIndex,
    required this.onTabSelected,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return BlocBuilder<AuthCubit, AuthState>(
      builder: (context, authState) {
