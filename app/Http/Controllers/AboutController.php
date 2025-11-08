<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function show()
    {
        return Cache::rememberForever('megwestlake-about', function () {
            return [
                'image' => config('app.images.about'),
                'contents' => Storage::disk('local')
                    ->get('about-me/contents.html')
            ];
        });
    }
}
