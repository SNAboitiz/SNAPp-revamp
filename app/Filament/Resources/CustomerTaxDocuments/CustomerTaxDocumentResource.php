<?php

namespace App\Filament\Resources\CustomerTaxDocuments;

use App\Filament\Resources\CustomerTaxDocuments\Pages\ManageCustomerTaxDocuments;
use App\Models\CustomerTaxDocument;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerTaxDocumentResource extends Resource
{
    protected static ?string $model = CustomerTaxDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'document_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                TextColumn::make('customer.account_name')
                    ->searchable()
                    ->label('Customer'),

                TextColumn::make('facility.name')
                    ->placeholder('-')
                    ->searchable()
                    ->label('Facility'),

                TextColumn::make('document_number')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Download 2307')
                    ->label('Download 2307')
                    ->color('secondary')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->action(fn (Model $record) => Storage::download($record->file_path, $record->customer->short_name.'_'.$record->billing_period.'_2307'.'.pdf')),

                Action::make('Download Official Receipt')
                    ->color('secondary')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->disabled(fn (Model $record) => ! $record->official_receipt)
                    ->action(fn (Model $record) => Storage::download($record->official_receipt, $record->customer->short_name.'_'.$record->billing_period.'_official_receipt'.'.pdf')),

                Action::make('Upload Official Receipt')
                    ->icon(Heroicon::OutlinedArrowUpTray)
                    ->schema([
                        FileUpload::make('official_receipt')
                            ->disk(config('filesystems.default'))
                            ->directory('official-receipts')
                            ->visibility('private')
                            ->label('Official Receipt')
                            ->required()
                            ->moveFiles(),
                    ])
                    ->action(fn (array $data, Model $record) => $record->update(['official_receipt' => $data['official_receipt']])),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCustomerTaxDocuments::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
