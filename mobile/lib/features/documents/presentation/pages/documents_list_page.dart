import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../core/di/injection_container.dart';
import '../../../../../logic/cubits/document_cubit.dart';
import 'document_detail_page.dart';
import 'document_form_page.dart';

class DocumentsListPage extends StatelessWidget {
  const DocumentsListPage({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<DocumentCubit>(
      create: (context) => sl<DocumentCubit>()..loadDocuments(),
      child: const _DocumentsListView(),
    );
  }
}

class _DocumentsListView extends StatefulWidget {
  const _DocumentsListView();

  @override
  State<_DocumentsListView> createState() => _DocumentsListViewState();
}

class _DocumentsListViewState extends State<_DocumentsListView> {
  Future<void> _onRefresh() async {
    context.read<DocumentCubit>().loadDocuments();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F8),
      appBar: AppBar(
        title: const Text('Documents', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      floatingActionButton: FloatingActionButton(
        heroTag: 'documents_fab',
        onPressed: () {
          Navigator.of(context).push(
            MaterialPageRoute(
              builder: (_) => const DocumentFormPage(),
            ),
          ).then((_) => _onRefresh());
        },
        backgroundColor: Theme.of(context).primaryColor,
        child: const Icon(Icons.add, color: Colors.white),
      ),
      body: BlocBuilder<DocumentCubit, DocumentState>(
        builder: (context, state) {
          if (state is DocumentLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is DocumentError) {
            return Center(child: Text(state.message, style: const TextStyle(color: Colors.red)));
          } else if (state is DocumentsLoaded) {
            if (state.documents.isEmpty) {
              return const Center(child: Text('No documents found.'));
            }
            return RefreshIndicator(
              onRefresh: _onRefresh,
              child: ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: state.documents.length,
                itemBuilder: (context, index) {
                  final doc = state.documents[index];
                  return Card(
                    margin: const EdgeInsets.only(bottom: 12),
                    child: ListTile(
                      title: Text(doc.documentNumber, style: const TextStyle(fontWeight: FontWeight.bold)),
                      subtitle: Text('${doc.type.toUpperCase()} • ${doc.contactName ?? "Unknown"}'),
                      trailing: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: doc.status == 'received' ? Colors.green.withValues(alpha: 0.1) : Colors.orange.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          doc.status.toUpperCase(),
                          style: TextStyle(
                            color: doc.status == 'received' ? Colors.green : Colors.orange,
                            fontWeight: FontWeight.bold,
                            fontSize: 12,
                          ),
                        ),
                      ),
                      onTap: () {
                        Navigator.of(context).push(
                          MaterialPageRoute(
                            builder: (_) => DocumentDetailPage(document: doc),
                          ),
                        ).then((_) => _onRefresh());
                      },
                    ),
                  );
                },
              ),
            );
          }
          return const SizedBox.shrink();
        },
      ),
    );
  }
}
