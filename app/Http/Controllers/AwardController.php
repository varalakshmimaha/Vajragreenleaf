<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::where('is_active', true)->orderBy('order')->get();
        return view('frontend.awards.index', compact('awards'));
    }
}
