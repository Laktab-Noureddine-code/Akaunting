import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../cubit/user_action_cubit.dart';
import '../cubit/user_action_state.dart';
import '../../data/models/user_model.dart';
import '../../../companies/presentation/cubit/company_cubit.dart';
import '../../../companies/presentation/cubit/company_state.dart';
import '../../../companies/data/models/company_model.dart';

class UserFormPage extends StatefulWidget {
  final UserModel? user;

  const UserFormPage({super.key, this.user});

  @override
  State<UserFormPage> createState() => _UserFormPageState();
}

class _UserFormPageState extends State<UserFormPage> {
  final _formKey = GlobalKey<FormState>();

  late TextEditingController _nameController;
  late TextEditingController _emailController;
  late TextEditingController _passwordController;
  late TextEditingController _passwordConfirmController;
  late TextEditingController _landingPageController;
  late String _selectedRole;
  late bool _enabled;
  List<int> _selectedCompanyIds = [];

  bool get isEditing => widget.user != null;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.user?.name ?? '');
    _emailController = TextEditingController(text: widget.user?.email ?? '');
    _passwordController = TextEditingController();
    _passwordConfirmController = TextEditingController();
    _landingPageController = TextEditingController(
      text: widget.user?.landingPage ?? '/',
    );
    _selectedRole = '1';
    _enabled = widget.user?.enabled ?? true;

