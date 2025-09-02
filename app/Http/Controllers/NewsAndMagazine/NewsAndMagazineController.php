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
}
