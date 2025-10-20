<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Enums\InquiryType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Type')
                    ->options(InquiryType::class)
                    ->required(),

                Textarea::make('message')
                    ->label('Message')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
