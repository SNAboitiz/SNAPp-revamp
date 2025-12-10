<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Customer;
use App\Models\Report;
use App\Models\ReportFile;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalSubmitActionLabel('Import')
                ->modalHeading('Import Reports')
                ->createAnother(false)
                ->databaseTransaction()
                ->label('Import Reports')
                ->action(function (array $data) {
                    DB::beginTransaction();

                    try {
                        $files = $data['files'] ?? [];

                        foreach ($files as $file) {
                            $handle = Storage::readStream($file->getRealPath());

                            $uuid = Str::uuid()->toString();

                            // Skip header row
                            $name = fgetcsv($handle)[0];

                            $customer = Customer::where('short_name', $name)->first()?->id;

                            if (! $customer) {
                                fclose($handle);
                                throw new \Exception("Customer '{$name}' not found");
                            }

                            fgetcsv($handle); // skip second row

                            $period = fgetcsv($handle)[0];

                            fgetcsv($handle); // skip fourth row
                            fgetcsv($handle); // skip fifth row
                            fgetcsv($handle); // skip sixth row

                            $records = [];

                            $reportFile = ReportFile::create([
                                'customer_id' => $customer,
                                'uuid' => $uuid,
                                'filename' => $file->getClientOriginalName(),
                                'period' => $period,
                            ]);

                            $records = [];
                            $batchSize = 1000;

                            while (($row = fgetcsv($handle)) !== false) {
                                if (count($row) < 5) {
                                    continue;
                                }

                                $records[] = [
                                    'report_file_id' => $reportFile->id,
                                    'data' => json_encode([
                                        'interval_start' => trim($row[0]),
                                        'interval_end' => trim($row[1]),
                                        'day' => trim($row[2]),
                                        'hour' => trim($row[3]),
                                        'gesq' => trim($row[4]) === '-' ? 0.0 : (float) $row[4],
                                    ]),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];

                                if (count($records) >= $batchSize) {
                                    Report::insert($records);
                                    $records = []; // Reset array
                                }
                            }

                            if (! empty($records)) {
                                array_pop($records);
                                Report::insert($records);
                            }

                            fclose($handle);

                            Storage::delete($file->getRealPath());
                        }

                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();

                        Notification::make()
                            ->title('Import Failed')
                            ->body('An error occurred during import: '.$th->getMessage())
                            ->danger()
                            ->send();

                        $this->halt(true);
                    }
                }),
        ];
    }

    public function getHeader(): View
    {
        return view('filament.resources.report.pages.header', [
            'actions' => $this->getHeaderActions(),
        ]);
    }
}
