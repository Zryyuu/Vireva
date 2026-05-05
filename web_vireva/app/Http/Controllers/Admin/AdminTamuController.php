<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class AdminTamuController extends Controller
{
    /**
     * Display a listing of the guests (Super Admin Only).
     */
    public function index()
    {
        $tamu = Tamu::with('user')
            ->orderBy('nama_tamu', 'asc')
            ->paginate(15);

        return view('admin.tamu.index', compact('tamu'));
    }
}
