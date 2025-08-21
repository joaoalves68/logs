<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\LogScanDetail;
use Illuminate\Database\Eloquent\Builder;

class LogDetailsTable extends DataTableComponent
{
    public $logId;

    protected $model = LogScanDetail::class;

    public function mount(int | null $logId): void
    {
        $this->logId = $logId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setColumnSelectDisabled();
    }

    public function builder(): Builder
    {
        if (is_null($this->logId)) {
            return LogScanDetail::query();
        }

        return LogScanDetail::query()
            ->where('log_scan_id', $this->logId)
            ->orderBy('timestamp', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),
            Column::make("Log scan id", "log_scan_id")
                ->hideIf(true),

            Column::make("Data / Hora", "timestamp")
                ->sortable(),
            Column::make("Domínio", "domain")
                ->sortable(),
            Column::make("Cliente IP", "client_ip")
                ->sortable(),
            Column::make("Classificação", "classification_label")
                ->sortable(),
        ];
    }
}

