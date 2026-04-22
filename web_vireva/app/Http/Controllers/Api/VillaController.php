<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Http\Resources\VillaResource;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::where('status_villa', 'tersedia')->get();
        return VillaResource::collection($villas);
    }

    public function show(Villa $villa)
    {
        return new VillaResource($villa);
    }
}
