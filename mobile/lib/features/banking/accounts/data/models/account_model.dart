class AccountModel {
  final int id;
  final String name;
  final String number;
  final String currencyCode;
  final double openingBalance;
  final double currentBalance;
  final String bankName;
  final String bankPhone;
  final String bankAddress;
  final bool enabled;

  AccountModel({
    required this.id,
    required this.name,
    required this.number,
    required this.currencyCode,
    required this.openingBalance,
    required this.currentBalance,
    required this.bankName,
    required this.bankPhone,
    required this.bankAddress,
    required this.enabled,
  });

  factory AccountModel.fromJson(Map<String, dynamic> json) {
    return AccountModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      number: json['number'] ?? '',
      currencyCode: json['currency_code'] ?? '',
      openingBalance: double.tryParse(json['opening_balance']?.toString() ?? '0') ?? 0.0,
      currentBalance: double.tryParse(json['balance']?.toString() ?? '0') ?? 0.0,
      bankName: json['bank_name'] ?? '',
      bankPhone: json['bank_phone'] ?? '',
      bankAddress: json['bank_address'] ?? '',
      enabled: json['enabled'] == true || json['enabled'] == 1,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'number': number,
      'currency_code': currencyCode,
      'opening_balance': openingBalance,
      'bank_name': bankName,
      'bank_phone': bankPhone,
      'bank_address': bankAddress,
      'enabled': enabled ? 1 : 0,
    };
  }
}
