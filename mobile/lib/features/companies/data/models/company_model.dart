class CompanyModel {
  final int id;
  final String name;
  final String? email;
  final String? currency;
  final String? domain;
  final String? address;
  final String? logo;
  final bool enabled;
  final String? createdFrom;
  final int? createdBy;
  final String? createdAt;
  final String? updatedAt;

  const CompanyModel({
    required this.id,
    required this.name,
    this.email,
    this.currency,
    this.domain,
    this.address,
    this.logo,
    this.enabled = true,
    this.createdFrom,
    this.createdBy,
    this.createdAt,
    this.updatedAt,
  });

  factory CompanyModel.fromJson(Map<String, dynamic> json) {
