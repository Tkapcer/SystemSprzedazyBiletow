@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-white">
            <h1 class="text-2xl font-bold text-gray-800">System raportowania</h1>
            <p class="text-gray-600">Statystyki i raporty dla Twoich wydarzeń</p>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- View mode toggle switch -->
            <div class="mb-6">
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <span class="mr-3 text-sm font-medium text-gray-700">Całościowe</span>
                        <div class="relative">
                            <input type="checkbox" id="view_mode" name="view_mode" value="partial" class="sr-only">
                            <div class="block bg-gray-300 w-14 h-7 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Cząstkowe</span>
                    </label>
                </div>
            </div>

            <!-- Overall view (całościowe) - default view -->
            <div id="overall-view">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total revenue card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="revenue" onclick="toggleInsightCard('revenue')">
                        <div class="text-sm text-gray-600 mb-1">Łączny dochód</div>
                        <div class="text-2xl font-bold">87,450 zł</div>
                    </div>
                    
                    <!-- Occupancy rate card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="occupancy" onclick="toggleInsightCard('occupancy')">
                        <div class="text-sm text-gray-600 mb-1">Obłożenie</div>
                        <div class="text-2xl font-bold">72%</div>
                    </div>
                    
                    <!-- Tickets sold card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="tickets" onclick="toggleInsightCard('tickets')">
                        <div class="text-sm text-gray-600 mb-1">Sprzedanych biletów</div>
                        <div class="text-2xl font-bold">1,245</div>
                    </div>
                    
                    <!-- Active reservations card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="reservations" onclick="toggleInsightCard('reservations')">
                        <div class="text-sm text-gray-600 mb-1">Aktywnych rezerwacji</div>
                        <div class="text-2xl font-bold">342</div>
                    </div>
                    
                    <!-- Events card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="events" onclick="toggleInsightCard('events')">
                        <div class="text-sm text-gray-600 mb-1">Wydarzenia</div>
                        <div class="text-2xl font-bold">18</div>
                    </div>
                    
                    <!-- Venues card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="venues" onclick="toggleInsightCard('venues')">
                        <div class="text-sm text-gray-600 mb-1">Sale</div>
                        <div class="text-2xl font-bold">4</div>
                    </div>
                    
                    <!-- Categories card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card" 
                         data-card-id="categories" onclick="toggleInsightCard('categories')">
                        <div class="text-sm text-gray-600 mb-1">Kategorie</div>
                        <div class="text-2xl font-bold">5</div>
                    </div>
                </div>
                
                <!-- Detailed insight cards - hidden by default -->
                <div id="insight-details" class="hidden mb-6">
                    <!-- Revenue details -->
                    <div id="revenue-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły dochodu</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('revenue'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tabela dochodu -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Dochód (zł)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Koncert symfoniczny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    25,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Wystawa malarstwa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    8,750
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Spektakl teatralny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    12,600
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Gala operowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    22,400
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Widowisko taneczne
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    12,000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Festiwal filmowy
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    6,100
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Occupancy details -->
                    <div id="occupancy-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły obłożenia</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('occupancy'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tabela obłożenia -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Obłożenie (%)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Koncert symfoniczny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    85
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Wystawa malarstwa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    62
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Spektakl teatralny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    78
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Gala operowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    70
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Widowisko taneczne
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    56
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Festiwal filmowy
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    48
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Recital fortepianowy
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    0
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Konferencja naukowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    0
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="occupancyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tickets details - zmodyfikowana wersja -->
                    <div id="tickets-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły sprzedanych biletów</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('tickets'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tabela sprzedanych biletów -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba sprzedanych
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Koncert symfoniczny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    320
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Wystawa malarstwa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    175
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Spektakl teatralny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    210
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Gala operowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    280
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Widowisko taneczne
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    150
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Festiwal filmowy
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    110
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="ticketsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reservations details -->
                    <div id="reservations-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły aktywnych rezerwacji</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('reservations'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tabela rezerwacji -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nazwa wydarzenia
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba rezerwacji
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Gala operowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    120
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Widowisko taneczne
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    85
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Festiwal filmowy
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    137
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="reservationsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Events details - zmodyfikowana wersja -->
                    <div id="events-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły wydarzeń</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('events'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Lista wydarzeń -->
                            <div class="w-full md:w-1/2">
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Odbyte:</h4>
                                    <ul class="list-disc pl-5 text-gray-600">
                                        <li>Koncert symfoniczny</li>
                                        <li>Wystawa malarstwa</li>
                                        <li>Spektakl teatralny</li>
                                    </ul>
                                </div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Nadchodzące:</h4>
                                    <ul class="list-disc pl-5 text-gray-600">
                                        <li>Gala operowa</li>
                                        <li>Widowisko taneczne</li>
                                        <li>Festiwal filmowy</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Odwołane:</h4>
                                    <ul class="list-disc pl-5 text-gray-600">
                                        <li>Recital fortepianowy</li>
                                        <li>Konferencja naukowa</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="eventsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Venues details -->
                    <div id="venues-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły sal</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('venues'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Lista sal -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nazwa sali
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba wydarzeń
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Sala koncertowa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    5
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Teatr główny
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    6
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Galeria sztuki
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    4
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Sala konferencyjna
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    3
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="venuesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories details -->
                    <div id="categories-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły kategorii</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('categories'); return false;">eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Lista kategorii -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nazwa kategorii
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba wydarzeń
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Koncerty
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    4
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Teatr
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    3
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Wystawa
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    2
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Film
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    6
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Konferencje
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    3
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Wykres -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="categoriesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partial view (cząstkowe) - hidden by default -->
            <div id="partial-view" class="hidden">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                    <h3 class="font-semibold mb-3">Wykres danych</h3>
                    <div class="h-96 w-full bg-gray-100 flex items-center justify-center">
                        <p class="text-gray-500">Tutaj będzie wyświetlany wykres danych</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for reporting system -->
