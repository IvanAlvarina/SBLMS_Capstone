<?php

namespace App\Http\Controllers\Ejournals;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal;

class EjournalsController extends Controller
{
    public function index()
    {
        $journals = Journal::where('is_active', true)->paginate(15);

        return view('Ejournals.EjournalsView', compact('journals'));
    }

    public function list()
    {
        return view('Ejournals.EjournalsListView');
    }

    public function getJournalData(Request $request)
    {
        $query = Journal::query();
        $totalData = $query->count();

        if ($search = $request->input('search.value')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['name', 'url', 'is_active'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        $journals = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        $data = $journals->map(function ($journal) {
            return [
                'name' => e($journal->name),
                'url'  => e($journal->url),
                'is_active' => $journal->is_active 
                    ? '<span class="badge bg-label-success">Active</span>' 
                    : '<span class="badge bg-label-danger">Inactive</span>',
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('ejournals.edit', $journal->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <form action="' . route('ejournals.destroy', $journal->id) . '" method="POST" class="delete-form">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="ti ti-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>'

            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function addEjournal()
    {
        $journal = new Journal();

        return view('Ejournals.EjournalsCreateView', compact('journal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        // Automatically set active
        $validated['is_active'] = 1;

        Journal::create($validated);

        return redirect()
            ->route('ejournals.list')
            ->with('success', 'E-Journal created successfully!');
    }

    public function edit($id)
    {
        $journal = Journal::findOrFail($id);
        return view('Ejournals.EjournalsCreateView', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        $journal = Journal::findOrFail($id);
        $validated['is_active'] = $journal->is_active; // keep existing status or set logic

        $journal->update($validated);

        return redirect()
            ->route('ejournals.list')
            ->with('success', 'E-Journal updated successfully!');
    }

    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->delete();

        return redirect()
            ->route('ejournals.list')
            ->with('success', 'E-Journal deleted successfully!');
    }





}
