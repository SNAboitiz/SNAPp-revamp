<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Customer;
use App\Models\Report;
use App\Models\ReportFile;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_reports')
                ->databaseTransaction()
                ->label('Import Reports')
                ->schema([
                    FileUpload::make('files')
                        ->label('Select Report CSV Files')
                        ->multiple()
                        ->directory('reports')
                        ->maxFiles(50)
                        ->storeFiles(false)
                        ->disk(config('filesystems.default'))
                        ->acceptedFileTypes(['text/csv', '.csv'])
                        ->panelLayout('grid')
                        ->required(),
                ]),
            // ->action(function (array $data) {
            //     DB::beginTransaction();

            //     try {
            //         $files = $data['files'] ?? [];

            //         foreach ($files as $file) {
            //             // Get the real path from the TemporaryUploadedFile object
            //             $filePath = $file->getRealPath();

            //             $uuid = Str::uuid()->toString();

            //             if (($handle = fopen($filePath, 'r')) !== false) {
            //                 // Skip header row
            //                 $name = fgetcsv($handle)[0];

            //                 $customer = Customer::where('short_name', $name)->first()?->id;

            //                 if (! $customer) {
            //                     fclose($handle);
            //                     throw new \Exception("Customer '{$name}' not found");
            //                 }

            //                 fgetcsv($handle); // skip second row

            //                 $period = fgetcsv($handle)[0];

            //                 fgetcsv($handle); // skip fourth row
            //                 fgetcsv($handle); // skip fifth row
            //                 fgetcsv($handle); // skip sixth row

            //                 $records = [];

            //                 $reportFile = ReportFile::create([
            //                     'customer_id' => $customer,
            //                     'uuid' => $uuid,
            //                     'filename' => $file->getClientOriginalName(),
            //                     'period' => $period,
            //                 ]);

            //                 while (($row = fgetcsv($handle)) !== false) {
            //                     $records[] = [
            //                         'report_file_id' => $reportFile->id,
            //                         'data' => json_encode([
            //                             'interval_start' => trim($row[0]),
            //                             'interval_end' => trim($row[1]),
            //                             'day' => trim($row[2]),
            //                             'hour' => trim($row[3]),
            //                             'gesq' => trim($row[4]) === '-' ? 0.0 : (float) $row[4],
            //                         ]),
            //                         'created_at' => now(),
            //                         'updated_at' => now(),
            //                     ];
            //                 }

            //                 fclose($handle);

            //                 array_pop($records);

            //                 // Bulk insert all records at once
            //                 if (! empty($records)) {
            //                     Report::insert($records);
            //                 }
            //             }
            //         }

            //         DB::commit();
            //     } catch (\Throwable $th) {
            //         DB::rollBack();

            //         Notification::make()
            //             ->title('Import Failed')
            //             ->body('An error occurred during import: ' . $th->getMessage())
            //             ->danger()
            //             ->send();

            //         $this->halt(true);
            //     }
            // }),
        ];
    }

    public function getHeader(): View
    {
        return view('filament.resources.report.pages.header', [
            'actions' => $this->getHeaderActions(),
        ]);
    }
}
