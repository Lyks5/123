<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnvironmentalInitiative;

class SustainabilityController extends Controller
{
    /**
     * Display the sustainability page.
     */
    public function index()
    {
        $initiatives = EnvironmentalInitiative::latest()->take(3)->get();
        
        return view('pages.sustainability', [
            'initiatives' => $initiatives
        ]);
    }
}
