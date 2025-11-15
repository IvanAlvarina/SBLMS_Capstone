<?php

namespace App\Http\Controllers\Ebooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ebook;

class EbooksController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::where('is_active', true)->paginate(10);

        return view('Ebooks.EbooksView', compact('ebooks'));
    }

    public function list()
    {
        return view('Ebooks.EbooksListView');
    }

    public function getEbookData(Request $request)
    {
        $query = Ebook::query();
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

        $ebooks = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        $data = $ebooks->map(function ($ebook) {
            return [
                'name' => e($ebook->name),
                'url'  => e($ebook->url),
                'is_active' => $ebook->is_active
                    ? '<span class="badge bg-label-success">Active</span>'
                    : '<span class="badge bg-label-danger">Inactive</span>',
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('ebooks.edit', $ebook->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <form action="' . route('ebooks.destroy', $ebook->id) . '" method="POST" class="delete-form">
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

    public function addEbook()
    {
        $ebook = new Ebook();

        return view('Ebooks.EbooksCreateView', compact('ebook'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        // Automatically set active
        $validated['is_active'] = 1;

        Ebook::create($validated);

        return redirect()
            ->route('ebooks.list')
            ->with('success', 'E-Book created successfully!');
    }

    public function edit($id)
    {
        $ebook = Ebook::findOrFail($id);
        return view('Ebooks.EbooksCreateView', compact('ebook'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        $ebook = Ebook::findOrFail($id);
        $validated['is_active'] = $ebook->is_active; // keep existing status

        $ebook->update($validated);

        return redirect()
            ->route('ebooks.list')
            ->with('success', 'E-Book updated successfully!');
    }

    public function destroy($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->delete();

        return redirect()
            ->route('ebooks.list')
            ->with('success', 'E-Book deleted successfully!');
    }
}
