<?php

namespace App\Http\Controllers\NewsAndMagazine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsMagazine;

class NewsAndMagazineController extends Controller
{
    public function index()
    {
        $newsMagazines = NewsMagazine::where('is_active', true)->paginate(15);

        return view('NewsAndMagazine.NewsAndMagazineView', compact('newsMagazines'));
    }

    public function list()
    {
        return view('NewsAndMagazine.NewsAndMagazineListView');
    }

    public function getNewsData(Request $request)
    {
        $query = NewsMagazine::query();
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

        $newsMagazines = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        $data = $newsMagazines->map(function ($news) {
            return [
                'name' => e($news->name),
                'url'  => e($news->url),
                'is_active' => $news->is_active
                    ? '<span class="badge bg-label-success">Active</span>'
                    : '<span class="badge bg-label-danger">Inactive</span>',
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('news&magazine.edit', $news->id) . '">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <form action="' . route('news&magazine.destroy', $news->id) . '" method="POST" class="delete-form">
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

    public function addNews()
    {
        $newsMagazine = new NewsMagazine();

        return view('NewsAndMagazine.NewsAndMagazineCreateView', compact('newsMagazine'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        // Automatically set active
        $validated['is_active'] = 1;

        NewsMagazine::create($validated);

        return redirect()
            ->route('news&magazine.list')
            ->with('success', 'News & Magazine created successfully!');
    }

    public function edit($id)
    {
        $newsMagazine = NewsMagazine::findOrFail($id);
        return view('NewsAndMagazine.NewsAndMagazineCreateView', compact('newsMagazine'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url'  => ['required', 'url', 'max:255'],
        ]);

        $newsMagazine = NewsMagazine::findOrFail($id);
        $validated['is_active'] = $newsMagazine->is_active; // keep existing status

        $newsMagazine->update($validated);

        return redirect()
            ->route('news&magazine.list')
            ->with('success', 'News & Magazine updated successfully!');
    }

    public function destroy($id)
    {
        $newsMagazine = NewsMagazine::findOrFail($id);
        $newsMagazine->delete();

        return redirect()
            ->route('news&magazine.list')
            ->with('success', 'News & Magazine deleted successfully!');
    }
}
