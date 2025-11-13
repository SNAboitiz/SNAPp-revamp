<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Enums\InquiryType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('type')
                    ->label('Request type')
                    ->options(InquiryType::class)
                    ->required(),

                // TODO: support documents
                // FileUpload::make('documents')
                //     ->label('Supporting Documents')
                //     ->multiple(),

                RichEditor::make('message')
                    ->label('Request details')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'link'],
                        ['undo', 'redo'],
                    ]),
            ]);
    }
}
