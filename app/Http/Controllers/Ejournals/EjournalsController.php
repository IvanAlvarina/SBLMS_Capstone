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

}
