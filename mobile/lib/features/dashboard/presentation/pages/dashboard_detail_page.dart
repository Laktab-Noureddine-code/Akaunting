import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../data/models/dashboard_model.dart';
import '../../../../core/ui/components/badge.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../core/ui/components/base_button.dart';
import '../../../../logic/cubits/dashboard_cubit.dart';
import '../../../../core/di/injection_container.dart';
import 'dashboard_form_page.dart';

class DashboardDetailPage extends StatelessWidget {
  final DashboardModel dashboard;

  const DashboardDetailPage({super.key, required this.dashboard});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<DashboardCubit>(
      create: (context) => sl<DashboardCubit>(),
      child: _DashboardDetailView(initialDashboard: dashboard),
    );
  }
}

class _DashboardDetailView extends StatefulWidget {
  final DashboardModel initialDashboard;

  const _DashboardDetailView({required this.initialDashboard});

  @override
  State<_DashboardDetailView> createState() => _DashboardDetailViewState();
}

class _DashboardDetailViewState extends State<_DashboardDetailView> {
  late DashboardModel _dashboard;

  @override
  void initState() {
    super.initState();
    _dashboard = widget.initialDashboard;
  }

  void _onDashboardDeleted() {
    Navigator.of(context).pop(true);
  }

  void _confirmDelete() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Dashboard'),
        content: const Text('Are you sure you want to delete this dashboard?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(),
            child: const Text('Cancel', style: TextStyle(color: Colors.grey)),
          ),
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop();
              context.read<DashboardCubit>().deleteDashboard(_dashboard.id);
            },
            child: const Text('Delete', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<DashboardCubit, DashboardState>(
      listener: (context, state) {
        if (state is DashboardDeleted) {
          _onDashboardDeleted();
        } else if (state is DashboardSaved) {
            setState(() {
              _dashboard = state.dashboard;
            });
        } else if (state is DashboardError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(state.message), backgroundColor: Colors.red),
          );
        }
      },
      builder: (context, state) {
        final isLoading = state is DashboardLoading;

        return Scaffold(
          backgroundColor: const Color(0xFFF4F6F8),
          appBar: AppBar(
            backgroundColor: Colors.white,
            elevation: 0,
            leading: const BackButton(color: Colors.black87),
            title: Text(
              _dashboard.name,
              style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold),
            ),
            actions: [
              IconButton(
                icon: const Icon(Icons.edit, color: Colors.black87),
                onPressed: () async {
                  final updatedDashboard = await Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => DashboardFormPage(dashboard: _dashboard),
                    ),
                  );
                  if (updatedDashboard != null) {
                    setState(() {
                      _dashboard = updatedDashboard;
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
