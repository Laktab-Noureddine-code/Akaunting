import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class ReportChartsWidget extends StatelessWidget {
  final Map<String, dynamic> data;

  const ReportChartsWidget({super.key, required this.data});

  Map<String, dynamic> _safeMap(dynamic value) {
    if (value is Map<String, dynamic>) return value;
    if (value is Map) return Map<String, dynamic>.from(value);
    return {};
  }

  @override
  Widget build(BuildContext context) {
    if (data['tables'] == null || data['dates'] == null) {
      return const SizedBox.shrink();
    }

    final tables = _safeMap(data['tables']);
    final dates = List<String>.from(data['dates'] ?? []);
    final rowNames = _safeMap(data['row_names']);
    final rowValues = _safeMap(data['row_values']);
    final footerTotals = _safeMap(data['footer_totals']);

    if (dates.isEmpty || tables.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: tables.keys.map((tableKey) {
        final currentTotals = _safeMap(footerTotals[tableKey]);
        final currentNames = _safeMap(rowNames[tableKey]);
        final currentValues = _safeMap(rowValues[tableKey]);

        final hasBarData = currentTotals.values.any((v) {
          final numValue = v as num?;
          return numValue != null && numValue > 0;
        });
        final hasDonutData = currentValues.isNotEmpty;

        return Column(
          children: [
            if (hasBarData)
              _buildBarChartCard(context, dates, currentTotals),
            if (hasBarData && hasDonutData)
              const SizedBox(height: 16),
            if (hasDonutData)
              _buildDonutChartCard(context, currentNames, currentValues),
            const SizedBox(height: 24),
          ],
        );
      }).toList(),
    );
  }

  Widget _buildBarChartCard(BuildContext context, List<String> dates, Map<String, dynamic> totals) {
    final maxTotal = totals.values.fold<double>(
      0.0,
      (max, v) {
        final val = (v as num?)?.toDouble() ?? 0.0;
        return val > max ? val : max;
      },
    );

    int index = 0;
    final barGroups = dates.map((date) {
      final value = (totals[date] as num?)?.toDouble() ?? 0.0;
      final group = BarChartGroupData(
        x: index,
        barRods: [
          BarChartRodData(
            toY: value,
            color: Theme.of(context).primaryColor,
            width: 16,
            borderRadius: BorderRadius.circular(4),
          ),
        ],
      );
      index++;
      return group;
    }).toList();

    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text('Overview', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
            const SizedBox(height: 24),
            SizedBox(
              height: 200,
              child: BarChart(
                BarChartData(
                  alignment: BarChartAlignment.spaceAround,
                  maxY: maxTotal > 0 ? maxTotal * 1.2 : 100,
                  barTouchData: BarTouchData(
                    touchTooltipData: BarTouchTooltipData(
                      getTooltipColor: (group) => Colors.blueGrey,
                      getTooltipItem: (group, groupIndex, rod, rodIndex) {
                        return BarTooltipItem(
                          '\${dates[group.x.toInt()]}\n',
                          const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                          children: [
                            TextSpan(
                              text: rod.toY.toStringAsFixed(2),
                              style: const TextStyle(color: Colors.yellowAccent),
                            ),
                          ],
                        );
                      },
                    ),
                  ),
                  titlesData: FlTitlesData(
                    show: true,
                    topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                    rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                    leftTitles: AxisTitles(
                      sideTitles: SideTitles(
                        showTitles: true,
                        reservedSize: 40,
                        getTitlesWidget: (value, meta) {
                          if (value == maxTotal || value == 0) return const SizedBox.shrink();
                          return Text(
                            value >= 1000 ? '\${(value / 1000).toStringAsFixed(1)}k' : value.toStringAsFixed(0),
                            style: const TextStyle(color: Colors.grey, fontSize: 10),
                          );
                        },
                      ),
                    ),
                    bottomTitles: AxisTitles(
                      sideTitles: SideTitles(
                        showTitles: true,
                        getTitlesWidget: (value, meta) {
                          final idx = value.toInt();
                          if (idx < 0 || idx >= dates.length) return const SizedBox.shrink();
                          return Padding(
                            padding: const EdgeInsets.only(top: 8.0),
                            child: Text(
                              dates[idx].split(' ').first,
                              style: const TextStyle(color: Colors.grey, fontSize: 10),
                            ),
                          );
                        },
                      ),
                    ),
                  ),
                  gridData: FlGridData(
                    show: true,
                    drawVerticalLine: false,
                    horizontalInterval: maxTotal > 0 ? maxTotal / 4 : 25,
                    getDrawingHorizontalLine: (value) => FlLine(
                      color: Colors.grey[200],
                      strokeWidth: 1,
                    ),
                  ),
                  borderData: FlBorderData(show: false),
                  barGroups: barGroups,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDonutChartCard(BuildContext context, Map<String, dynamic> names, Map<String, dynamic> values) {
    final Map<String, double> rowTotals = {};

    for (final key in values.keys) {
      final arr = values[key] as List<dynamic>? ?? [];
      double sum = 0;
      for (final val in arr) {
        sum += (val as num?)?.toDouble() ?? 0.0;
      }
      if (sum > 0) {
        rowTotals[key] = sum;
      }
    }

    if (rowTotals.isEmpty) return const SizedBox.shrink();

