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
        'extra',
        'classification',
        'analysis_reason',
    ];

    protected $appends = ['classification_label'];

    protected $casts = [
        'extra' => 'json'
    ];

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
        if ($log) {
            $details = $log->details;
            $totalLogs = $details->count();
            $classifiedLogs = $details->whereNotNull('classification');

            $maliciousCount = $classifiedLogs->where('classification', 1)->count();
            $moderateCount = $classifiedLogs->where('classification', 2)->count();
            $safeCount = $classifiedLogs->where('classification', 3)->count();

            $lastTenMalicious = $details->where('classification', 1)
                ->sortByDesc('timestamp')
                ->take(10)
                ->pluck('domain')
                ->toArray();
        } else {
            $totalLogs = LogScanDetail::count();
            $classifiedLogsQuery = LogScanDetail::whereNotNull('classification');

            $maliciousCount = (clone $classifiedLogsQuery)->where('classification', 1)->count();
            $moderateCount = (clone $classifiedLogsQuery)->where('classification', 2)->count();
            $safeCount = (clone $classifiedLogsQuery)->where('classification', 3)->count();

            $lastTenMalicious = LogScanDetail::where('classification', 1)
                ->orderByDesc('timestamp')
                ->limit(10)
                ->pluck('domain')
                ->toArray();

            $totalClassified = $classifiedLogsQuery->count() ?: 1;
        }

        $totalClassified = $log ? $classifiedLogs->count() ?: 1 : $totalClassified;

        return [
            'total_logs' => $totalLogs,
            'malicious' => [
                'count' => $maliciousCount,
                'percentage' => round(($maliciousCount / $totalClassified) * 100, 2),
            ],
            'moderate' => [
                'count' => $moderateCount,
                'percentage' => round(($moderateCount / $totalClassified) * 100, 2),
            ],
            'safe' => [
                'count' => $safeCount,
                'percentage' => round(($safeCount / $totalClassified) * 100, 2),
            ],
            'lastTenMalicious' => $lastTenMalicious,
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
