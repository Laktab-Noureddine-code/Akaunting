import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../cubit/user_cubit.dart';
import '../cubit/user_state.dart';
import '../cubit/user_action_cubit.dart';
import '../cubit/user_action_state.dart';
import '../../data/models/user_model.dart';
import 'user_form_page.dart';

class UsersListPage extends StatefulWidget {
  const UsersListPage({super.key});

  @override
  State<UsersListPage> createState() => _UsersListPageState();
}

class _UsersListPageState extends State<UsersListPage> {
  @override
  void initState() {
    super.initState();
    _loadUsers();
  }

  void _loadUsers() {
    context.read<UserCubit>().getUsers();
  }

  void _confirmDelete(UserModel user) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Delete User'),
        content: Text('Are you sure you want to delete "${user.name}"?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(ctx);
              context.read<UserActionCubit>().deleteUser(user.id);
            },
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Delete'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<UserActionCubit, UserActionState>(
      listener: (context, actionState) {
        if (actionState is UserActionSaved ||
            actionState is UserActionDeleted ||
            actionState is UserActionToggled) {
          _loadUsers();