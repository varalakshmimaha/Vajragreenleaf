<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::where('is_active', true)->orderBy('order')->get();
        return view('frontend.certifications.index', compact('certifications'));
    }
}
