<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LogScan extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'path'
    ];

    public static function generateResume(self $log = null): array
    {
        $log = $log?->details ?: new LogScanDetail();

        return [
            'total_logs' => $log->count() ?? 0,
            'malicious' => $log->where('classification', 1)->count() ?? 0,
            'moderate' => $log->where('classification', 2)->count() ?? 0,
            'safe' => $log->where('classification', 3)->count() ?? 0,
        ];
    }

    public function details(): HasMany
    {
        return $this->hasMany(LogScanDetail::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
