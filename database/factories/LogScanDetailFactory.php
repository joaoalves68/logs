<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LogScanDetail;
use App\Models\LogScan;

class LogScanDetailFactory extends Factory
{
    protected $model = LogScanDetail::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'log_scan_id' => LogScan::factory(),
            'timestamp' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'domain' => $this->faker->domainName(),
            'client_ip' => $this->faker->ipv4(),
            'classification' => rand(1,3),
            'analysis_reason' => $this->faker->sentence(),
        ];
    }
}
