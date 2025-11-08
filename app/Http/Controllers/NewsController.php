<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index()
    {

        return Cache::rememberForever('megwestlake-news',  function () {
            return News::orderBy('date', 'desc')
                ->get();
        });
    }
}
