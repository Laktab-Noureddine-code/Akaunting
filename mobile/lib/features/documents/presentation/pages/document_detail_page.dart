import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../core/di/injection_container.dart';
import '../../../../../data/models/document_model.dart';
import '../../../../../logic/cubits/document_cubit.dart';
import '../../../../../logic/cubits/document_transaction_cubit.dart';
import 'document_form_page.dart';
import 'document_transaction_form_page.dart';

class DocumentDetailPage extends StatelessWidget {
  final DocumentModel document;

  const DocumentDetailPage({super.key, required this.document});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<DocumentCubit>(create: (context) => sl<DocumentCubit>()),
        BlocProvider<DocumentTransactionCubit>(
          create: (context) => sl<DocumentTransactionCubit>()..loadDocumentTransactions(document.id),
        ),
      ],
      child: _DocumentDetailView(initialDocument: document),
    );
  }
}

class _DocumentDetailView extends StatefulWidget {
  final DocumentModel initialDocument;

  const _DocumentDetailView({required this.initialDocument});

  @override
  State<_DocumentDetailView> createState() => _DocumentDetailViewState();
}

class _DocumentDetailViewState extends State<_DocumentDetailView> {
  late DocumentModel _currentDoc;

  @override
  void initState() {
    super.initState();
    _currentDoc = widget.initialDocument;
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<DocumentCubit, DocumentState>(
      listener: (context, state) {
        if (state is DocumentError) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(state.message), backgroundColor: Colors.red));
        } else if (state is DocumentSaved) {
          setState(() {
            _currentDoc = state.document;
          });
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Document updated successfully'), backgroundColor: Colors.green));
        } else if (state is DocumentLoaded) {
          setState(() {
            _currentDoc = state.document;
          });
        } else if (state is DocumentDeleted) {
          Navigator.of(context).pop(true);