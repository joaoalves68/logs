<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LogScan;

class LogScanFactory extends Factory
{
    protected $model = LogScan::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->name(),
            'path' => 'uploads/' . $this->faker->uuid() . '.txt',
        ];
    }
}
