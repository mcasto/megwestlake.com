<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    public function show()
    {
        return Cache::rememberForever('megwestlake-about', function () {
            return [
                'image' => Storage::siteImage('admin-about')->image,
                'contents' => Storage::disk('local')
                    ->get('about-me/contents.html')
            ];
        });
    }

    public function update(Request $request)
    {
        Storage::cleanupOldFiles();

        $validator = Validator::make($request->all(), [
            'contents' => 'required|string',
            'changeImage' => 'required|boolean',
            'image' => 'required|string',
            'tempFile' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid request'];
        }

        extract($validator->valid());

        if ($changeImage && $tempFile) {
            // delete current image file
            $oldPath = str_replace("/storage", "", $image);
            Storage::disk('public')
                ->delete($oldPath);

            // move new file to images
            $newPath = '/images/' . basename($tempFile);
            Storage::disk('public')
                ->move($tempFile, $newPath);

            Storage::setImage('admin-about', "/storage/{$newPath}");
        }

        Storage::disk('local')
            ->put('/about-me/contents.html', $contents);

        Cache::forget('megwestlake-about');

        return ['status' => 'success', 'message' => 'About Me Updated'];
    }
}
