import 'package:flutter/material.dart';
import '../../../../../core/di/injection_container.dart';
import '../../../../../core/ui/components/inputs/base_input.dart';
import '../../../../../core/ui/components/base_button.dart';
import '../../../../../core/ui/components/akaunting_switch.dart';
import '../../domain/repositories/account_repository.dart';
import '../../data/models/account_model.dart';

class EditAccountPage extends StatefulWidget {
  final AccountModel? account;

  const EditAccountPage({super.key, this.account});

  @override
  State<EditAccountPage> createState() => _EditAccountPageState();
}

class _EditAccountPageState extends State<EditAccountPage> {
  final _formKey = GlobalKey<FormState>();
  final _repository = sl<AccountRepository>();

  late TextEditingController _nameController;
  late TextEditingController _numberController;
  late TextEditingController _openingBalanceController;
  late TextEditingController _bankNameController;
  late TextEditingController _bankPhoneController;
  late TextEditingController _bankAddressController;
  String _currencyCode = 'USD';
  bool _enabled = true;
  bool _isSaving = false;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.account?.name ?? '');
    _numberController = TextEditingController(text: widget.account?.number ?? '');
    _openingBalanceController = TextEditingController(
      text: widget.account?.openingBalance.toString() ?? '0.0',
    );
    _bankNameController = TextEditingController(text: widget.account?.bankName ?? '');
    _bankPhoneController = TextEditingController(text: widget.account?.bankPhone ?? '');
    _bankAddressController = TextEditingController(text: widget.account?.bankAddress ?? '');
    _currencyCode = widget.account?.currencyCode ?? 'USD';
    _enabled = widget.account?.enabled ?? true;
  }

  @override
  void dispose() {
    _nameController.dispose();
    _numberController.dispose();
    _openingBalanceController.dispose();
    _bankNameController.dispose();
    _bankPhoneController.dispose();
    _bankAddressController.dispose();
    super.dispose();
  }

  Future<void> _saveAccount() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSaving = true);
    try {
      final accountData = AccountModel(
        id: widget.account?.id ?? 0,
        name: _nameController.text,
        number: _numberController.text,
        currencyCode: _currencyCode,
        openingBalance: double.tryParse(_openingBalanceController.text) ?? 0.0,
        currentBalance: widget.account?.currentBalance ?? (double.tryParse(_openingBalanceController.text) ?? 0.0),
        bankName: _bankNameController.text,
        bankPhone: _bankPhoneController.text,
        bankAddress: _bankAddressController.text,
        enabled: _enabled,
      );

      if (widget.account == null) {
        await _repository.createAccount(accountData);
      } else {
        await _repository.updateAccount(widget.account!.id, accountData);
      }

      if (mounted) {
        Navigator.of(context).pop(true);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error saving account: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isSaving = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final isEditing = widget.account != null;

    return Scaffold(
      appBar: AppBar(
        title: Text(isEditing ? 'Edit Account' : 'New Account'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              BaseInput(
                label: 'Name',
                isRequired: true,
                controller: _nameController,
                placeholder: 'e.g. Petty Cash',
              ),
              const SizedBox(height: 16),
              BaseInput(
                label: 'Number',
                isRequired: true,
                controller: _numberController,
                placeholder: 'e.g. 1000-00',
              ),
              const SizedBox(height: 16),
              // Simplified currency selector for now. Ideally use AkauntingSelect
              DropdownButtonFormField<String>(
                value: _currencyCode,
                decoration: const InputDecoration(
                  labelText: 'Currency',
                  border: OutlineInputBorder(),
                ),
                items: ['USD', 'EUR', 'GBP', 'TRY'].map((e) {
                  return DropdownMenuItem(value: e, child: Text(e));
                }).toList(),
                onChanged: (val) {
                  if (val != null) setState(() => _currencyCode = val);
                },
              ),
              const SizedBox(height: 16),
              BaseInput(
                label: 'Opening Balance',
                controller: _openingBalanceController,
                keyboardType: const TextInputType.numberWithOptions(decimal: true),
                placeholder: '0.00',
                disabled: isEditing, // Usually cannot change opening balance after creation
              ),
              const SizedBox(height: 24),
              const Text('Bank Details', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const Divider(),
              const SizedBox(height: 8),
              BaseInput(
                label: 'Bank Name',
                controller: _bankNameController,
                placeholder: 'e.g. Bank of America',
              ),
              const SizedBox(height: 16),
              BaseInput(
                label: 'Bank Phone',
                controller: _bankPhoneController,
                keyboardType: TextInputType.phone,
                placeholder: 'e.g. +1-800-432-1000',
              ),
              const SizedBox(height: 16),
              BaseInput(
                label: 'Bank Address',
                controller: _bankAddressController,
                placeholder: 'e.g. 100 N Tryon St',
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  const Text('Enabled'),
                  const Spacer(),
                  AkauntingSwitch(
                    value: _enabled,
                    onChanged: (val) {
                      setState(() => _enabled = val);
                    },
                  ),
                ],
              ),
              const SizedBox(height: 32),
              SizedBox(
                width: double.infinity,
                child: BaseButton(
                  type: ButtonType.primary,
                  onPressed: _isSaving ? null : _saveAccount,
                  loading: _isSaving,
                  child: Text(isEditing ? 'Save Changes' : 'Create Account'),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
