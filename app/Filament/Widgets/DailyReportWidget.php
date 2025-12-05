<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DailyReportWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Daily Report';

    protected function getData(): array
    {
        $year = $this->pageFilters['year'] ?? now()->year;

        $results = Report::with('reportFile')
            ->whereHas('reportFile', function ($query) {
                $query->whereNotNull('published_at')
                    ->where('customer_id', auth()->user()->customer_id);
            })
            ->whereBetween('data->interval_start', [
                Carbon::createFromDate($year)->startOfYear(),
                Carbon::createFromDate($year)->endOfYear(),
            ])
            ->whereBetween('data->interval_end', [
                Carbon::createFromDate($year)->startOfYear(),
                Carbon::createFromDate($year)->endOfYear(),
            ])
            ->selectRaw("
                DATE(JSON_UNQUOTE(JSON_EXTRACT(data, '$.interval_start'))) AS day,
                SUM(JSON_UNQUOTE(JSON_EXTRACT(data, '$.gesq'))) AS total_gesq
            ")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Reports',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('day')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
