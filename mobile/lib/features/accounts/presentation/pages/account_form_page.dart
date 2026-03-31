import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../data/models/account_model.dart';
import '../../../../core/ui/components/inputs/base_input.dart';
import '../../../../core/ui/components/akaunting_money.dart';
import '../../../../core/ui/components/akaunting_radio_group.dart';
import '../../../../core/ui/components/akaunting_select.dart';
import '../../../../core/ui/components/akaunting_switch.dart';
import '../../../../core/ui/components/base_button.dart';
import '../../../../core/ui/components/cards/card.dart';
import '../../../../logic/cubits/account_cubit.dart';
import '../../../../core/di/injection_container.dart';

class AccountFormPage extends StatelessWidget {
  final AccountModel? account;

  const AccountFormPage({super.key, this.account});

  @override
  Widget build(BuildContext context) {
    return BlocProvider<AccountCubit>(
      create: (context) => sl<AccountCubit>(),
      child: _AccountFormView(account: account),
    );
  }
}

class _AccountFormView extends StatefulWidget {
  final AccountModel? account;

  const _AccountFormView({this.account});

  @override
  State<_AccountFormView> createState() => _AccountFormViewState();
}

class _AccountFormViewState extends State<_AccountFormView> {
  final _formKey = GlobalKey<FormState>();

  late int _type;