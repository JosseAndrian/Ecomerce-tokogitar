<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Gitar Akustik',      'icon' => 'bi-music-note-beamed', 'description' => 'Koleksi gitar akustik dari berbagai merek ternama dunia.'],
            ['name' => 'Gitar Elektrik',      'icon' => 'bi-lightning-charge',  'description' => 'Gitar elektrik untuk genre rock, jazz, blues, dan metal.'],
            ['name' => 'Bass',                'icon' => 'bi-guitar',            'description' => 'Bass gitar elektrik dan akustik untuk semua level pemain.'],
            ['name' => 'Drum & Perkusi',      'icon' => 'bi-disc',             'description' => 'Drum akustik, elektrik, dan alat perkusi lainnya.'],
            ['name' => 'Keyboard & Piano',    'icon' => 'bi-music-note-list',  'description' => 'Keyboard portable, digital piano, dan synthesizer.'],
            ['name' => 'Aksesoris',           'icon' => 'bi-plug',             'description' => 'Senar, pick, strap, capo, efek pedal, dan aksesoris lainnya.'],
            ['name' => 'Biola & Violin',      'icon' => 'bi-vinyl',            'description' => 'Biola, viola, cello, dan perlengkapan alat gesek.'],
            ['name' => 'Microphone',          'icon' => 'bi-mic',              'description' => 'Microphone condenser, dynamic, dan wireless untuk recording & live.'],
            ['name' => 'Soundcard & Mixer',   'icon' => 'bi-sliders',          'description' => 'Audio interface, soundcard USB, dan mixer untuk home studio.'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'        => $category['name'],
                'slug'        => Str::slug($category['name']),
                'icon'        => $category['icon'],
                'description' => $category['description'],
            ]);
        }
    }
}
