import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/di/injection_container.dart';
import '../../../../core/ui/components/akaunting_search.dart';
import '../../../../core/ui/components/badge.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../core/ui/components/base_alert.dart';
import '../../../../logic/cubits/contact_cubit.dart';
import 'contact_detail_page.dart';
import 'contact_form_page.dart';

class ContactsListPage extends StatelessWidget {
  const ContactsListPage({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ContactCubit>(
      create: (context) => sl<ContactCubit>(),
      child: const ContactsListView(),
    );
  }
}

class ContactsListView extends StatefulWidget {
  const ContactsListView({super.key});

  @override
  State<ContactsListView> createState() => _ContactsListViewState();
}

class _ContactsListViewState extends State<ContactsListView> with SingleTickerProviderStateMixin {
  String _searchQuery = '';
  late TabController _tabController;
  final List<String> _types = ['customer', 'vendor'];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: _types.length, vsync: this);

