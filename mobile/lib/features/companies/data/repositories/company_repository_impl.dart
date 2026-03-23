import 'package:dio/dio.dart';
import '../../domain/repositories/company_repository.dart';
import '../models/company_model.dart';

class CompanyRepositoryImpl implements CompanyRepository {
  final Dio _dio;

  CompanyRepositoryImpl({required Dio dio}) : _dio = dio;

  @override
  Future<List<CompanyModel>> getCompanies() async {
    try {
      final response = await _dio.get('/api/companies');
      final data = response.data as Map<String, dynamic>;
      final List<dynamic> companiesJson = data['data'] ?? [];
      return companiesJson
          .map((json) => CompanyModel.fromJson(json as Map<String, dynamic>))
          .toList();
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Exception _handleError(DioException e) {
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      return Exception('Connection timeout. Please try again.');
    }

    if (e.type == DioExceptionType.connectionError) {
      return Exception('Network error. Please check your connection.');
    }

    if (e.response != null) {
      final statusCode = e.response!.statusCode;
      final data = e.response!.data;

      if (statusCode == 401) {
        return Exception('Unauthorized. Please login again.');
      }

      if (statusCode == 403) {
        return Exception('Access forbidden.');
      }

      if (data is Map && data['message'] != null) {
        return Exception(data['message']);
      }

      return Exception('Server error: $statusCode');
    }

    return Exception('An unexpected error occurred.');
  }
}
