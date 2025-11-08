<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Admin Dashboard Images
     */
    public function dashboard()
    {
        $homeImage = json_decode(Storage::disk('local')
            ->get('/home/config.json'))->image;

        $home = [['link' => 'admin-home', 'image' => $homeImage, 'label' => 'Home']];

        $config = json_decode(Storage::disk('local')
            ->get('/app/config.json'));

        return array_merge($home, $config->dashboardImages);
    }

    /**
     * Handle an uploaded image for home page
     */
    public function handleUploadedImage(Request $request)
    {
        if (!$request->hasFile('uploadedImage')) {
            return ['status' => 'error', 'message' => 'No image attached'];
        }

        $tempFile = $request->uploadedImage->store('upload-temp', 'public');

        return ['status' => 'success', 'tempFile' => $tempFile];
    }
}
