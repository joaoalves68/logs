<style>
    /* Reset and base styles for the table */
    .minimalist-table-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #333;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .minimalist-table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Table header styles */
    .minimalist-table thead {
        background-color: #f7f7f7;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .minimalist-table th {
        padding: 16px 24px;
        text-align: left;
        border-bottom: 2px solid #e0e0e0;
    }

    /* Table body and row styles */
    .minimalist-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .minimalist-table tbody tr:last-child {
        border-bottom: none;
    }

    .minimalist-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .minimalist-table td {
        padding: 16px 24px;
        vertical-align: top;
    }

    /* Link and special content styles */
    .minimalist-link {
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .minimalist-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Message for no records found */
    .minimalist-no-records {
        text-align: center;
        padding: 24px;
        color: #888;
        font-style: italic;
    }
</style>

@props(['logScans', 'searchTerm', 'sortBy', 'sortOrder'])

<h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Registros de Scans</h3>

<form action="{{ route('dashboard') }}" method="GET"
    class="mb-6 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 gap-4">
    <div class="w-full sm:w-auto flex-grow">
        <label for="search" class="sr-only">Pesquisar</label>
        <input type="text" name="search" id="search" placeholder="Pesquisar por nome, ID, ou caminho..."
            value="{{ $searchTerm }}"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
    </div>

    <div class="w-full sm:w-auto">
        <label for="sort_by" class="sr-only">Ordenar por</label>
        <select name="sort_by" id="sort_by"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
            <option value="created_at" @selected($sortBy=='created_at' )>Data de Criação</option>
            <option value="name" @selected($sortBy=='name' )>Nome</option>
            <option value="id" @selected($sortBy=='id' )>ID</option>
        </select>
    </div>

    <div class="w-full sm:w-auto">
        <label for="sort_order" class="sr-only">Ordem</label>
        <select name="sort_order" id="sort_order"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
            <option value="desc" @selected($sortOrder=='desc' )>Decrescente</option>
            <option value="asc" @selected($sortOrder=='asc' )>Crescente</option>
        </select>
    </div>

    <button type="submit"
        class="w-full inline-flex justify-center py-3 px-6
        border border-transparent shadow-lg text-lg font-medium
        rounded-md text-white bg-gradient-to-r from-indigo-600 to-purple-600
        hover:from-indigo-700 hover:to-purple-700
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Filtrar
    </button>
</form>

<div class="minimalist-table-container">
    <table class="minimalist-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Caminho do Arquivo</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logScans as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->name }}</td>
                <td>
                    @if ($log->path)
                    <a href="{{ asset('storage/' . $log->path) }}" target="_blank" class="minimalist-link">
                        Visualizar Arquivo
                    </a>
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="minimalist-no-records">Nenhum registro encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $logScans->links() }}
</div>
