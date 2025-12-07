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

class MonthlyReportWidget extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 0;

    protected ?string $heading = 'Monthly Report';

    protected $year;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
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
        return $this->year.' '.$this->heading;
    }

    protected function getData(): array
    {
        $this->year = $this->filters['year'] ?? now()->year;

        $results = Report::with('reportFile')
            ->whereHas('reportFile', function ($query) {
                $query->whereNotNull('published_at')
                    ->where('customer_id', auth()->user()->customer_id);
            })
            ->whereBetween('data->interval_start', [
                Carbon::createFromDate($this->year)->startOfYear(),
                Carbon::createFromDate($this->year)->endOfYear(),
            ])
            ->whereBetween('data->interval_end', [
                Carbon::createFromDate($this->year)->startOfYear(),
                Carbon::createFromDate($this->year)->endOfYear(),
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
                    'label' => 'Total GESQ',
                    'data' => $results->pluck('total_gesq')->toArray(),
                ],
            ],
            'labels' => $results->pluck('month')->map(fn ($month) => Carbon::createFromFormat('Y-m', $month)->format('M'))->toArray(),
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
        return 'Energy consumption summarized by month.';
    }
}
