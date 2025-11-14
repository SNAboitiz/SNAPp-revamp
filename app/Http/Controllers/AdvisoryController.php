<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvisoryRequest;
use App\Http\Requests\UpdateAdvisoryRequest;
use App\Models\Advisory;
use Illuminate\Http\Request;

class AdvisoryController extends Controller
{
    public function index()
    {
        $latestAdvisory = Advisory::where('is_archive', false)
            ->orderBy('created_at', 'desc')
            ->first();

        $moreAdvisories = Advisory::where('is_archive', false)
            ->where('id', '!=', optional($latestAdvisory)->id)
            ->orderBy('created_at', 'desc')
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
