<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class DashboardPage extends Dashboard
{
    use HasFiltersForm;

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public function filtersForm(Schema $schema): Schema
    {
        // TODO: add widget legend
        return $schema
            ->components([
                Section::make()
                    ->schema([
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
                    ])
                    ->columns(1),
            ]);
    }
}
