<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->hidden(fn () => $this->record->published_at !== null)
                ->label('Publish Report')
                ->action(function () {
                    $this->record->published_at = now();
                    $this->record->save();
                })
                ->requiresConfirmation()
                ->color('primary'),

            Action::make('unpublish')
                ->hidden(fn () => $this->record->published_at === null)
                ->label('Unpublish Report')
                ->action(function () {
                    $this->record->published_at = null;
                    $this->record->save();
                })
                ->requiresConfirmation()
                ->color('warning'),

            Action::make('delete')
                ->label('Delete Report')
                ->action(function () {
                    $this->record->delete();
                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->requiresConfirmation()
                ->color('danger'),
        ];
    }

    public function getHeader(): View
    {
        return view('filament.resources.report.pages.header', [
            'title' => $this->record->filename,
            'actions' => $this->getHeaderActions(),
        ]);
    }
}
