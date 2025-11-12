<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('megwestlake-calendar', function () {
            return [
                'image' => Storage::siteImage('admin-calendar')->image,
                'entries' => [
                    'upcoming' => Calendar::where('date', '>', now())
                        ->orderBy('date', 'desc')
                        ->get(),
                    'past' => Calendar::where('date', '<=', now())
                        ->orderBy('date', 'desc')
                        ->get()
                ]
            ];
        });
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date' => 'required|date',
            'distance' => 'nullable|string',
            'results' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid submission'];
        }

        $rec = Calendar::create($validator->valid());

        Cache::forget('megwestlake-calendar');

        return ['status' => 'success'];
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date' => 'required|date',
            'distance' => 'nullable|string',
            'results' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid submission'];
        }

        $rec = Calendar::find($id);

        if (!$rec) {
            return ['status' => 'error', 'message' => 'No such record exists'];
        }

        $valid = $validator->valid();

        $rec->name = $valid['name'];
        $rec->date = $valid['date'];
        $rec->distance = $valid['distance'];
        $rec->results = $valid['results'];
        $rec->save();

        Cache::forget('megwestlake-calendar');

        return ['status' => 'success'];
    }

    public function destroy(int $id)
    {
        Calendar::find($id)->delete();

        Cache::forget('megwestlake-calendar');

        return ['status' => 'success'];
    }
}
