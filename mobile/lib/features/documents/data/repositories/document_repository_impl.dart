import 'package:dio/dio.dart';
import '../../../../data/models/document_model.dart';
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
      final response = await _dio.get('/api/documents/$id');
      final data = response.data as Map<String, dynamic>;
      return DocumentModel.fromJson(data['data']);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  @override
  Future<DocumentModel> createDocument(Map<String, dynamic> data) async {
    try {
      final response = await _dio.post('/api/documents', data: data);
      final responseData = response.data as Map<String, dynamic>;
      return DocumentModel.fromJson(responseData['data']);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  @override
  Future<DocumentModel> updateDocument(int id, Map<String, dynamic> data) async {
    try {
      final response = await _dio.patch('/api/documents/$id', data: data);
      final responseData = response.data as Map<String, dynamic>;
      return DocumentModel.fromJson(responseData['data']);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  @override
  Future<void> deleteDocument(int id) async {
    try {
      await _dio.delete('/api/documents/$id');
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  @override
  Future<DocumentModel> markAsReceived(int id) async {
    try {
      final response = await _dio.get('/api/documents/$id/received');
      final data = response.data as Map<String, dynamic>;
      return DocumentModel.fromJson(data['data']);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Exception _handleError(DioException e) {
    if (e.response != null) {
      final data = e.response!.data;
      if (data is Map && data['message'] != null) {
        return Exception(data['message']);
      }
      return Exception('Server error: ${e.response!.statusCode}');
    }
    return Exception('Network error. Please check your connection.');
  }
}
