<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products  = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // We'll create orders spread across the last 6 months
        // to populate the dashboard charts nicely
        $statuses  = ['completed', 'completed', 'completed', 'shipped', 'processing', 'paid', 'pending', 'cancelled'];
        $cities    = ['Depok', 'Jakarta Selatan', 'Bogor', 'Bekasi', 'Tangerang', 'Bandung'];
        $provinces = ['Jawa Barat', 'DKI Jakarta', 'Jawa Barat', 'Jawa Barat', 'Banten', 'Jawa Barat'];

        $orderTemplates = [
            // --- MONTH 1 (6 months ago) ---
            [
                'months_ago' => 6, 'day' => 5,
                'products_idx' => [0, 24], // Yamaha F310, Elixir Strings
                'quantities' => [1, 2],
                'status' => 'completed',
                'city_idx' => 0,
            ],
            [
                'months_ago' => 6, 'day' => 15,
                'products_idx' => [5], // Fender Stratocaster
                'quantities' => [1],
                'status' => 'completed',
                'city_idx' => 1,
            ],

            // --- MONTH 2 (5 months ago) ---
            [
                'months_ago' => 5, 'day' => 3,
                'products_idx' => [14], // Yamaha DTX402K
                'quantities' => [1],
                'status' => 'completed',
                'city_idx' => 2,
            ],
            [
                'months_ago' => 5, 'day' => 12,
                'products_idx' => [10, 26], // Squier Jazz Bass, Boss DS-1
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 3,
            ],
            [
                'months_ago' => 5, 'day' => 22,
                'products_idx' => [19, 28], // Casio CT-X700, Ernie Ball Strings
                'quantities' => [1, 3],
                'status' => 'completed',
                'city_idx' => 0,
            ],

            // --- MONTH 3 (4 months ago) ---
            [
                'months_ago' => 4, 'day' => 7,
                'products_idx' => [7], // Epiphone Les Paul
                'quantities' => [1],
                'status' => 'completed',
                'city_idx' => 4,
            ],
            [
                'months_ago' => 4, 'day' => 14,
                'products_idx' => [21, 23], // Roland FP-30X, Arturia MiniLab 3
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 5,
            ],
            [
                'months_ago' => 4, 'day' => 25,
                'products_idx' => [33, 35], // Behringer C-1, BM-800 Kit
                'quantities' => [1, 2],
                'status' => 'shipped',
                'city_idx' => 1,
            ],

            // --- MONTH 4 (3 months ago) ---
            [
                'months_ago' => 3, 'day' => 2,
                'products_idx' => [1, 30], // Yamaha FG800, Stand Gitar
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 0,
            ],
            [
                'months_ago' => 3, 'day' => 10,
                'products_idx' => [36], // Focusrite Scarlett 2i2
                'quantities' => [1],
                'status' => 'completed',
                'city_idx' => 2,
            ],
            [
                'months_ago' => 3, 'day' => 18,
                'products_idx' => [34, 35], // AT2020, Shure SM58
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 3,
            ],
            [
                'months_ago' => 3, 'day' => 28,
                'products_idx' => [15], // Tama Imperialstar
                'quantities' => [1],
                'status' => 'shipped',
                'city_idx' => 4,
            ],

            // --- MONTH 5 (2 months ago) ---
            [
                'months_ago' => 2, 'day' => 4,
                'products_idx' => [12], // Yamaha TRBX304
                'quantities' => [1],
                'status' => 'completed',
                'city_idx' => 5,
            ],
            [
                'months_ago' => 2, 'day' => 11,
                'products_idx' => [20, 28, 29], // Yamaha PSR-E373, Ernie Ball, Dunlop Picks
                'quantities' => [1, 2, 1],
                'status' => 'completed',
                'city_idx' => 0,
            ],
            [
                'months_ago' => 2, 'day' => 19,
                'products_idx' => [6], // Ibanez GRG170DX
                'quantities' => [1],
                'status' => 'processing',
                'city_idx' => 1,
            ],
            [
                'months_ago' => 2, 'day' => 26,
                'products_idx' => [31, 32], // Mandalika Biola, Cremona SV-75
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 2,
            ],

            // --- MONTH 6 (1 month ago) ---
            [
                'months_ago' => 1, 'day' => 3,
                'products_idx' => [38, 39], // Yamaha MG10XU, Dolphin Sound R4
                'quantities' => [1, 1],
                'status' => 'completed',
                'city_idx' => 3,
            ],
            [
                'months_ago' => 1, 'day' => 8,
                'products_idx' => [0, 26, 29], // Yamaha F310, Boss DS-1, Dunlop Picks
                'quantities' => [1, 1, 2],
                'status' => 'shipped',
                'city_idx' => 0,
            ],
            [
                'months_ago' => 1, 'day' => 15,
                'products_idx' => [3], // Taylor 114ce
                'quantities' => [1],
                'status' => 'paid',
                'city_idx' => 4,
            ],
            [
                'months_ago' => 1, 'day' => 22,
                'products_idx' => [9, 24], // Jackson JS22, Elixir Strings
                'quantities' => [1, 1],
                'status' => 'processing',
                'city_idx' => 5,
            ],

            // --- THIS MONTH ---
            [
                'months_ago' => 0, 'day' => 2,
                'products_idx' => [35], // Shure SM58
                'quantities' => [2],
                'status' => 'paid',
                'city_idx' => 1,
            ],
            [
                'months_ago' => 0, 'day' => 5,
                'products_idx' => [16, 37], // Roland TD-1DMK, Behringer UM2
                'quantities' => [1, 1],
                'status' => 'pending',
                'city_idx' => 0,
            ],
            [
                'months_ago' => 0, 'day' => 7,
                'products_idx' => [8], // Squier Telecaster
                'quantities' => [1],
                'status' => 'pending',
                'city_idx' => 2,
            ],
            [
                'months_ago' => 0, 'day' => 3,
                'products_idx' => [2, 4], // Cort AD810, Ibanez V50NJP
                'quantities' => [1, 1],
                'status' => 'cancelled',
                'city_idx' => 3,
            ],
        ];

        $orderCount = 0;
        foreach ($orderTemplates as $template) {
            $orderCount++;
            $customer = $customers->random();
            $cityIdx  = $template['city_idx'];

            $date = Carbon::now()->subMonths($template['months_ago'])->startOfMonth()->addDays($template['day'] - 1);
            // Make sure date is not in the future
            if ($date->gt(now())) {
                $date = now()->subDays(rand(1, 5));
            }

            // Calculate subtotal
            $subtotal = 0;
            $orderDetailData = [];
            foreach ($template['products_idx'] as $i => $prodIdx) {
                if (!isset($products[$prodIdx])) continue;
                $product = $products[$prodIdx];
                $qty     = $template['quantities'][$i];
                $price   = $product->price;
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;

                $orderDetailData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'subtotal'     => $itemSubtotal,
                ];
            }

            $shippingCost = collect([15000, 20000, 25000, 30000, 0])->random();
            $totalPrice   = $subtotal + $shippingCost;

            $order = Order::create([
                'order_code'       => 'ORD-' . $date->format('Ymd') . '-' . strtoupper(substr(md5($orderCount . time()), 0, 6)),
                'user_id'          => $customer->id,
                'shipping_address' => 'Jl. Contoh Alamat No. ' . rand(1, 200) . ', ' . $cities[$cityIdx],
                'phone'            => '08' . rand(1000000000, 9999999999),
                'city'             => $cities[$cityIdx],
                'province'         => $provinces[$cityIdx],
                'postal_code'      => (string) rand(10000, 99999),
                'payment_method'   => 'transfer_bank',
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total_price'      => $totalPrice,
                'status'           => $template['status'],
                'notes'            => null,
                'created_at'       => $date,
                'updated_at'       => $date,
            ]);

            // Create order details
            foreach ($orderDetailData as $detail) {
                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $detail['product_id'],
                    'product_name' => $detail['product_name'],
                    'price'        => $detail['price'],
                    'quantity'     => $detail['quantity'],
                    'subtotal'     => $detail['subtotal'],
                    'created_at'   => $date,
                    'updated_at'   => $date,
                ]);
            }

            // Create payment for non-pending, non-cancelled orders
            if (!in_array($template['status'], ['pending', 'cancelled'])) {
                $bankNames = ['BCA', 'Mandiri'];
                Payment::create([
                    'order_id'       => $order->id,
                    'proof_image'    => null,
                    'status'         => in_array($template['status'], ['completed', 'shipped', 'processing']) ? 'verified' : 'pending',
                    'bank_name'      => $bankNames[array_rand($bankNames)],
                    'account_number' => '123456' . rand(1000, 9999),
                    'account_name'   => $customer->name,
                    'notes'          => 'Transfer via ' . $bankNames[array_rand($bankNames)],
                    'verified_at'    => in_array($template['status'], ['completed', 'shipped', 'processing']) ? $date->copy()->addHours(rand(2, 24)) : null,
                    'created_at'     => $date,
                    'updated_at'     => $date,
                ]);
            }
        }
    }
}
