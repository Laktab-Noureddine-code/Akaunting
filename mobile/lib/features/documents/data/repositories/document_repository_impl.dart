import 'package:dio/dio.dart';
import '../../../../data/models/document_model.dart';
import '../../../../data/models/transaction_model.dart';
import '../../domain/repositories/document_repository.dart';

class ApiDocumentRepository implements DocumentRepository {
  final Dio _dio;

  ApiDocumentRepository({required Dio dio}) : _dio = dio;

  @override
  Future<List<DocumentModel>> getDocuments({String? search, int page = 1}) async {
    try {
      final queryParams = <String, dynamic>{'page': page};
      if (search != null && search.isNotEmpty) {
        queryParams['search'] = search.contains('type:') ? search : 'type:invoice $search';
      } else {
        queryParams['search'] = 'type:invoice';
      }

      final response = await _dio.get('/api/documents', queryParameters: queryParams);
      final data = response.data as Map<String, dynamic>;
      final List items = data['data'] as List;
      return items.map((json) => DocumentModel.fromJson(json)).toList();
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  @override
  Future<DocumentModel> getDocument(int id) async {
    try {
