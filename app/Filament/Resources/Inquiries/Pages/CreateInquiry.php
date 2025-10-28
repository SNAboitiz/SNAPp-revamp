<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class CreateInquiry extends CreateRecord
{
    protected static string $resource = InquiryResource::class;

    protected string $view = 'filament.resources.helpdesk.pages.create-helpdesk';

    public function getHeader(): View
    {
        return view('filament.resources.helpdesk.pages.create-helpdesk-header');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->databaseTransaction()
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Arr::set($data, 'user_id', auth()->id());

        return $data;
    }

    public function create(bool $another = false): void
    {
        parent::create($another);

        $this->fillForm();
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label('Submit Inquiry');
    }
}