<script>
    // Initialize view mode handling
    document.addEventListener('DOMContentLoaded', function() {
        const viewModeSwitch = document.getElementById('view_mode');
        const overallView = document.getElementById('overall-view');
        const partialView = document.getElementById('partial-view');
        
        // Toggle switch handler
        viewModeSwitch.addEventListener('change', function() {
            if (this.checked) {
                // Partial mode
                overallView.classList.add('hidden');
                partialView.classList.remove('hidden');
            } else {
                // Overall mode
                overallView.classList.remove('hidden');
                partialView.classList.add('hidden');
            }
        });
        
        // Styling for toggle switch
        viewModeSwitch.parentElement.querySelector('.dot').style.transition = 'all 0.3s ease-in-out';
        viewModeSwitch.addEventListener('change', function() {
            const dot = this.parentElement.querySelector('.dot');
            if (this.checked) {
                dot.style.transform = 'translateX(100%)';
                this.parentElement.querySelector('.block').classList.remove('bg-gray-300');
                this.parentElement.querySelector('.block').classList.add('bg-blue-500');
            } else {
                dot.style.transform = 'translateX(0)';
                this.parentElement.querySelector('.block').classList.remove('bg-blue-500');
                this.parentElement.querySelector('.block').classList.add('bg-gray-300');
            }
        });
    });
    
    // Toggle insight card details
    function toggleInsightCard(cardId) {
        // Get the details container and specific card details
        const detailsContainer = document.getElementById('insight-details');
        const cardDetails = document.getElementById(cardId + '-details');
        const allDetails = document.querySelectorAll('.insight-detail');
        const allCards = document.querySelectorAll('.insight-card');
        
        // Get the clicked card
        const clickedCard = document.querySelector(`[data-card-id="${cardId}"]`);
        
        // Reset all cards styling
        allCards.forEach(card => {
            card.classList.remove('border-blue-500');
            card.classList.add('border-gray-200');
        });
        
        // If the card was already active and visible, hide everything
        if (!cardDetails.classList.contains('hidden')) {
            detailsContainer.classList.add('hidden');
            allDetails.forEach(detail => detail.classList.add('hidden'));
            return;
        }
        
        // Otherwise, show this card's details and hide others
        detailsContainer.classList.remove('hidden');
        allDetails.forEach(detail => detail.classList.add('hidden'));
        cardDetails.classList.remove('hidden');
        
        // Highlight the active card
        clickedCard.classList.remove('border-gray-200');
        clickedCard.classList.add('border-blue-500');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
<script>
    // Inicjalizacja wykresów po załadowaniu strony
    document.addEventListener('DOMContentLoaded', function() {
        // Dane dla wykresu wydarzeń
        const eventsData = {
            labels: ['Odbyte', 'Nadchodzące', 'Odwołane'],
            datasets: [{
                label: 'Liczba wydarzeń',
                data: [3, 3, 2],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Dane dla wykresu sprzedanych biletów
        const ticketsData = {
            labels: ['Koncert symfoniczny', 'Wystawa malarstwa', 'Spektakl teatralny', 'Gala operowa', 'Widowisko taneczne', 'Festiwal filmowy'],
            datasets: [{
                label: 'Liczba sprzedanych biletów',
                data: [320, 175, 210, 280, 150, 110],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Inicjalizacja wykresu wydarzeń
        const eventsCtx = document.getElementById('eventsChart').getContext('2d');
        const eventsChart = new Chart(eventsCtx, {
            type: 'bar',
            data: eventsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Inicjalizacja wykresu sprzedanych biletów
        const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
        const ticketsChart = new Chart(ticketsCtx, {
            type: 'bar',
            data: ticketsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });

    // Inicjalizacja pozostałych wykresów
document.addEventListener('DOMContentLoaded', function() {
    // Dane dla wykresu rezerwacji
    const reservationsData = {
        labels: ['Gala operowa', 'Widowisko taneczne', 'Festiwal filmowy'],
        datasets: [{
            label: 'Liczba rezerwacji',
            data: [120, 85, 137],
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    };
    
    // Dane dla wykresu dochodu
    const revenueData = {
        labels: ['Koncert symfoniczny', 'Wystawa malarstwa', 'Spektakl teatralny', 'Gala operowa', 'Widowisko taneczne', 'Festiwal filmowy'],
        datasets: [{
            label: 'Dochód (zł)',
            data: [25600, 8750, 12600, 22400, 12000, 6100],
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    };
    
    // Dane dla wykresu obłożenia
    const occupancyData = {
        labels: ['Koncert symfoniczny', 'Wystawa malarstwa', 'Spektakl teatralny', 'Gala operowa', 'Widowisko taneczne', 'Festiwal filmowy', 'Recital fortepianowy', 'Konferencja naukowa'],
        datasets: [{
            label: 'Obłożenie (%)',
            data: [85, 62, 78, 70, 56, 48, 0, 0],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };
    
    // Dane dla wykresu sal
    const venuesData = {
        labels: ['Sala koncertowa', 'Teatr główny', 'Galeria sztuki', 'Sala konferencyjna'],
        datasets: [{
            label: 'Liczba wydarzeń',
            data: [5, 6, 4, 3],
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    };
    
    // Dane dla wykresu kategorii
    const categoriesData = {
        labels: ['Koncerty', 'Teatr', 'Wystawa', 'Film', 'Konferencje'],
        datasets: [{
            label: 'Liczba wydarzeń',
            data: [4, 3, 2, 6, 3],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    
    // Funkcja do inicjalizacji wykresu
    function initializeChart(ctx, data) {
        if (ctx) {
            return new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        return null;
    }
    
    // Inicjalizacja wykresów
    const reservationsCtx = document.getElementById('reservationsChart');
    const revenueCtx = document.getElementById('revenueChart');
    const occupancyCtx = document.getElementById('occupancyChart');
    const venuesCtx = document.getElementById('venuesChart');
    const categoriesCtx = document.getElementById('categoriesChart');
    
    if (reservationsCtx) initializeChart(reservationsCtx.getContext('2d'), reservationsData);
    if (revenueCtx) initializeChart(revenueCtx.getContext('2d'), revenueData);
    if (occupancyCtx) initializeChart(occupancyCtx.getContext('2d'), occupancyData);
    if (venuesCtx) initializeChart(venuesCtx.getContext('2d'), venuesData);
    if (categoriesCtx) initializeChart(categoriesCtx.getContext('2d'), categoriesData);
});

// Rozszerz funkcję exportCSV o obsługę pozostałych typów
function exportCSV(type) {
    let csvContent = '';
    let filename = '';
    
    // Przygotuj odpowiednie dane w zależności od typu
    if (type === 'events') {
        csvContent = 'Status,Nazwa wydarzenia\n';
        csvContent += 'Odbyte,Koncert symfoniczny\n';
        csvContent += 'Odbyte,Wystawa malarstwa\n';
        csvContent += 'Odbyte,Spektakl teatralny\n';
        csvContent += 'Nadchodzące,Gala operowa\n';
        csvContent += 'Nadchodzące,Widowisko taneczne\n';
        csvContent += 'Nadchodzące,Festiwal filmowy\n';
        csvContent += 'Odwołane,Recital fortepianowy\n';
        csvContent += 'Odwołane,Konferencja naukowa\n';
        filename = 'wydarzenia_raport.csv';
    } else if (type === 'tickets') {
        csvContent = 'Wydarzenie,Liczba sprzedanych biletów\n';
        csvContent += 'Koncert symfoniczny,320\n';
        csvContent += 'Wystawa malarstwa,175\n';
        csvContent += 'Spektakl teatralny,210\n';
        csvContent += 'Gala operowa,280\n';
        csvContent += 'Widowisko taneczne,150\n';
        csvContent += 'Festiwal filmowy,110\n';
        filename = 'sprzedane_bilety_raport.csv';
    } else if (type === 'reservations') {
        csvContent = 'Wydarzenie,Liczba rezerwacji\n';
        csvContent += 'Gala operowa,120\n';
        csvContent += 'Widowisko taneczne,85\n';
        csvContent += 'Festiwal filmowy,137\n';
        filename = 'rezerwacje_raport.csv';
    } else if (type === 'revenue') {
        csvContent = 'Wydarzenie,Dochód (zł)\n';
        csvContent += 'Koncert symfoniczny,25600\n';
        csvContent += 'Wystawa malarstwa,8750\n';
        csvContent += 'Spektakl teatralny,12600\n';
        csvContent += 'Gala operowa,22400\n';
        csvContent += 'Widowisko taneczne,12000\n';
        csvContent += 'Festiwal filmowy,6100\n';
        filename = 'dochod_raport.csv';
    } else if (type === 'occupancy') {
        csvContent = 'Wydarzenie,Obłożenie (%)\n';
        csvContent += 'Koncert symfoniczny,85\n';
        csvContent += 'Wystawa malarstwa,62\n';
        csvContent += 'Spektakl teatralny,78\n';
        csvContent += 'Gala operowa,70\n';
        csvContent += 'Widowisko taneczne,56\n';
        csvContent += 'Festiwal filmowy,48\n';
        csvContent += 'Recital fortepianowy,0\n';
        csvContent += 'Konferencja naukowa,0\n';
        filename = 'oblozenie_raport.csv';
    } else if (type === 'venues') {
        csvContent = 'Nazwa sali,Liczba wydarzeń\n';
        csvContent += 'Sala koncertowa,5\n';
        csvContent += 'Teatr główny,6\n';
        csvContent += 'Galeria sztuki,4\n';
        csvContent += 'Sala konferencyjna,3\n';
        filename = 'sale_raport.csv';
    } else if (type === 'categories') {
        csvContent = 'Nazwa kategorii,Liczba wydarzeń\n';
        csvContent += 'Koncerty,4\n';
        csvContent += 'Teatr,3\n';
        csvContent += 'Wystawa,2\n';
        csvContent += 'Film,6\n';
        csvContent += 'Konferencje,3\n';
        filename = 'kategorie_raport.csv';
    }
    
    // W prawdziwej implementacji tutaj byłoby pobieranie pliku
    console.log(`Eksport CSV dla ${type}:`, csvContent);
    
    // Dla prototypu wyświetl tylko komunikat
    alert(`Eksport CSV dla ${filename} (funkcjonalność w prototypie)`);
}
</script>
@endsection