<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Illuminate\Contracts\Support\Htmlable;

class DailyReportWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Daily Report';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('month')
                ->columnSpanFull()
                ->options([
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                ])
                ->default(now()->format('m'))
                ->preload()
                ->selectablePlaceholder(false),
            Select::make('year')
                ->columnSpanFull()
                ->options(
                    array_combine(
                        range(\Carbon\Carbon::now()->year, 2000),
                        range(\Carbon\Carbon::now()->year, 2000)
                    )
                )
                ->default(\Carbon\Carbon::now()->year)
                ->preload()
                ->selectablePlaceholder(false),
        ]);
    }

    public function getHeading(): string|Htmlable|null
    {
        $month = $this->filters['month'] ?? now()->format('m');
        $year = $this->filters['year'] ?? now()->year;

        return Carbon::createFromFormat('Y-m', $year.'-'.$month)->format('F Y').' '.$this->heading;
    }

    protected function getData(): array
    {
        $month = $this->filters['month'] ?? now()->format('m');
        $year = $this->filters['year'] ?? now()->year;

        $startOfMonth = Carbon::createFromFormat('Y-m', $year.'-'.$month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $year.'-'.$month)->endOfMonth();

        $results = Report::with('reportFile')
            ->whereHas('reportFile', function ($query) {
                $query->whereNotNull('published_at')
                    ->where('customer_id', auth()->user()->customer_id);
            })
            ->whereBetween('data->interval_start', [$startOfMonth, $endOfMonth])
            ->whereBetween('data->interval_end', [$startOfMonth, $endOfMonth])
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
                    'label' => 'Total GESQ',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('day')->map(fn ($day) => Carbon::createFromFormat('Y-m-d', $day)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => context.dataset.label + ': ' + context.formattedValue + ' kWh',
                    },
                },
            },
            scales: {
                y: {
                    ticks: {
                        callback: (value) => value + ' kWh',
                    },
                },
            }
        }
    JS);
    }

    public function getDescription(): ?string
    {
        return 'Energy consumption summarized by day.';
    }
}
