import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/di/injection_container.dart';
import '../../../../logic/cubits/auth_cubit.dart';
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

