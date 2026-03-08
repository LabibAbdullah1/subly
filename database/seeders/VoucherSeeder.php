<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::updateOrCreate(
            ['code' => 'SUBLYDISKON'],
            [
                'type' => 'percent',
                'reward_amount' => 10, // 10% discount
                'usage_limit' => 100,
                'expires_at' => Carbon::now()->addMonths(1),
            ]
        );

        Voucher::updateOrCreate(
            ['code' => 'PROMO50K'],
            [
                'type' => 'fixed',
                'reward_amount' => 50000, // Rp 50.000 discount
                'usage_limit' => 50,
                'expires_at' => Carbon::now()->addMonths(1),
            ]
        );
    }
}
