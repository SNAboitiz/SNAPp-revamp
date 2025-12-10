<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                FileUpload::make('files')
                    ->label('Select Report CSV Files')
                    ->multiple()
                    ->maxFiles(50)
                    ->storeFiles(false)
                    ->disk(config('filesystems.default'))
                    ->acceptedFileTypes(['text/csv', '.csv'])
                    ->visibility('private')
                    ->required(),
            ]);
    }
}
