<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('megwestlake-news',  function () {
            return News::orderBy('date', 'desc')
                ->get();
        });
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'date' => 'required|date',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid Request'];
        }

        $valid = $validator->valid();

        $rec = News::create($validator->safe()->only('subject', 'date'));
        $rec->content = $valid['content'];
        $rec->save();

        Cache::forget('megwestlake-news');

        return ['status' => 'success', 'message' => 'News Item Created'];
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'date' => 'required|date',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid Request'];
        }

        $valid = $validator->valid();

        $rec = News::find($id);
        if (!$rec) {
            return ['status' => 'error', 'message' => 'News Item Not Found'];
        }

        $rec->subject = $valid['subject'];
        $rec->date = $valid['date'];
        $rec->content = $valid['content'];
        $rec->save();

        Cache::forget('megwestlake-news');

        return ['status' => 'success', 'message' => 'News Item Updated'];
    }

    public function destroy(int $id)
    {
        $rec = News::find($id);
        if (!$rec) {
            return ['status' => 'error', 'message' => 'News Item Not Found'];
        }

        $rec->delete();

        Cache::forget('megwestlake-news');

        return ['status' => 'success', 'message' => 'News Item Deleted'];
    }
}
