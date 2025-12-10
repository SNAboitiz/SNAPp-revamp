<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvisoryRequest;
use App\Http\Requests\UpdateAdvisoryRequest;
use App\Models\Advisory;
use Illuminate\Http\Request;

class AdvisoryController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->cant('can view advisories')) {
            abort(403, 'Unauthorized action.');
        }

        $filter = $request->date;
        $sort = $request->sort;

        $advisoriesQuery = Advisory::where('is_archive', false);

        // Apply filter
        switch ($filter) {
            case 'last_7_days':
                $advisoriesQuery->where('created_at', '>=', now()->subDays(7));
                break;
            case 'last_30_days':
                $advisoriesQuery->where('created_at', '>=', now()->subDays(30));
                break;
            case 'this_month':
                $advisoriesQuery->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'last_month':
                $advisoriesQuery->whereYear('created_at', now()->subMonth()->year)
                    ->whereMonth('created_at', now()->subMonth()->month);
                break;
        }

        // Apply sorting
        switch ($sort) {
            case 'date_asc':
                $advisoriesQuery->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $advisoriesQuery->orderBy('created_at', 'desc');
                break;
            case 'headline_asc':
                $advisoriesQuery->orderBy('headline', 'asc');
                break;
            case 'headline_desc':
                $advisoriesQuery->orderBy('headline', 'desc');
                break;
            default:
                $advisoriesQuery->orderBy('created_at', 'desc');
                break;
        }

        $latestAdvisory = $advisoriesQuery->first();

        $moreAdvisories = Advisory::where('is_archive', false)
            ->where('id', '!=', optional($latestAdvisory)->id)
            ->when($filter === 'last_7_days', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($filter === 'last_30_days', fn ($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($sort === 'date_asc', fn ($q) => $q->orderBy('created_at', 'asc'))
            ->when($sort === 'date_desc', fn ($q) => $q->orderBy('created_at', 'desc'))
            ->when($sort === 'headline_asc', fn ($q) => $q->orderBy('headline', 'asc'))
            ->when($sort === 'headline_desc', fn ($q) => $q->orderBy('headline', 'desc'))
            ->when(! $sort, fn ($q) => $q->orderBy('created_at', 'desc'))
            ->take(3)
            ->get();

        return view('advisories', compact('latestAdvisory', 'moreAdvisories'));
    }

    public function loadMore(Request $request)
    {
        return Advisory::where('is_archive', false)
            ->where('id', '!=', Advisory::where('is_archive', false)->latest()->value('id'))
            ->skip($request->skip ?? 0)
            ->take(5)
            ->get();
    }

    public function adminList()
    {
        $advisories = Advisory::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.advisory-management.advisory-list', compact('advisories'));
    }

    public function store(StoreAdvisoryRequest $request)
    {
        $validatedRequest = $request->validated();

        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('snapp-advisory-attachments');
            $validatedRequest['attachment'] = $filePath;
        }

        $validatedRequest['created_by'] = auth()->id();

        Advisory::create($validatedRequest);

        return redirect()->back()->with('success', 'Advisory created successfully.');
    }

    public function update(UpdateAdvisoryRequest $request, Advisory $advisory)
    {
        $validated = $request->validated();

        $data = [
            'headline' => $validated['edit_headline'],
            'description' => $validated['edit_description'],
            'content' => $validated['edit_content'],
            'link' => $validated['edit_link'] ?? null,
            'is_archive' => $validated['is_archive'] ?? 0,
        ];

        if ($request->hasFile('edit_attachment')) {
            $data['attachment'] = $request
                ->file('edit_attachment')
                ->store('snapp-advisory-attachments');
        }

        $advisory->update($data);

        return redirect()
            ->route('advisories.list')
            ->with('success', 'Advisory updated successfully.');
    }
}
