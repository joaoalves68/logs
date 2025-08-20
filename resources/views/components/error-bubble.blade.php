@if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="fixed z-50 w-full px-4 py-3 bg-red-600 text-white rounded-lg shadow-xl animate-fade-in-up transition-all duration-500 ease-in-out transform" style="top: 15px; right: 15px; max-width: 350px;">

        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold text-lg">Erro de Validação</span>
        </div>

        <ul class="mt-2 list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
