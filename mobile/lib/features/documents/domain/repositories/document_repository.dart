import '../../../../data/models/document_model.dart';

abstract class DocumentRepository {
  Future<List<DocumentModel>> getDocuments({String? search, int page = 1});
  Future<DocumentModel> getDocument(int id);
  Future<DocumentModel> createDocument(Map<String, dynamic> data);
  Future<DocumentModel> updateDocument(int id, Map<String, dynamic> data);
  Future<void> deleteDocument(int id);
  Future<DocumentModel> markAsReceived(int id);
}
