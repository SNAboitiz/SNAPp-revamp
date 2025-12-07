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

class HourlyReportWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Hourly Report';

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
            Select::make('day')
                ->columnSpanFull()
                ->options(
                    fn (callable $get) => array_combine(
                        range(1, Carbon::createFromFormat('Y-m', (now()->year).'-'.($get('month') ?? now()->format('m')))->daysInMonth),
                        range(1, Carbon::createFromFormat('Y-m', (now()->year).'-'.($get('month') ?? now()->format('m')))->daysInMonth)
                    )
                )
                ->default(now()->day)
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
        $day = $this->filters['day'] ?? now()->day;
        $year = $this->filters['year'] ?? now()->year;

        return Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day)->format('F d, Y').' '.$this->heading;
    }

    protected function getData(): array
    {
        $month = $this->filters['month'] ?? now()->format('m');
        $day = $this->filters['day'] ?? now()->day;
        $year = $this->filters['year'] ?? now()->year;

        $startOfDay = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day)->startOfDay();
        $endOfDay = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day)->endOfDay();

        $results = Report::with('reportFile')
            ->whereHas('reportFile', function ($query) {
                $query->whereNotNull('published_at')
                    ->where('customer_id', auth()->user()->customer_id);
            })
            ->whereBetween('data->interval_start', [$startOfDay, $endOfDay])
            ->whereBetween('data->interval_end', [$startOfDay, $endOfDay])
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
                    'label' => 'Total GESQ',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('hour')->map(fn ($value) => $value.' hr')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
        return 'Energy consumption summarized by hour.';
    }
}
