import '../../data/models/account_model.dart';

abstract class AccountRepository {
  Future<List<AccountModel>> getAccounts();
  Future<AccountModel> getAccount(int id);
  Future<AccountModel> createAccount(AccountModel account);
  Future<AccountModel> updateAccount(int id, AccountModel account);
  Future<void> deleteAccount(int id);
  Future<void> enableAccount(int id);
  Future<void> disableAccount(int id);
}
