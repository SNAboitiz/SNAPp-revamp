<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('customer.short_name')
                    ->label('Customer Short Name'),

                TextColumn::make('filename')
                    ->label('Filename'),

                TextColumn::make('period')
                    ->label('Period'),

                ToggleColumn::make('published_at')
                    ->label('Publish')
                    ->getStateUsing(fn ($record) => $record->published_at !== null)
                    ->updateStateUsing(function ($record, $state) {
                        $record->update([
                            'published_at' => $state ? now() : null,
                        ]);
                    }),
            ])
            ->filters([
                TernaryFilter::make('published_at')
                    ->label('Publication status')
                    ->placeholder('All statuses')
                    ->trueLabel('Published reports')
                    ->falseLabel('Unpublished reports')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('published_at'),
                        false: fn (Builder $query) => $query->whereNull('published_at'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['published_at' => now()]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    BulkAction::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['published_at' => null]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
