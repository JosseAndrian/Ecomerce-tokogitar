<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        if ($categories->isEmpty()) {
            return;
        }

        $products = [
            // ============================
            // 1. GITAR AKUSTIK (cat 1)
            // ============================
            [
                'category' => 'Gitar Akustik',
                'name' => 'Yamaha F310 Acoustic Guitar',
                'description' => 'Gitar akustik Yamaha F310 memberikan kualitas, desain, dan suara yang sangat baik. Cocok untuk pemula hingga menengah.',
                'price' => 1500000,
                'stock' => 15,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Natural', 'price' => 1500000],
                    ['name' => 'Tobacco Brown Sunburst', 'price' => 1550000],
                ],
            ],
            [
                'category' => 'Gitar Akustik',
                'name' => 'Yamaha FG800 Solid Top Acoustic',
                'description' => 'Gitar akustik dengan top solid spruce untuk suara yang lebih kaya dan resonan. Cocok untuk pemain serius.',
                'price' => 2800000,
                'stock' => 8,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Natural', 'price' => 2800000],
                    ['name' => 'Sunset Blue', 'price' => 2900000],
                ],
            ],
            [
                'category' => 'Gitar Akustik',
                'name' => 'Cort AD810 Acoustic Guitar',
                'description' => 'Gitar akustik Cort AD810 dengan body dreadnought, suara penuh dan punchy. Pilihan terbaik di kelasnya.',
                'price' => 1200000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'category' => 'Gitar Akustik',
                'name' => 'Taylor 114ce Grand Auditorium',
                'description' => 'Gitar elektrik akustik Taylor 114ce dengan pickup ES2, body grand auditorium untuk keseimbangan suara sempurna.',
                'price' => 15000000,
                'stock' => 3,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Natural Sitka Spruce', 'price' => 15000000],
                    ['name' => 'Sunburst', 'price' => 15500000],
                ],
            ],
            [
                'category' => 'Gitar Akustik',
                'name' => 'Ibanez V50NJP Acoustic Jam Pack',
                'description' => 'Paket gitar akustik lengkap dengan tas, strap, tuner, dan pick. Sempurna untuk pemula.',
                'price' => 1350000,
                'stock' => 12,
                'is_featured' => false,
            ],

            // ============================
            // 2. GITAR ELEKTRIK (cat 2)
            // ============================
            [
                'category' => 'Gitar Elektrik',
                'name' => 'Fender Stratocaster Player Series',
                'description' => 'Gitar elektrik legendaris dengan tone yang jernih dan playability yang nyaman. Made in Mexico.',
                'price' => 12500000,
                'stock' => 5,
                'is_featured' => true,
                'variations' => [
                    ['name' => '3-Color Sunburst', 'price' => 12500000],
                    ['name' => 'Polar White', 'price' => 12500000],
                    ['name' => 'Tidepool Blue', 'price' => 12800000],
                ],
            ],
            [
                'category' => 'Gitar Elektrik',
                'name' => 'Ibanez GRG170DX Electric Guitar',
                'description' => 'Gitar elektrik Ibanez GRG170DX dengan HSH pickup configuration. Neck tipis dan cepat untuk shredding.',
                'price' => 3200000,
                'stock' => 10,
                'is_featured' => false,
                'variations' => [
                    ['name' => 'Black Night', 'price' => 3200000],
                    ['name' => 'Transparent Red Burst', 'price' => 3300000],
                ],
            ],
            [
                'category' => 'Gitar Elektrik',
                'name' => 'Epiphone Les Paul Standard 50s',
                'description' => 'Replika Les Paul dengan pickup ProBucker, body mahogany dan maple top. Tone klasik rock dan blues.',
                'price' => 6500000,
                'stock' => 7,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Heritage Cherry Sunburst', 'price' => 6500000],
                    ['name' => 'Metallic Gold', 'price' => 6800000],
                ],
            ],
            [
                'category' => 'Gitar Elektrik',
                'name' => 'Squier Affinity Telecaster',
                'description' => 'Gitar elektrik Telecaster affordable dari Squier. Tone bright dan twangy untuk country, pop, dan rock.',
                'price' => 3800000,
                'stock' => 9,
                'is_featured' => false,
            ],
            [
                'category' => 'Gitar Elektrik',
                'name' => 'Jackson JS22 Dinky',
                'description' => 'Gitar elektrik metal dengan body compound radius, pickup high-output, dan tremolo. Untuk genre metal dan hard rock.',
                'price' => 3500000,
                'stock' => 6,
                'is_featured' => false,
                'variations' => [
                    ['name' => 'Satin Black', 'price' => 3500000],
                    ['name' => 'Snow White', 'price' => 3500000],
                ],
            ],

            // ============================
            // 3. BASS (cat 3)
            // ============================
            [
                'category' => 'Bass',
                'name' => 'Squier Affinity Jazz Bass',
                'description' => 'Bass gitar Jazz Bass affordable dari Squier. Tone versatile untuk berbagai genre musik.',
                'price' => 3500000,
                'stock' => 8,
                'is_featured' => true,
                'variations' => [
                    ['name' => '3-Color Sunburst', 'price' => 3500000],
                    ['name' => 'Olympic White', 'price' => 3500000],
                ],
            ],
            [
                'category' => 'Bass',
                'name' => 'Ibanez GSR200 4-String Bass',
                'description' => 'Bass Ibanez GSR200 dengan neck tipis dan ringan, pickup Dynamix untuk tone yang powerful.',
                'price' => 3200000,
                'stock' => 6,
                'is_featured' => false,
            ],
            [
                'category' => 'Bass',
                'name' => 'Yamaha TRBX304 Active Bass',
                'description' => 'Bass aktif Yamaha TRBX304 dengan active/passive switching. Body solid mahogany untuk tone yang kaya.',
                'price' => 4500000,
                'stock' => 4,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Mist Green', 'price' => 4500000],
                    ['name' => 'Factory Blue', 'price' => 4500000],
                    ['name' => 'Candy Apple Red', 'price' => 4700000],
                ],
            ],
            [
                'category' => 'Bass',
                'name' => 'Cort Action Bass Plus',
                'description' => 'Bass elektrik Cort Action Bass Plus dengan Powersound pickup. Affordable dan berkualitas tinggi.',
                'price' => 2800000,
                'stock' => 10,
                'is_featured' => false,
            ],

            // ============================
            // 4. DRUM & PERKUSI (cat 4)
            // ============================
            [
                'category' => 'Drum & Perkusi',
                'name' => 'Yamaha DTX402K Electronic Drum Set',
                'description' => 'Drum elektrik Yamaha DTX402K, sangat cocok untuk latihan di rumah tanpa mengganggu tetangga. 10 built-in training.',
                'price' => 6500000,
                'stock' => 3,
                'is_featured' => true,
            ],
            [
                'category' => 'Drum & Perkusi',
                'name' => 'Tama Imperialstar 5-Piece Drum Kit',
                'description' => 'Drum akustik Tama Imperialstar dengan 5 piece shell pack. Suara punchy dan projection yang baik.',
                'price' => 8500000,
                'stock' => 2,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Hairline Blue', 'price' => 8500000],
                    ['name' => 'Candy Apple Mist', 'price' => 8800000],
                ],
            ],
            [
                'category' => 'Drum & Perkusi',
                'name' => 'Roland TD-1DMK V-Drums',
                'description' => 'Drum elektrik Roland TD-1DMK dengan mesh head pad. Feel natural seperti drum akustik.',
                'price' => 7800000,
                'stock' => 4,
                'is_featured' => false,
            ],
            [
                'category' => 'Drum & Perkusi',
                'name' => 'Pearl Cajon Primero Box PBC-507',
                'description' => 'Cajon Pearl dengan body birch/meranti. Suara snare yang tajam dan bass yang deep.',
                'price' => 1200000,
                'stock' => 15,
                'is_featured' => false,
            ],
            [
                'category' => 'Drum & Perkusi',
                'name' => 'Zildjian ZBT Hi-Hat Cymbal 14"',
                'description' => 'Hi-hat cymbal Zildjian ZBT 14 inch. Bright, cutting, dan responsive.',
                'price' => 1800000,
                'stock' => 8,
                'is_featured' => false,
            ],

            // ============================
            // 5. KEYBOARD & PIANO (cat 5)
            // ============================
            [
                'category' => 'Keyboard & Piano',
                'name' => 'Casio CT-X700 Portable Keyboard',
                'description' => 'Keyboard portable Casio CT-X700 dengan AiX Sound Source, 61 keys, 600 tone, dan 195 rhythm.',
                'price' => 2800000,
                'stock' => 10,
                'is_featured' => false,
            ],
            [
                'category' => 'Keyboard & Piano',
                'name' => 'Yamaha PSR-E373 Keyboard',
                'description' => 'Keyboard Yamaha PSR-E373 dengan 61 keys touch-sensitive, USB to Host, dan 622 suara.',
                'price' => 3200000,
                'stock' => 8,
                'is_featured' => true,
            ],
            [
                'category' => 'Keyboard & Piano',
                'name' => 'Roland FP-30X Digital Piano',
                'description' => 'Digital piano Roland FP-30X dengan 88 keys PHA-4 Standard keyboard. Suara piano SuperNATURAL yang realistis.',
                'price' => 10500000,
                'stock' => 3,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'Black', 'price' => 10500000],
                    ['name' => 'White', 'price' => 10500000],
                ],
            ],
            [
                'category' => 'Keyboard & Piano',
                'name' => 'Korg B2 Digital Piano 88-Key',
                'description' => 'Digital piano Korg B2 dengan 88 keys, 12 suara berkualitas tinggi, dan koneksi USB.',
                'price' => 6800000,
                'stock' => 5,
                'is_featured' => false,
            ],
            [
                'category' => 'Keyboard & Piano',
                'name' => 'Arturia MiniLab 3 MIDI Controller',
                'description' => 'MIDI controller compact Arturia MiniLab 3 dengan 25 keys, 8 pads, 8 knobs, dan software bundle.',
                'price' => 1800000,
                'stock' => 12,
                'is_featured' => false,
            ],

            // ============================
            // 6. AKSESORIS (cat 6)
            // ============================
            [
                'category' => 'Aksesoris',
                'name' => 'Elixir Nanoweb 80/20 Acoustic Strings',
                'description' => 'Senar gitar akustik Elixir Nanoweb dengan coating anti karat. Tahan lama dan tone yang bright.',
                'price' => 180000,
                'stock' => 50,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Boss DS-1 Distortion Pedal',
                'description' => 'Efek pedal distorsi legendaris Boss DS-1. Dari crunch ringan hingga heavy distortion.',
                'price' => 750000,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Valeton Dapper Dark Mini Multi-Effect',
                'description' => 'Multi efek gitar Valeton Dapper Dark Mini dengan 4 efek module (tuner, drive, delay, mod).',
                'price' => 850000,
                'stock' => 10,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Gruv Gear FretWraps String Muter',
                'description' => 'String muter GruvGear FretWraps untuk mengurangi noise saat tapping dan recording.',
                'price' => 150000,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Ernie Ball Regular Slinky Electric Strings',
                'description' => 'Senar gitar elektrik Ernie Ball Regular Slinky (.010-.046). Favorit gitaris dunia.',
                'price' => 95000,
                'stock' => 60,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Dunlop Tortex Standard Pick Set (12pcs)',
                'description' => 'Set 12 pick gitar Dunlop Tortex Standard. Grip yang nyaman dan durable.',
                'price' => 60000,
                'stock' => 80,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'KLIQ UberTuner Clip-On Tuner',
                'description' => 'Tuner clip-on dengan display besar dan akurasi tinggi. Cocok untuk gitar, bass, ukulele, dan biola.',
                'price' => 120000,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Stand Gitar Lipat Universal',
                'description' => 'Stand gitar lipat portable yang kokoh dan ringan. Cocok untuk gitar akustik, elektrik, dan bass.',
                'price' => 85000,
                'stock' => 40,
                'is_featured' => false,
            ],

            // ============================
            // 7. BIOLA & VIOLIN (cat 7)
            // ============================
            [
                'category' => 'Biola & Violin',
                'name' => 'Mandalika Biola 4/4 Full Size',
                'description' => 'Biola Mandalika 4/4 full size dengan body spruce, fingerboard ebony. Cocok untuk pemula dan pelajar.',
                'price' => 850000,
                'stock' => 12,
                'is_featured' => false,
            ],
            [
                'category' => 'Biola & Violin',
                'name' => 'Cremona SV-75 Premier Student Violin',
                'description' => 'Biola pelajar Cremona SV-75 dengan setup yang baik, body spruce dan maple. Tone yang hangat.',
                'price' => 2200000,
                'stock' => 6,
                'is_featured' => true,
                'variations' => [
                    ['name' => '4/4 Full Size', 'price' => 2200000],
                    ['name' => '3/4 Size', 'price' => 2000000],
                    ['name' => '1/2 Size', 'price' => 1800000],
                ],
            ],
            [
                'category' => 'Biola & Violin',
                'name' => 'Yamaha V5SA Acoustic Violin',
                'description' => 'Biola akustik Yamaha V5SA buatan tangan dengan top solid spruce dan back/side maple.',
                'price' => 4500000,
                'stock' => 4,
                'is_featured' => true,
            ],
            [
                'category' => 'Biola & Violin',
                'name' => 'DAddario Prelude Violin Strings Set',
                'description' => 'Set senar biola DAddario Prelude untuk tone yang warm dan stabil. Ideal untuk pelajar.',
                'price' => 180000,
                'stock' => 20,
                'is_featured' => false,
            ],

            // ============================
            // 8. MICROPHONE (cat 8)
            // ============================
            [
                'category' => 'Microphone',
                'name' => 'Behringer C-1 Condenser Microphone',
                'description' => 'Microphone condenser studio Behringer C-1 dengan large-diaphragm cardioid. Cocok untuk vokal dan recording.',
                'price' => 850000,
                'stock' => 10,
                'is_featured' => false,
            ],
            [
                'category' => 'Microphone',
                'name' => 'Audio-Technica AT2020 Condenser Mic',
                'description' => 'Mic condenser Audio-Technica AT2020 side-address cardioid. Standar industri untuk home studio.',
                'price' => 1500000,
                'stock' => 8,
                'is_featured' => true,
            ],
            [
                'category' => 'Microphone',
                'name' => 'Shure SM58 Dynamic Microphone',
                'description' => 'Mic dynamic legendaris Shure SM58. Standar industri untuk live vocal performance.',
                'price' => 1600000,
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'category' => 'Microphone',
                'name' => 'Rode NT-USB Mini USB Microphone',
                'description' => 'Microphone USB compact Rode NT-USB Mini untuk podcasting, streaming, dan video call.',
                'price' => 1400000,
                'stock' => 7,
                'is_featured' => false,
            ],
            [
                'category' => 'Microphone',
                'name' => 'BM-800 Condenser Mic Starter Kit',
                'description' => 'Paket mic condenser BM-800 lengkap dengan phantom power, pop filter, arm stand, dan kabel XLR.',
                'price' => 350000,
                'stock' => 25,
                'is_featured' => false,
            ],

            // ============================
            // 9. SOUNDCARD & MIXER (cat 9)
            // ============================
            [
                'category' => 'Soundcard & Mixer',
                'name' => 'Focusrite Scarlett 2i2 3rd Gen',
                'description' => 'Audio interface USB-C Focusrite Scarlett 2i2 dengan 2 input/2 output. Preamp berkualitas studio.',
                'price' => 2800000,
                'stock' => 6,
                'is_featured' => true,
            ],
            [
                'category' => 'Soundcard & Mixer',
                'name' => 'Behringer U-Phoria UM2 Audio Interface',
                'description' => 'Audio interface affordable Behringer UM2 dengan 2x2 USB, phantom power, dan XENYX preamp.',
                'price' => 550000,
                'stock' => 15,
                'is_featured' => false,
            ],
            [
                'category' => 'Soundcard & Mixer',
                'name' => 'Yamaha MG10XU Mixing Console',
                'description' => 'Mixer Yamaha MG10XU 10-channel dengan SPX digital effects dan USB audio interface built-in.',
                'price' => 3500000,
                'stock' => 4,
                'is_featured' => true,
                'variations' => [
                    ['name' => 'MG10XU (10 Channel)', 'price' => 3500000],
                    ['name' => 'MG12XU (12 Channel)', 'price' => 4200000],
                ],
            ],
            [
                'category' => 'Soundcard & Mixer',
                'name' => 'Dolphin Sound R4 USB Audio Interface',
                'description' => 'Audio interface lokal Dolphin Sound R4 dengan 4 input, fitur loopback untuk streaming/podcast.',
                'price' => 450000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'category' => 'Soundcard & Mixer',
                'name' => 'PreSonus AudioBox USB 96',
                'description' => 'Audio interface PreSonus AudioBox USB 96 dengan 2 preamp, 24-bit/96kHz, dan bundled StudioOne Artist.',
                'price' => 1800000,
                'stock' => 5,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $prod) {
            $catName = $prod['category'];
            $category = $categories->get($catName);
            if (!$category) continue;

            Product::create([
                'category_id' => $category->id,
                'name'        => $prod['name'],
                'slug'        => Str::slug($prod['name']) . '-' . substr(md5($prod['name']), 0, 6),
                'description' => $prod['description'],
                'variations'  => $prod['variations'] ?? null,
                'price'       => $prod['price'],
                'stock'       => $prod['stock'],
                'is_featured' => $prod['is_featured'],
                'is_active'   => true,
            ]);
        }
    }
}
