<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.short_name')
                    ->label('Customer Short Name'),

                TextEntry::make('period')
                    ->label('Period'),
            ]);
    }
}
