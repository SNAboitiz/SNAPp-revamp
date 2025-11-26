<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\View\View;

class ManageInquiries extends ManageRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Submit Inquiry')
                ->databaseTransaction()
                ->createAnother(false)
                ->mutateDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
        ];
    }

    public function getHeader(): View
    {
        return view('filament.resources.helpdesk.pages.create-helpdesk-header', [
            'actions' => $this->getHeaderActions(),
        ]);
    }
}
