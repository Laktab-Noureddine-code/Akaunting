import 'package:get_it/get_it.dart';
import '../../features/auth/domain/repositories/auth_repository.dart';
import '../../features/auth/data/repositories/dev_auth_repository.dart';
import '../../features/banking/accounts/domain/repositories/account_repository.dart';
import '../../features/banking/accounts/data/repositories/dev_account_repository.dart';

final sl = GetIt.instance;

Future<void> init() async {
  // Authentication
  sl.registerLazySingleton<AuthRepository>(() => DevAuthRepository());
  
  // Banking - Accounts
  sl.registerLazySingleton<AccountRepository>(() => DevAccountRepository());

  // Future: Switch to ApiAuthRepository and ApiAccountRepository when Developer 1 finishes components
}
