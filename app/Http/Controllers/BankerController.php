<?php

namespace App\Http\Controllers;

use App\Models\Banker;
use Illuminate\Http\Request;

class BankerController extends Controller
{
    public function index()
    {
        $bankers = Banker::where('is_active', true)->orderBy('order')->get();
        return view('frontend.bankers.index', compact('bankers'));
    }
}
