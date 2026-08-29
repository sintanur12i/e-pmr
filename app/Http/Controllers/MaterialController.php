<?php

namespace App\Http\Controllers;

use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('uploader')->latest()->paginate(10);

        return view('materials.index', compact('materials'));
    }
}