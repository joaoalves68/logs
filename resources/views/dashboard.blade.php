<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Registrar Novos Dados</h3>

                    <x-alert-bubble/>

                    <form action="{{ route('log.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome:</label>
                            <input value="{{ old('name') }}" type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                              focus:ring-indigo-500 focus:border-indigo-500
                                              sm:text-base p-3 placeholder-gray-400
                                              transition duration-150 ease-in-out">
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Arquivo
                                (.txt):</label>
                            <input type="file" id="file" name="file" accept=".txt" required class="mt-1 block w-full text-gray-900 border border-gray-300 rounded-md cursor-pointer
                                              bg-white focus:outline-none p-3
                                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                              file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100 transition duration-150 ease-in-out">
                            <p class="mt-2 text-xs text-gray-500">Apenas arquivos .txt são aceitos e o tamanho máximo é
                                de 2MB.</p>
                        </div>

                        <div>
                            <button type="submit" class="w-full inline-flex justify-center py-3 px-6
                                               border border-transparent shadow-lg text-lg font-medium
                                               rounded-md text-white bg-gradient-to-r from-indigo-600 to-purple-600
                                               hover:from-indigo-700 hover:to-purple-700
                                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enviar Dados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Últimos Logs registrados</h3>

                    <livewire:log-scan-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
