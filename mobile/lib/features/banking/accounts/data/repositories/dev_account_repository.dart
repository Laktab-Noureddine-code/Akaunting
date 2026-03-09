import '../models/account_model.dart';
import '../../domain/repositories/account_repository.dart';

class DevAccountRepository implements AccountRepository {
  final List<AccountModel> _mockAccounts = [
    AccountModel(
      id: 1,
      name: 'Cash',
      number: 'CASH-001',
      currencyCode: 'USD',
      openingBalance: 1000.0,
      currentBalance: 1500.0,
      bankName: '',
      bankPhone: '',
      bankAddress: '',
      enabled: true,
    ),
    AccountModel(
      id: 2,
      name: 'Bank of America',
      number: 'BOA-9988',
      currencyCode: 'USD',
      openingBalance: 5000.0,
      currentBalance: 4250.75,
      bankName: 'Bank of America',
      bankPhone: '1-800-432-1000',
      bankAddress: '100 N Tryon St',
      enabled: true,
    ),
  ];

  @override
  Future<List<AccountModel>> getAccounts() async {
    await Future.delayed(const Duration(milliseconds: 800));
    return _mockAccounts;
  }

  @override
  Future<AccountModel> getAccount(int id) async {
    await Future.delayed(const Duration(milliseconds: 500));
    return _mockAccounts.firstWhere((a) => a.id == id);
  }

  @override
  Future<AccountModel> createAccount(AccountModel account) async {
    await Future.delayed(const Duration(milliseconds: 800));
    final newAccount = AccountModel(
      id: DateTime.now().millisecondsSinceEpoch,
      name: account.name,
      number: account.number,
      currencyCode: account.currencyCode,
      openingBalance: account.openingBalance,
      currentBalance: account.openingBalance,
      bankName: account.bankName,
      bankPhone: account.bankPhone,
      bankAddress: account.bankAddress,
      enabled: account.enabled,
    );
    _mockAccounts.add(newAccount);
    return newAccount;
  }

  @override
  Future<AccountModel> updateAccount(int id, AccountModel account) async {
    await Future.delayed(const Duration(milliseconds: 800));
    final index = _mockAccounts.indexWhere((a) => a.id == id);
    if (index != -1) {
      final updated = AccountModel(
        id: id,
        name: account.name,
        number: account.number,
        currencyCode: account.currencyCode,
        openingBalance: account.openingBalance,
        currentBalance: account.currentBalance, // Preserve existing balance
        bankName: account.bankName,
        bankPhone: account.bankPhone,
        bankAddress: account.bankAddress,
        enabled: account.enabled,
      );
      _mockAccounts[index] = updated;
      return updated;
    }
    throw Exception('Account not found');
  }

  @override
  Future<void> deleteAccount(int id) async {
    await Future.delayed(const Duration(milliseconds: 800));
    _mockAccounts.removeWhere((a) => a.id == id);
  }

  @override
  Future<void> enableAccount(int id) async {
    await Future.delayed(const Duration(milliseconds: 400));
    final index = _mockAccounts.indexWhere((a) => a.id == id);
    if (index != -1) {
      final a = _mockAccounts[index];
      _mockAccounts[index] = AccountModel(
        id: a.id, name: a.name, number: a.number, currencyCode: a.currencyCode,
        openingBalance: a.openingBalance, currentBalance: a.currentBalance,
        bankName: a.bankName, bankPhone: a.bankPhone, bankAddress: a.bankAddress,
        enabled: true,
      );
    }
  }

  @override
  Future<void> disableAccount(int id) async {
    await Future.delayed(const Duration(milliseconds: 400));
    final index = _mockAccounts.indexWhere((a) => a.id == id);
    if (index != -1) {
      final a = _mockAccounts[index];
      _mockAccounts[index] = AccountModel(
        id: a.id, name: a.name, number: a.number, currencyCode: a.currencyCode,
        openingBalance: a.openingBalance, currentBalance: a.currentBalance,
        bankName: a.bankName, bankPhone: a.bankPhone, bankAddress: a.bankAddress,
        enabled: false,
      );
    }
  }
}
