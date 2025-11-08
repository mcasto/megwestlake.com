<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function show()
    {
        return Cache::rememberForever('megwestlake-home', function () {
            $config = json_decode(Storage::disk('local')
                ->get('home/config.json'), true);

            return $config;
        });
    }

    public function update(Request $request)
    {
        Storage::cleanupOldFiles();

        $validator = Validator::make($request->all(), [
            'caption' => 'required|string',
            'changeImage' => 'required|boolean',
            'image' => 'required|string',
            'tempFile' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid request'];
        }

        extract($validator->valid());

        $config = [
            'caption' => $caption,
            'image' => $image
        ];

        if ($changeImage && $tempFile) {
            // delete current image file
            $oldPath = str_replace("/storage", "", $image);
            Storage::disk('public')
                ->delete($oldPath);

            // move new file to images
            $newPath = '/images/' . basename($tempFile);
            Storage::disk('public')
                ->move($tempFile, $newPath);

            $config['image'] = "/storage{$newPath}";
        }

        Storage::disk('local')
            ->put('/home/config.json', json_encode($config));

        Cache::forget('megwestlake-home');

        return ['updateHome' => $config];
    }
}
