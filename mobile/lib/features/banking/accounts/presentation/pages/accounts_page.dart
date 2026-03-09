import 'package:flutter/material.dart';
import '../../../../../core/di/injection_container.dart';
import '../../../../../core/ui/components/badge.dart';
import '../../../../../core/ui/components/cards/card.dart';
import '../../../../../core/ui/components/akaunting_money.dart';
import '../../../../../core/ui/components/akaunting_search.dart';
import '../../domain/repositories/account_repository.dart';
import '../../data/models/account_model.dart';
import 'edit_account_page.dart';

class AccountsPage extends StatefulWidget {
  const AccountsPage({super.key});

  @override
  State<AccountsPage> createState() => _AccountsPageState();
}

class _AccountsPageState extends State<AccountsPage> {
  final AccountRepository _repository = sl<AccountRepository>();
  
  List<AccountModel> _accounts = [];
  List<AccountModel> _filteredAccounts = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadAccounts();
  }

  Future<void> _loadAccounts() async {
    setState(() => _isLoading = true);
    try {
      final accounts = await _repository.getAccounts();
      setState(() {
        _accounts = accounts;
        _filteredAccounts = accounts;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to load accounts: $e')),
        );
      }
    }
  }

  void _onSearch(String query) {
    setState(() {
      if (query.isEmpty) {
        _filteredAccounts = _accounts;
      } else {
        _filteredAccounts = _accounts.where((account) => 
          account.name.toLowerCase().contains(query.toLowerCase()) || 
          account.number.toLowerCase().contains(query.toLowerCase())
        ).toList();
      }
    });
  }

  Future<void> _toggleStatus(AccountModel account) async {
    try {
      if (account.enabled) {
        await _repository.disableAccount(account.id);
      } else {
        await _repository.enableAccount(account.id);
      }
      _loadAccounts(); // Refresh
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to update status: $e')),
        );
      }
    }
  }

  Future<void> _deleteAccount(AccountModel account) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete Account'),
        content: Text('Are you sure you want to delete ${account.name}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(false),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () => Navigator.of(context).pop(true),
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      )
    );

    if (confirm == true) {
      try {
        await _repository.deleteAccount(account.id);
        _loadAccounts();
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Failed to delete account: $e')),
          );
        }
      }
    }
  }

  void _openEditPage([AccountModel? account]) async {
    final result = await Navigator.of(context).push(
      MaterialPageRoute(builder: (context) => EditAccountPage(account: account)),
    );
    if (result == true) {
      _loadAccounts();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Accounts'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _openEditPage(),
          )
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: AkauntingSearch(
              onChanged: _onSearch,
              placeholder: 'Search Accounts...',
            ),
          ),
          Expanded(
            child: _isLoading 
              ? const Center(child: CircularProgressIndicator())
              : _filteredAccounts.isEmpty 
                ? const Center(child: Text('No accounts found.'))
                : RefreshIndicator(
                    onRefresh: _loadAccounts,
                    child: ListView.builder(
                      padding: const EdgeInsets.symmetric(horizontal: 16.0),
                      itemCount: _filteredAccounts.length,
                      itemBuilder: (context, index) {
                        final account = _filteredAccounts[index];
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 12.0),
                          child: _buildAccountCard(account),
                        );
                      },
                    ),
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildAccountCard(AccountModel account) {
    return AppCard(
      shadow: true,
      child: ListTile(
        contentPadding: EdgeInsets.zero,
        title: Text(account.name, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Number: ${account.number}'),
            const SizedBox(height: 4),
            Row(
              children: [
                AppBadge(
                  type: account.enabled ? BadgeType.success : BadgeType.danger,
                  child: Text(account.enabled ? 'Enabled' : 'Disabled'),
                  rounded: true,
                ),
              ],
            )
          ],
        ),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text(
              '${account.currencyCode} ${account.currentBalance.toStringAsFixed(2)}',
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                InkWell(
                  onTap: () => _toggleStatus(account),
                  child: Icon(
                    account.enabled ? Icons.toggle_on : Icons.toggle_off,
                    color: account.enabled ? Colors.green : Colors.grey,
                  ),
                ),
              ],
            )
          ],
        ),
        onTap: () => _openEditPage(account),
        onLongPress: () => _deleteAccount(account),
      ),
    );
  }
}
