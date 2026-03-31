class ProfileCompanyModel {
  final int id;
  final String name;
  final String? email;
  final String? domain;
  final String? currency;
  final bool enabled;

  const ProfileCompanyModel({
    required this.id,
    required this.name,
    this.email,
    this.domain,
    this.currency,
    this.enabled = true,
  });

  factory ProfileCompanyModel.fromJson(Map<String, dynamic> json) {
    return ProfileCompanyModel(
      id: json['id'] as int,
      name: json['name'] as String? ?? 'Unknown Company',
      email: json['email'] as String?,
      domain: json['domain'] as String?,
      currency: json['currency'] as String?,
      enabled: json['enabled'] == true || json['enabled'] == 1,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'email': email,
    'domain': domain,
    'currency': currency,
    'enabled': enabled,
  };
}

class UserProfileModel {
  final int id;
  final String name;
  final String email;
  final String? locale;
  final String? landingPage;
  final bool enabled;
  final String? lastLoggedInAt;
  final String? createdAt;
  final List<ProfileCompanyModel> companies;
  final List<String> roles;

  const UserProfileModel({
    required this.id,
    required this.name,
    required this.email,
    this.locale,
    this.landingPage,
    this.enabled = true,
    this.lastLoggedInAt,
    this.createdAt,
    this.companies = const [],
    this.roles = const [],
  });

  factory UserProfileModel.fromJson(Map<String, dynamic> json) {
