<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CalendarController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('megwestlake-calendar', function () {
            return [
                'image' => config('app.images.calendar'),
                'entries' => Calendar::orderBy('date', 'desc')
                    ->get()
            ];
        });
    }
}
