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

    public function mount(int|null|string $logId): void
    {
        $this->logId = $logId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setColumnSelectDisabled();
        $this->setAdditionalSelects(['log_scan_details.*']);
    }

    public function builder(): Builder
    {
        $query = $this->model::query();

        if ($this->logId) {
            $query->where('log_scan_id', $this->logId);
        }

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hideIf(true),
            Column::make("Log scan id", "log_scan_id")
                ->hideIf(true),

            Column::make('Detalhes', 'id')
                ->collapseAlways()
                ->format(function ($value, $row) {
                    $classification = (int) $row->classification;
                    $classificationLabel = match ($classification) {
                        1 => 'Malicioso',
                        2 => 'Moderado',
                        3 => 'Seguro',
                        default => 'Não processado',
                    };

                    return "
                        <div>
                            <div><strong>Data:</strong> {$row->timestamp}</div>
                            <div><strong>IP:</strong> {$row->client_ip}</div>
                            <div><strong>Domínio:</strong> {$row->domain}</div>
                            <div><strong>Classificação:</strong> {$classificationLabel}</div>
                            <div><strong>Motivo:</strong> {$row->analysis_reason}</div>
                        </div>
                    ";
                })
                ->html(),

            Column::make("Data / Hora", "timestamp")
                ->sortable(),
            Column::make("Classificação", "classification")
                ->sortable()
                ->format(function($value, $row) {
                    switch ($value) {
                        case 1:
                            $color = 'bg-red-500';
                            $label = 'Malicioso';
                            break;
                        case 2:
                            $color = 'bg-orange-600';
                            $label = 'Moderado';
                            break;
                        case 3:
                            $color = 'bg-green-600';
                            $label = 'Seguro';
                            break;
                        default:
                            $color = 'bg-gray-500';
                            $label = 'Não processado';
                    }

                    return "<span class='inline-flex items-center px-2 py-1 rounded-full text-white text-xs font-semibold {$color}'>{$label}</span>";
                })
                ->html(),
            Column::make("Cliente IP", "client_ip")
                ->sortable(),
        ];
    }
}

