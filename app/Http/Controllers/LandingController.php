<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $kamar = Kamar::where('status_kamar', 'tersedia')->latest()->get();
        return view('landing', compact('kamar'));
    }
}
