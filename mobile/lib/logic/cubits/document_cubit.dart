import 'package:flutter_bloc/flutter_bloc.dart';
import '../../data/models/document_model.dart';
import '../../features/documents/domain/repositories/document_repository.dart';

abstract class DocumentState {}

class DocumentInitial extends DocumentState {}

class DocumentLoading extends DocumentState {}

class DocumentsLoaded extends DocumentState {
  final List<DocumentModel> documents;
  DocumentsLoaded(this.documents);
}

class DocumentLoaded extends DocumentState {
  final DocumentModel document;
  DocumentLoaded(this.document);
}

class DocumentSaved extends DocumentState {
  final DocumentModel document;
  DocumentSaved(this.document);
}

class DocumentDeleted extends DocumentState {}

class DocumentError extends DocumentState {
  final String message;
  DocumentError(this.message);
}

class DocumentCubit extends Cubit<DocumentState> {
  final DocumentRepository _documentRepo;

  DocumentCubit({required DocumentRepository documentRepository})
      : _documentRepo = documentRepository,
        super(DocumentInitial());

  Future<void> loadDocuments({String? search}) async {
    emit(DocumentLoading());
    try {
      final documents = await _documentRepo.getDocuments(search: search);
      emit(DocumentsLoaded(documents));
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  Future<void> loadDocument(int id) async {
    emit(DocumentLoading());
    try {
      final document = await _documentRepo.getDocument(id);
      emit(DocumentLoaded(document));
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  Future<void> createDocument(Map<String, dynamic> data) async {
    emit(DocumentLoading());
    try {
      final document = await _documentRepo.createDocument(data);
      emit(DocumentSaved(document));
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  Future<void> updateDocument(int id, Map<String, dynamic> data) async {
    emit(DocumentLoading());
    try {
      final document = await _documentRepo.updateDocument(id, data);
      emit(DocumentSaved(document));
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  Future<void> deleteDocument(int id) async {
    emit(DocumentLoading());
    try {
      await _documentRepo.deleteDocument(id);
      emit(DocumentDeleted());
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  Future<void> markAsReceived(int id) async {
    try {
      final document = await _documentRepo.markAsReceived(id);
      emit(DocumentSaved(document));
    } catch (e) {
      emit(DocumentError(_parseError(e)));
    }
  }

  String _parseError(Object e) {
    final msg = e.toString();
    if (msg.startsWith('Exception: ')) return msg.substring(11);
    return 'An unexpected error occurred. Please try again.';
  }
}
