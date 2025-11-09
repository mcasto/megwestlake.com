<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('megwestlake-calendar', function () {
            return [
                'image' => Storage::siteImage('admin-calendar')->image,
                'entries' => Calendar::orderBy('date', 'desc')
                    ->get()
            ];
        });
    }
}
