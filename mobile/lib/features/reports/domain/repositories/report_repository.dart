import '../../../../core/network/api_client.dart';
import '../../data/models/report_model.dart';

class ReportRepository {
  final ApiClient apiClient;

  ReportRepository({required this.apiClient});

  Future<List<ReportModel>> getReports() async {
    final response = await apiClient.dio.get('/api/reports');
    final dynamic responseData = response.data['data'];
    if (responseData is List) {
      return responseData.map((json) => ReportModel.fromJson(json)).toList();
    }
    return [];
  }

  Future<ReportModel> getReport(int id) async {
    final response = await apiClient.dio.get('/api/reports/$id');

    if (response.data is Map<String, dynamic>) {
      final Map<String, dynamic> data = response.data;
