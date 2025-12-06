<?php

namespace App\Filament\Resources\CustomerTaxDocuments\Pages;

use App\Filament\Resources\CustomerTaxDocuments\CustomerTaxDocumentResource;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\View\View;

class ManageCustomerTaxDocuments extends ManageRecords
{
    protected static string $resource = CustomerTaxDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getHeader(): View
    {
        return view('filament.resources.report.pages.header', [
            'actions' => $this->getHeaderActions(),
            'title' => 'Manage Payments',
        ]);
    }
}
