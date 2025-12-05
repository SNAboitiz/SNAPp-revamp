<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class HourlyReportWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Hourly Report';

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
                JSON_UNQUOTE(JSON_EXTRACT(data, '$.hour')) AS hour,
                SUM(JSON_UNQUOTE(JSON_EXTRACT(data, '$.gesq'))) AS total_gesq
            ")
            ->groupBy('hour')
            ->orderByRaw('CAST(hour AS UNSIGNED)')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Hourly Reports',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('hour')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
