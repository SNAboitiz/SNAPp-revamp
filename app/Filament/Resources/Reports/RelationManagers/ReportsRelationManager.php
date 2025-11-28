<?php

namespace App\Filament\Resources\Reports\RelationManagers;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $relatedResource = ReportResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data.interval_start')
                    ->label('Interval Start')
                    ->dateTime(),

                TextColumn::make('data.interval_end')
                    ->label('Interval End')
                    ->dateTime(),

                TextColumn::make('data.day')
                    ->label('Day'),

                TextColumn::make('data.hour')
                    ->label('Hour'),

                TextColumn::make('data.gesq')
                    ->label('GESQ'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
