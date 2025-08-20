<?php

namespace App\Livewire;

use App\Models\LogScan;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LogScanTable extends DataTableComponent
{
    protected $model = LogScan::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setColumnSelectDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable(),

            Column::make("Nome", "name")
                ->sortable()
                ->searchable(),

            Column::make("Criado em", "created_at")
                ->sortable(),
        ];
    }
}
