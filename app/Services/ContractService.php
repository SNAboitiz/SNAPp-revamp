<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContractService
{
    protected GcsService $gcsService;

    public function __construct(GcsService $gcsService)
    {
        $this->gcsService = $gcsService;
    }

    public function getAllContracts(): LengthAwarePaginator
    {
        $disk = config('filesystems.default');

        // Get all contracts ordered by created_at descending, paginated (5 per page)
        $dbContracts = Contract::orderBy('created_at', 'desc')->paginate(5);

        // Map over paginated items
        $dbContracts->getCollection()->transform(function ($c) use ($disk) {
            $status = 'unknown';
            if ($c->contract_end) {
                $endDate = Carbon::parse($c->contract_end);
                $status = $endDate->isPast() ? 'Expired' : 'Active';
            }

            return [
                'reference_number' => $c->reference_number,
                'contract_name' => $c->description,
                'shortname' => $c->shortname,
                'contract_period' => $c->contract_period,
                'contract_end' => $c->contract_end,
                'upload_date' => $c->created_at->format('d-M-Y'),
                'status' => $status,
                'gcsPdfUrl' => $this->resolveFileUrl($c->document, $disk),
            ];
        });

        return $dbContracts;
    }

    public function getContractForUser($user): LengthAwarePaginator
    {
        $short = $user->profile->short_name ?? null;
        if (! $short) {
            Log::warning("User {$user->id} has no short_name.");

            return new LengthAwarePaginator([], 0, 5);
        }

        $disk = config('filesystems.default');

        // Get DB contracts filtered by short_name, ordered newest first
        $dbContracts = Contract::where('short_name', $short)
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Pagination (5 per page)

        // Transform each contract
        $mappedContracts = $dbContracts->getCollection()->transform(function ($c) use ($disk) {
            $status = 'unknown';
            if ($c->contract_end) {
                $endDate = Carbon::parse($c->contract_end);
                $status = $endDate->isPast() ? 'Expired' : 'Active';
            }

            return [
                'reference_number' => $c->reference_number,
                'contract_name' => $c->description,
                'shortname' => $c->shortname,
                'contract_period' => $c->contract_period,
                'contract_end' => $c->contract_end,
                'upload_date' => $c->created_at->format('d-M-Y'),
                'status' => $status,
                'gcsPdfUrl' => $this->resolveFileUrl($c->document, $disk),
            ];
        });

        // Replace collection with mapped contracts
        $dbContracts->setCollection($mappedContracts);

        // Add legacy contract manually to *first page only*
        if ($dbContracts->currentPage() === 1) {
            $legacyPath = "snapp_contracts/CONTRACT_{$short}.pdf";
            $legacyUrl = $this->resolveFileUrl($legacyPath, $disk);

            if ($legacyUrl) {
                $legacyContract = [
                    'reference_number' => null,
                    'contract_name' => "Legacy Contract for {$short}",
                    'shortname' => $short,
                    'contract_period' => null,
                    'contract_end' => null,
                    'upload_date' => Carbon::now()->format('d-M-Y'),
                    'status' => 'Available',
                    'gcsPdfUrl' => $legacyUrl,
                ];

                // Prepend legacy to collection
                $dbContracts->setCollection(collect([$legacyContract])->merge($dbContracts->getCollection()));
            }
        }

        return $dbContracts;
    }

    protected function resolveFileUrl(string $path, string $disk): ?string
    {
        if ($disk === 'gcs') {
            $url = $this->gcsService->generateSignedUrl($path);

            return $url ?: null;
        }

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }

        return null;
    }
}