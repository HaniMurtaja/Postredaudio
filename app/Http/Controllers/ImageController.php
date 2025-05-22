<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function images(Request $request)
    {
        return Storage::disk('public')->exists('base64.json') ? json_decode(Storage::disk('public')->get('base64.json'), true) : [];
    }
}
