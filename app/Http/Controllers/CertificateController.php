<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Auth::user()->member->certificates()->with(['period', 'unit'])->latest()->get();

        return view('certificates.index', compact('certificates'));
    }
}