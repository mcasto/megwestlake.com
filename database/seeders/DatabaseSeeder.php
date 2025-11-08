<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Margaret Westlake',
            'email' => config('app.admin.email'),
            'password' => Hash::make(config('app.admin.password'))
        ]);

        $calendar = json_decode(file_get_contents(__DIR__ . '/seed-data/calendar.json'));

        foreach ($calendar as $entry) {
            Calendar::create([
                'date' => $entry->date,
                'distance' => $entry->distance,
                'name' => $entry->name,
                'results' => $entry->results
            ]);
        }

        $news = json_decode(file_get_contents(__DIR__ . '/seed-data/news.json'), true);

        News::create($news);
    }
}
