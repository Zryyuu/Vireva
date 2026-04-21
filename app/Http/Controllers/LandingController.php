<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $villas = Villa::where('status_villa', 'tersedia')->latest()->get();
        return view('landing', compact('villas'));
    }
}
