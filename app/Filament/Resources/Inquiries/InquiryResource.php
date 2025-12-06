<?php

namespace App\Filament\Resources\Inquiries;

use App\Enums\InquiryType;
use App\Filament\Resources\Inquiries\Pages\ManageInquiries;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Request type')
                    ->options(InquiryType::class)
                    ->required(),

                RichEditor::make('message')
                    ->label('Request details')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk(config('filesystems.default'))
                    ->fileAttachmentsDirectory('inquiries')
                    ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg'])
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'link', 'attachFiles'],
                        ['undo', 'redo'],
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->placeholder('Pending'),

                TextEntry::make('type')
                    ->badge()
                    ->placeholder('-'),

                TextEntry::make('message')
                    ->html()
                    ->prose()
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('status')
                    ->placeholder('Pending'),

                TextColumn::make('type')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInquiries::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('customer');
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->hasRole('customer');
    }
}
