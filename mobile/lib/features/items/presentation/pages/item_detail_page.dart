import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../data/models/item_model.dart';
import '../../../../core/ui/components/badge.dart';
import '../../../../core/ui/components/cards/stats_card.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../core/ui/components/base_button.dart';
import '../../../../logic/cubits/item_cubit.dart';
import '../../../../core/di/injection_container.dart';
import 'item_form_page.dart';

class ItemDetailPage extends StatelessWidget {
  final ItemModel item;

  const ItemDetailPage({super.key, required this.item});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ItemCubit>(
      create: (context) => sl<ItemCubit>(),
      child: _ItemDetailView(initialItem: item),
    );
  }
}

class _ItemDetailView extends StatefulWidget {
  final ItemModel initialItem;

  const _ItemDetailView({required this.initialItem});

  @override
  State<_ItemDetailView> createState() => _ItemDetailViewState();
}

class _ItemDetailViewState extends State<_ItemDetailView> {
  late ItemModel _item;

  @override
  void initState() {
    super.initState();
    _item = widget.initialItem;
  }

  void _onItemDeleted() {
    Navigator.of(context).pop(true);
  }

  void _confirmDelete() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Item'),
        content: const Text('Are you sure you want to delete this item?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: const Text('Cancel', style: TextStyle(color: Colors.grey)),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop();
              context.read<ItemCubit>().deleteItem(_item.id);
            },
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ItemCubit, ItemState>(
      listener: (context, state) {
        if (state is ItemDeleted) {
          _onItemDeleted();
        } else if (state is ItemSaved) {
            setState(() {
              _item = state.item;
            });
        } else if (state is ItemError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(state.message), backgroundColor: Colors.red),
          );
        }
      },
      builder: (context, state) {
        final isLoading = state is ItemLoading;

        return Scaffold(
          backgroundColor: const Color(0xFFF4F6F8),
          appBar: AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            leading: const BackButton(color: Colors.black87),
            title: Text(
              _item.name,
              style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
            ),
            actions: [
              IconButton(
                icon: const Icon(Icons.edit, color: Colors.black87),
                onPressed: () async {
                  final updatedItem = await Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => ItemFormPage(item: _item),
                    ),
                  );
                  if (updatedItem != null) {
                    setState(() {
                      _item = updatedItem;
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
