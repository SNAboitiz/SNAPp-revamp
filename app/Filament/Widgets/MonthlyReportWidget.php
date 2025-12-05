<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class MonthlyReportWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected ?string $heading = 'Monthly Report';

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
                DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(data, '$.interval_start')), '%Y-%m') AS month,
                SUM(JSON_UNQUOTE(JSON_EXTRACT(data, '$.gesq'))) AS total_gesq
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Reports',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
