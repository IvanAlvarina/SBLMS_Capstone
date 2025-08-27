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
}
