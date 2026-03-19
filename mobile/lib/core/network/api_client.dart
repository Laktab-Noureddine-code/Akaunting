import 'package:dio/dio.dart';
import 'auth_interceptor.dart';

class ApiClient {
  late final Dio dio;

  ApiClient({
    String baseUrl = 'http://192.168.1.107:8000',
    AuthInterceptor? authInterceptor,
  }) {
    dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));

    if (authInterceptor != null) {
      dio.interceptors.add(authInterceptor);
    }
  }
}
