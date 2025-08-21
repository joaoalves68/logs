<?php

namespace App\Livewire;

use App\Models\LogScan;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Illuminate\Database\Eloquent\Builder;
class LogScanTable extends DataTableComponent
{
    protected $model = LogScan::class;

    // Propriedades para o modal
    public $selectedLog = null;
    public $showModal = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setColumnSelectDisabled();
    }


    public function builder(): Builder
    {
        return $this->model::query()->orderBy('created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->hideIf(true),

            Column::make("Nome", "name")
                ->sortable()
                ->searchable(),

            Column::make("Criado em", "created_at")
                ->sortable(),

            LinkColumn::make('Ações')
                ->title(fn($row) => 'Ver detalhes')
                ->location(fn($row) => route('log.show', $row->id)),
        ];
    }
}
