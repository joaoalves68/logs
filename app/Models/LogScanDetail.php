<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LogScanDetail extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'log_scan_id',
        'timestamp',
        'domain',
        'client_ip',
        'classification',
        'analysis_reason',
    ];

    protected $appends = ['classification_label'];

    public function getClassificationLabelAttribute(): ?string
    {
        return match ($this->classification) {
            1 => 'Malicioso',
            2 => 'Moderado',
            3 => 'Seguro',
            default => null,
        };
    }

    public static function generateResume(LogScan $log = null): array
    {
        $log = $log?->details ?: new self();

        return [
            'total_logs' => $log->count() ?? 0,
            'malicious' => $log->where('classification', 1)->count() ?? 0,
            'moderate' => $log->where('classification', 2)->count() ?? 0,
            'safe' => $log->where('classification', 3)->count() ?? 0,
        ];
    }

    public function logScan(): BelongsTo
    {
        return $this->belongsTo(LogScan::class);
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
