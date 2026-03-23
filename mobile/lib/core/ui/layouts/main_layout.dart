import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get_it/get_it.dart';
import '../../../../features/accounts/presentation/pages/accounts_list_page.dart';
import '../../../../features/reconciliations/presentation/pages/reconciliations_list_page.dart';
import '../../../../features/transactions/presentation/pages/transactions_list_page.dart';
import '../../../../features/dashboard/presentation/pages/dashboard_page.dart';
import '../../../../features/companies/presentation/cubit/company_cubit.dart';
import '../../../../features/profile/presentation/cubit/profile_cubit.dart';

class MainLayout extends StatefulWidget {
  const MainLayout({super.key});

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<CompanyCubit>(create: (_) => GetIt.I<CompanyCubit>()),
        BlocProvider<ProfileCubit>(create: (_) => GetIt.I<ProfileCubit>()),
      ],
      child: Scaffold(
        body: IndexedStack(
          index: _currentIndex,
          children: const [
            DashboardPage(),
            AccountsListPage(),
            ReconciliationsListPage(),
            TransactionsListPage(),
            Center(child: Text('Settings Placeholder')),
          ],
        ),
        bottomNavigationBar: BottomNavigationBar(
          type: BottomNavigationBarType.fixed,
          currentIndex: _currentIndex,
          onTap: (index) {
            setState(() {
              _currentIndex = index;
            });
          },
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.dashboard),
              label: 'Dashboard',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.account_balance_wallet),
              label: 'Accounts',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.receipt_long),
              label: 'Reconciliations',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.swap_horiz),
              label: 'Transactions',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.settings),
              label: 'Settings',
            ),
          ],
        ),
      ),
    );
  }
}
