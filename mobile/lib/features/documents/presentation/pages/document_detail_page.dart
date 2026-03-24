import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../core/di/injection_container.dart';
import '../../../../../data/models/document_model.dart';
import '../../../../../logic/cubits/document_cubit.dart';
import 'document_form_page.dart';

class DocumentDetailPage extends StatelessWidget {
  final DocumentModel document;

  const DocumentDetailPage({super.key, required this.document});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<DocumentCubit>(
      create: (context) => sl<DocumentCubit>(),
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
          Navigator.of(context).pop(true); // Pop the detail page and indicate success
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Document deleted'), backgroundColor: Colors.green));
        }
      },
      builder: (context, state) {
        final isLoading = state is DocumentLoading;

        return Scaffold(
          backgroundColor: const Color(0xFFF4F6F8),
          appBar: AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            iconTheme: const IconThemeData(color: Colors.black87),
            title: Text(
              _currentDoc.documentNumber,
              style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
            ),
            actions: [
              IconButton(
                icon: const Icon(Icons.edit, color: Colors.blue),
                onPressed: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => DocumentFormPage(document: _currentDoc),
                    ),
                  ).then((res) {
                    if (res == true) {
                      // If the form was saved, reload the document to get updated data
                      context.read<DocumentCubit>().loadDocument(_currentDoc.id);
                    }
                  });
                },
              ),
              IconButton(
                icon: const Icon(Icons.delete, color: Colors.red),
                onPressed: () => _confirmDelete(context),
              )
            ],
          ),
          body: isLoading ? const Center(child: CircularProgressIndicator()) : SingleChildScrollView(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      children: [
                        _DetailRow(label: 'Type', value: _currentDoc.type.toUpperCase()),
                        const Divider(),
                        _DetailRow(label: 'Status', value: _currentDoc.status.toUpperCase()),
                        const Divider(),
                        _DetailRow(label: 'Amount', value: _currentDoc.amount.toStringAsFixed(2)),
                        const Divider(),
                        _DetailRow(label: 'Contact', value: _currentDoc.contactName ?? 'N/A'),
                        if (_currentDoc.issueDate != null) ...[
                          const Divider(),
                          _DetailRow(label: 'Issue Date', value: _currentDoc.issueDate!),
                        ],
                        if (_currentDoc.dueDate != null) ...[
                          const Divider(),
                          _DetailRow(label: 'Due Date', value: _currentDoc.dueDate!),
                        ],
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 24),
                if (_currentDoc.status != 'received')
                  ElevatedButton(
                    onPressed: () {
                      context.read<DocumentCubit>().markAsReceived(_currentDoc.id);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.green,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                    ),
                    child: const Text('Mark as Received', style: TextStyle(color: Colors.white)),
                  )
                else
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    decoration: BoxDecoration(
                      color: Colors.green.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Center(
                      child: Text('This document is received.', style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
                    ),
                  ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _confirmDelete(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Document'),
        content: const Text('Are you sure you want to delete this document? This action cannot be undone.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: const Text('CANCEL'),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop(); // Close the dialog
              context.read<DocumentCubit>().deleteDocument(_currentDoc.id);
            },
            child: const Text('DELETE', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }
}

class _DetailRow extends StatelessWidget {
  final String label;
  final String value;

  const _DetailRow({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey.shade600, fontSize: 14)),
          Flexible(child: Text(value, style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 14, color: Colors.black87), textAlign: TextAlign.right)),
        ],
      ),
    );
  }
}
