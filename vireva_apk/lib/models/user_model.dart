class UserModel {
  final int id;
  final String name;
  final String email;
  final String role;
  final String? token;
  final String? phone;
  final String? nik;
  final String? alamat;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.token,
    this.phone,
    this.nik,
    this.alamat,
  });

  factory UserModel.fromJson(Map<String, dynamic> json, {String? token}) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'] ?? 'tamu',
      token: token,
      phone: json['phone'],
      nik: json['nik'],
      alamat: json['alamat'],
    );
  }

  UserModel copyWith({
    String? name,
    String? email,
    String? phone,
    String? nik,
    String? alamat,
    String? token,
  }) {
    return UserModel(
      id: id,
      name: name ?? this.name,
      email: email ?? this.email,
      role: role,
      token: token ?? this.token,
      phone: phone ?? this.phone,
      nik: nik ?? this.nik,
      alamat: alamat ?? this.alamat,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'email': email,
        'role': role,
        'phone': phone,
        'nik': nik,
        'alamat': alamat,
      };

  // Helper getters
  bool get isAdmin => role == 'admin' || role == 'superadmin';
  bool get isSuperAdmin => role == 'superadmin';
  bool get isUser => role == 'tamu' || role == 'user';

  String get displayRole {
    switch (role) {
      case 'superadmin':
        return 'Super Admin';
      case 'admin':
        return 'Admin';
      default:
        return 'Tamu';
    }
  }

  String get initials {
    final parts = name.trim().split(' ');
    if (parts.length >= 2) {
      return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    }
    return name.isNotEmpty ? name[0].toUpperCase() : '?';
  }
}
