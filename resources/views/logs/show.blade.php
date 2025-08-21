<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Logs
            </h2>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 ease-in-out">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
                Voltar
            </a>
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mt-4">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Resumo da Análise</h3>
                    <p class="text-gray-500">Uma visão geral das estatísticas dos seu logs.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md flex flex-col justify-center items-center transform transition duration-300 hover:scale-105 hover:shadow-xl">
                        <p class="text-sm uppercase tracking-wide font-semibold text-gray-500 mb-1">Total de Logs</p>
                        <p class="text-6xl font-extrabold text-gray-900 leading-none">{{ $resume['total_logs'] }}</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md flex flex-col items-center justify-center lg:col-span-2 transform transition duration-300 hover:scale-[1.02] hover:shadow-xl">
                        <p class="text-sm uppercase tracking-wide font-semibold text-gray-500 mb-4">Percentual por Categoria</p>
                        <div class="w-full h-64 flex justify-center items-center">
                            <canvas id="doughnut-chart"></canvas>
                        </div>
                        <ul class="flex flex-wrap justify-center space-x-4 mt-6 text-sm font-medium">
                            <li class="flex items-center">
                                <span class="w-2 h-2 rounded-full mr-1" style="background-color: #4ade80;"></span>Seguro
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 rounded-full mr-1" style="background-color: #f87171;"></span>Malicioso
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 rounded-full mr-1" style="background-color: #60a5fa;"></span>Neutro
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-10">
                    <p class="text-lg font-semibold text-gray-800 mb-4 text-center">Últimos 10 Domínios Maliciosos</p>
                    <div class="flex flex-wrap justify-center gap-3 text-sm text-gray-600">
                        @foreach ($resume['lastTenMalicious'] as $domain)
                            <span class="bg-gray-100 px-4 py-2 rounded-full font-medium transition duration-150 hover:bg-red-50 hover:text-red-600">{{ $domain }}</span>
                        @endforeach
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Detalhes do Scan</h3>
                <livewire:log-details-table :log-id="$log?->id" />
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const data = {
                labels: ['Seguro', 'Malicioso', 'Neutro'],
                datasets: [{
                    data: [{{ $resume['safe']['percentage'] }}, {{ $resume['malicious']['percentage'] }}, {{ $resume['moderate']['percentage'] }}],
                    backgroundColor: ['#4ade80', '#f87171', '#60a5fa'],
                    hoverBackgroundColor: ['#22c55e', '#ef4444', '#3b82f6'],
                    borderWidth: 0,
                }]
            };

            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw}%`;
                                }
                            }
                        }
                    },
                }
            };

            const myDoughnutChart = new Chart(
                document.getElementById('doughnut-chart'),
                config
            );
        });
    </script>
</x-app-layout>
