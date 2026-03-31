import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../data/models/account_model.dart';
import '../../../../core/ui/components/badge.dart';
import '../../../../core/ui/components/cards/stats_card.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../core/ui/components/base_button.dart';
import '../../../../logic/cubits/account_cubit.dart';
import '../../../../core/di/injection_container.dart';
import 'account_form_page.dart';

class AccountDetailPage extends StatelessWidget {
  final AccountModel account;

  const AccountDetailPage({super.key, required this.account});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<AccountCubit>(
      create: (context) => sl<AccountCubit>(),
      child: _AccountDetailView(initialAccount: account),
    );
  }
}

class _AccountDetailView extends StatefulWidget {
  final AccountModel initialAccount;

  const _AccountDetailView({required this.initialAccount});

  @override
  State<_AccountDetailView> createState() => _AccountDetailViewState();
}

class _AccountDetailViewState extends State<_AccountDetailView> {
  late AccountModel _account;

  @override
  void initState() {
    super.initState();
    _account = widget.initialAccount;
  }

  void _onAccountDeleted() {
    Navigator.of(context).pop(true);
  }

  void _confirmDelete() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Account'),
        content: const Text('Are you sure you want to delete this account?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: const Text('Cancel', style: TextStyle(color: Colors.grey)),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop();
              context.read<AccountCubit>().deleteAccount(_account.id);
            },
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<AccountCubit, AccountState>(
      listener: (context, state) {
        if (state is AccountDeleted) {
          _onAccountDeleted();
        } else if (state is AccountSaved) {
            setState(() {
              _account = state.account;
            });
        } else if (state is AccountError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(state.message), backgroundColor: Colors.red),
          );
        }
      },
      builder: (context, state) {
        final isLoading = state is AccountLoading;

        return Scaffold(
          backgroundColor: const Color(0xFFF4F6F8),
          appBar: AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            leading: const BackButton(color: Colors.black87),
            title: Text(
              _account.name,
              style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
            ),
            actions: [
              IconButton(
                icon: const Icon(Icons.edit, color: Colors.black87),
                onPressed: () async {
                  final updatedAccount = await Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => AccountFormPage(account: _account),
                    ),
                  );
                  if (updatedAccount != null) {
                    setState(() {
                      _account = updatedAccount;
                    });
                  }
                },
              ),
              IconButton(
                icon: const Icon(Icons.delete, color: Colors.red),
                onPressed: _confirmDelete,
              ),
            ],
          ),
          body: isLoading ? const Center(child: CircularProgressIndicator()) : SingleChildScrollView(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
