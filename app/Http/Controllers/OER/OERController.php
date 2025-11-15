<?php

namespace App\Http\Controllers\OER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Oer;

class OERController extends Controller
{
    public function index()
    {
        $oers = Oer::where('is_active', true)->paginate(15);

        return view('OER.OERView', compact('oers'));
    }

    public function list()
    {
        return view('OER.OERListView');
    }

    public function getOerData(Request $request)
    {
        $query = Oer::query();
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

        $oers = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        $data = $oers->map(function ($oer) {
            return [
                'name' => e($oer->name),
                'url'  => e($oer->url),
                'is_active' => $oer->is_active
                    ? '<span class="badge bg-label-success">Active</span>'
                    : '<span class="badge bg-label-danger">Inactive</span>',
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('oer.edit', $oer->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <form action="' . route('oer.destroy', $oer->id) . '" method="POST" class="delete-form">
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

    public function addOer()
    {
        $oer = new Oer();

        return view('OER.OERCreateView', compact('oer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        // Automatically set active
        $validated['is_active'] = 1;

        Oer::create($validated);

        return redirect()
            ->route('oer.list')
            ->with('success', 'OER created successfully!');
    }

    public function edit($id)
    {
        $oer = Oer::findOrFail($id);
        return view('OER.OERCreateView', compact('oer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        $oer = Oer::findOrFail($id);
        $validated['is_active'] = $oer->is_active; // keep existing status

        $oer->update($validated);

        return redirect()
            ->route('oer.list')
            ->with('success', 'OER updated successfully!');
    }

    public function destroy($id)
    {
        $oer = Oer::findOrFail($id);
        $oer->delete();

        return redirect()
            ->route('oer.list')
            ->with('success', 'OER deleted successfully!');
    }
}
