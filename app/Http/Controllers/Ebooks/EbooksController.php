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
}
