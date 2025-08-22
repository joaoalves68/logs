<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogScan;
use App\Models\LogScanDetail;

class LogScanSeeder extends Seeder
{
    public function run()
    {
        LogScan::factory(10)->create(['user_id' => 1])->each(function ($log) {
            LogScanDetail::factory(rand(20,50))
                ->create(['log_scan_id' => $log->id]);
        });
    }
}
