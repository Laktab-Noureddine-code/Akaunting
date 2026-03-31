import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../data/models/contact_model.dart';
import '../../../../core/ui/components/badge.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../core/ui/components/base_button.dart';
import '../../../../logic/cubits/contact_cubit.dart';
import '../../../../core/di/injection_container.dart';
import 'contact_form_page.dart';

class ContactDetailPage extends StatelessWidget {
  final ContactModel contact;

  const ContactDetailPage({super.key, required this.contact});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ContactCubit>(
      create: (context) => sl<ContactCubit>(),
      child: _ContactDetailView(initialContact: contact),
    );
  }
}

class _ContactDetailView extends StatefulWidget {
  final ContactModel initialContact;

  const _ContactDetailView({required this.initialContact});

  @override
  State<_ContactDetailView> createState() => _ContactDetailViewState();
}

class _ContactDetailViewState extends State<_ContactDetailView> {
  late ContactModel _contact;

  @override
  void initState() {
    super.initState();
    _contact = widget.initialContact;
  }

  void _onContactDeleted() {
    Navigator.of(context).pop(true);
  }

  void _confirmDelete() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Contact'),
        content: const Text('Are you sure you want to delete this contact?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: const Text('Cancel', style: TextStyle(color: Colors.grey)),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop();
              context.read<ContactCubit>().deleteContact(_contact.id);
            },
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ContactCubit, ContactState>(
      listener: (context, state) {
        if (state is ContactDeleted) {
          _onContactDeleted();
        } else if (state is ContactSaved) {
            setState(() {
              _contact = state.contact;
            });
        } else if (state is ContactError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(state.message), backgroundColor: Colors.red),
          );
        }
      },
      builder: (context, state) {
        final isLoading = state is ContactLoading;

        return Scaffold(
          backgroundColor: const Color(0xFFF4F6F8),
          appBar: AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            leading: const BackButton(color: Colors.black87),
            title: Text(
              _contact.name,
              style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
            ),
            actions: [
              IconButton(
                icon: const Icon(Icons.edit, color: Colors.black87),
                onPressed: () async {
                  final updatedContact = await Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => ContactFormPage(contact: _contact),
                    ),
                  );
                  if (updatedContact != null) {
                    setState(() {
                      _contact = updatedContact;
                    });
                  }
                },
              ),
              IconButton(
                icon: const Icon(Icons.delete, color: Colors.red),
                onPressed: _confirmDelete,
              ),
            ],
          ),
          body: isLoading ? const Center(child: CircularProgressIndicator()) : SingleChildScrollView(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
