import 'package:flutter_bloc/flutter_bloc.dart';
import '../../domain/repositories/company_repository.dart';
import 'company_state.dart';

class CompanyCubit extends Cubit<CompanyState> {
  final CompanyRepository _repository;

  CompanyCubit({required CompanyRepository repository})
    : _repository = repository,
      super(CompanyInitial());

  Future<void> getCompanies() async {
    emit(CompanyLoading());
    try {
      final companies = await _repository.getCompanies();
      final selectedId = companies.isNotEmpty ? companies.first.id : null;
      emit(CompanyLoaded(companies: companies, selectedCompanyId: selectedId));
    } catch (e) {
      emit(CompanyError(e.toString().replaceFirst('Exception: ', '')));
    }
  }

  void selectCompany(int companyId) {
    final currentState = state;
    if (currentState is CompanyLoaded) {
      emit(currentState.copyWith(selectedCompanyId: companyId));
    }
  }
}
