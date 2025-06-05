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
                        <span class="mr-3 text-sm font-medium text-gray-700">Ogólne</span>
                        <div class="relative">
                            <input type="checkbox" id="view_mode" name="view_mode" value="partial" class="sr-only">
                            <div class="block bg-gray-300 w-14 h-7 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Szczegółowe</span>
                    </label>
                </div>
            </div>

            <!-- Overall view (ogólne) - default view -->
            <div id="overall-view">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total revenue card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="revenue" onclick="toggleInsightCard('revenue')">
                        <div class="text-sm text-gray-600 mb-1">Łączny dochód</div>
                        <div id="revenueTotal" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Occupancy rate card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="occupancy" onclick="toggleInsightCard('occupancy')">
                        <div class="text-sm text-gray-600 mb-1">Obłożenie</div>
                        <div id="occupancyPrecent" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Tickets sold card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="tickets" onclick="toggleInsightCard('tickets')">
                        <div class="text-sm text-gray-600 mb-1">Sprzedanych biletów</div>
                        <div id="ticketsCount" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Active reservations card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="reservations" onclick="toggleInsightCard('reservations')">
                        <div class="text-sm text-gray-600 mb-1">Aktywnych rezerwacji</div>
                        <div id="reservationsCount" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Events card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                        data-card-id="events" onclick="toggleInsightCard('events')">
                        <div class="text-sm text-gray-600 mb-1">Wydarzenia</div>
                        <div id="eventsCount" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Venues card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="venues" onclick="toggleInsightCard('venues')">
                        <div class="text-sm text-gray-600 mb-1">Sale</div>
                        <div id="venuesCount" class="text-2xl font-bold"></div>
                    </div>

                    <!-- Categories card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="categories" onclick="toggleInsightCard('categories')">
                        <div class="text-sm text-gray-600 mb-1">Gatunki</div>
                        <div id="categoriesCount" class="text-2xl font-bold"></div>
                    </div>
                </div>


                <!-- Detailed insight cards - hidden by default -->
                <div id="insight-details" class="hidden mb-6">
                    
                    <!-- Revenue details -->
                    <div id="revenue-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <!-- Header with export option -->
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły dochodów</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('revenue'); return false;">Eksport CSV</a>
                        </div>

                        <!-- Scrollable content layout -->
                        <div class="flex flex-col md:flex-row gap-4 max-h-[450px] overflow-auto">

                            <!-- Revenue table -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Dochód (zł)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="revenueTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Revenue chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 overflow-auto">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Occupancy details -->
                    <div id="occupancy-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <!-- Header with export option -->
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły obłożenia</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('occupancy'); return false;">Eksport CSV</a>
                        </div>

                        <!-- Scrollable content layout -->
                        <div class="flex flex-col md:flex-row gap-4 max-h-[450px] overflow-auto">

                            <!-- Occupancy table -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Obłożenie (%)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="occupancyTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Occupancy chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 overflow-auto">
                                    <canvas id="occupancyChart"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Tickets details -->
                    <div id="tickets-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <!-- Header with export option -->
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły sprzedanych biletów</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('tickets'); return false;">Eksport CSV</a>
                        </div>

                        <!-- Scrollable content layout -->
                        <div class="flex flex-col md:flex-row gap-4 max-h-[450px] overflow-auto">

                            <!-- Tickets sales table -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Sprzedane bilety
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="ticketsTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tickets chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 overflow-auto">
                                    <canvas id="ticketsChart"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Reservations details -->
                    <div id="reservations-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <!-- Header with export option -->
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły aktywnych rezerwacji</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('reservations'); return false;">Eksport CSV</a>
                        </div>

                        <!-- Scrollable content layout -->
                        <div class="flex flex-col md:flex-row gap-4 max-h-[450px] overflow-auto">

                            <!-- Reservations table -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Wydarzenie
                                                </th>
                                                <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Aktywne rezerwacje
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="reservationsTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Reservations chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 overflow-auto">
                                    <canvas id="reservationsChart"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Events Details -->
                    <div id="events-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły wydarzeń</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('events'); return false;">Eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Event List -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nadchodzące
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Zakończone
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Anulowane
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="eventsTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Events chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="eventsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Venues Details -->
                    <div id="venues-details" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 insight-detail hidden">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">Szczegóły sal</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('venues'); return false;">Eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Venues List -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Sala
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba wydarzeń
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="venuesTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Venues chart -->
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
                            <h3 class="font-semibold">Szczegóły gatunków</h3>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('categories'); return false;">Eksport CSV</a>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Categories List -->
                            <div class="w-full md:w-1/2">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Gatunek
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Liczba wydarzeń
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="categoriesTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Categories chart -->
                            <div class="w-full md:w-1/2">
                                <div class="h-64 w-full bg-white">
                                    <canvas id="categoriesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Detailed view (szczegółowe) - hidden by default -->
            <div id="partial-view" class="hidden">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                    <h3 class="font-semibold mb-3">Filtrowanie</h3>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    
                        <!-- Events -->
                        <div>
                            <label for="eventSelect" class="block text-sm font-medium text-gray-700">Wydarzenia</label>
                            <select id="eventSelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Wszystkie</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <!-- <div>
                            <label for="categorySelect" class="block text-sm font-medium text-gray-700">Gatunek</label>
                            <select id="categorySelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Wszystkie</option>
                            </select>
                        </div> -->

                        <!-- Venues -->
                        <div>
                            <label for="venueSelect" class="block text-sm font-medium text-gray-700">Sala</label>
                            <select id="venueSelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Wszystkie</option>
                            </select>
                        </div>

                        <!-- Minimum revenue -->
                        <div>
                            <label for="minRevenue" class="block text-sm font-medium text-gray-700">Minimalny dochód (zł)</label>
                            <input type="number" id="minRevenue" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" min="0" placeholder="0" />
                        </div>

                        <!-- Dates -->
                        <div>
                            <label for="dateRange" class="block text-sm font-medium text-gray-700">Zakres dat</label>
                            <input type="date" id="startDate" class="mt-1 w-full border-gray-300 rounded-md shadow-sm mb-1" />
                            <input type="date" id="endDate" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                    </div>

                    <!-- Chart axis -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-2">Wybierz dane do wyświetlenia:</p>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="dataType" value="revenue" class="form-radio text-blue-600" checked>
                                <span class="ml-2">Dochód</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="dataType" value="tickets" class="form-radio text-blue-600">
                                <span class="ml-2">Sprzedane bilety</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="dataType" value="reservations" class="form-radio text-blue-600">
                                <span class="ml-2">Rezerwacje</span>
                            </label>
                        </div>
                    </div>

                    <!-- Confirmation button -->
                    <div class="mb-6">
                        <button id="applyFiltersBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Zastosuj filtry
                        </button>
                    </div>

                    <!-- Chart with filtered data -->
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Wykres danych</h3>
                        <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('filtered'); return false;">Eksport CSV</a> -->
                    </div>
                    <div class="h-96 w-full bg-gray-100 flex items-center justify-center relative">
                        <canvas id="filteredDataChart" class="w-full h-full"></canvas>
                        <p id="chartPlaceholder" class="text-gray-500 absolute">Kliknij przycisk by zobaczyć wykres</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts for reporting system -->

<!-- Link to have working charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>

<!-- Card details tables and charts initializers with connected database -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function to initialize tables
        function initializeTable(tableId, tableData) {
            const tableBody = document.getElementById(tableId);
            if (tableBody) {
                tableBody.innerHTML = ''; // Clear any existing rows

                // Populate the table with rows
                tableData.forEach(rowData => {
                    const row = document.createElement('tr');

                    // Loop through each property in rowData object and add it as a table cell
                    Object.values(rowData).forEach(cellData => {
                        const cell = document.createElement('td');
                        cell.classList.add('px-4', 'py-2', 'text-gray-700');
                        cell.textContent = cellData;
                        row.appendChild(cell);
                    });

                    tableBody.appendChild(row);
                });
            } else {
                console.error(`Table body with ID ${tableId} not found!`);
            }
        }

        // Helper function to initialize charts
        function initializeChart(ctx, itemsOrChartData, labelName = 'Liczba wydarzeń') {
            if (!ctx) return null;

            const isPrebuilt = itemsOrChartData.labels && itemsOrChartData.datasets;
            const chartData = isPrebuilt
                ? itemsOrChartData
                : {
                    labels: itemsOrChartData.map(item => item.name),
                    datasets: [{
                        label: labelName,
                        data: itemsOrChartData.map(item => item.events_count),
                        borderWidth: 1
                    }]
                };

            return new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        // Fetch total revenue from the ReportController
        fetch('/report/total-revenue')
            .then(res => res.json())
            .then(data => {
                const revenueElement = document.getElementById('revenueTotal');
                if (revenueElement) {
                    const revenue = parseFloat(data.totalRevenue || 0).toFixed(2);
                    revenueElement.textContent = `${revenue} zł`;
                }
            })
            .catch(error => {
                console.error('Błąd podczas pobierania łącznego dochodu:', error);
                const revenueElement = document.getElementById('revenueTotal');
                if (revenueElement) {
                    revenueElement.textContent = 'Błąd';
                }
            });

        // Fetch total number of events from the ReportController
        fetch('/report/total-events')
            .then(res => res.json())
            .then(data => {
                const eventsCountElement = document.getElementById('eventsCount');
                if (eventsCountElement) {
                    // Update the number of events in the element
                    eventsCountElement.textContent = data.totalEvents || '0';  // Use 0 as fallback if no data
                }
            })
            .catch(error => {
                console.error('Error fetching total events:', error);
                const eventsCountElement = document.getElementById('eventsCount');
                if (eventsCountElement) {
                    eventsCountElement.textContent = 'Error'; // Show error if fetch fails
                }
            });

        // Fetch total number of venues from the ReportController
        fetch('/report/total-venues')
            .then(res => res.json())
            .then(data => {
                const venuesCountElement = document.getElementById('venuesCount');
                if (venuesCountElement) {
                    venuesCountElement.textContent = data.totalVenues || '0';
                }
            })
            .catch(error => {
                console.error('Error fetching total venues:', error);
                const venuesCountElement = document.getElementById('venuesCount');
                if (venuesCountElement) {
                    venuesCountElement.textContent = 'Error'; // Show error if fetch fails
                }
            });

        // Fetch total number of categories from the ReportController
        fetch('/report/total-categories')
            .then(res => res.json())
            .then(data => {
                const categoriesCountElement = document.getElementById('categoriesCount');
                if (categoriesCountElement) {
                    categoriesCountElement.textContent = data.totalCategories || '0';
                }
            })
            .catch(error => {
                console.error('Error fetching total categories:', error);
                const categoriesCountElement = document.getElementById('categoriesCount');
                if (categoriesCountElement) {
                    categoriesCountElement.textContent = 'Error';
                }
            });

        // Fetch total number of sold tickets from the ReportController
        fetch('/report/sold-tickets')
            .then(res => res.json())
            .then(data => {
                const ticketsCountElement = document.getElementById('ticketsCount');
                if (ticketsCountElement) {
                ticketsCountElement.textContent = data.soldTickets || '0';
                }
            })
            .catch(error => {
                console.error('Error fetching total sold tickets:', error);
                const ticketsCountElement = document.getElementById('ticketsCount');
                if (ticketsCountElement) {
                ticketsCountElement.textContent = 'Error';
                }
            });
        
        // Fetch total number of active reservations from the ReportController
        fetch('/report/active-reservations')
            .then(res => res.json())
            .then(data => {
                const reservationsCountElement = document.getElementById('reservationsCount');
                if (reservationsCountElement) {
                    reservationsCountElement.textContent = data.activeReservations || '0';
                }
            })
            .catch(error => {
                console.error('Error fetching total active reservations:', error);
                const reservationsCountElement = document.getElementById('reservationsCount');
                if (reservationsCountElement) {
                    reservationsCountElement.textContent = 'Error';
                }
            });

        // Fetch occupancy from the ReportController
        fetch('/report/average-occupancy')
            .then(res => res.json())
            .then(data => {
                const occupancyPrecent = document.getElementById('occupancyPrecent');
                if (occupancyPrecent) {
                    occupancyPrecent.textContent = (data.averageOccupancy || 0) + '%';
                }
            })
            .catch(error => {
                console.error('Error fetching average occupancy:', error);
                const occupancyPrecent = document.getElementById('occupancyPrecent');
                if (occupancyPrecent) {
                    occupancyPrecent.textContent = 'Error';
                }
            });

        // Fetch occupancy details
        fetch('/report/occupancy-by-event')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Initialize table
                const tableData = Object.entries(data).map(([eventName, occupancy]) => ({
                    name: eventName,
                    occupancy: occupancy
                }));
                initializeTable('occupancyTableBody', tableData);

                // Initialize chart
                const occupancyChart = document.getElementById('occupancyChart');
                if (occupancyChart) {
                    initializeChart(occupancyChart.getContext('2d'), {
                        labels: tableData.map(item => item.name),
                        datasets: [{
                            label: 'Obłożenie (%)',
                            data: tableData.map(item => item.occupancy),
                            borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching occupancy by event:', error);
            });

        // Fetch revenue details
        fetch('/report/revenue-by-event')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Prepare table data
                const revenueData = data.revenueByEvent || {};
                const tableData = Object.entries(revenueData).map(([eventName, revenue]) => ({
                    name: eventName,
                    revenue: parseFloat(revenue).toFixed(2)
                }));
                initializeTable('revenueTableBody', tableData);

                // Prepare chart
                const revenueChartCanvas = document.getElementById('revenueChart');
                if (revenueChartCanvas) {
                    initializeChart(revenueChartCanvas.getContext('2d'), {
                        labels: tableData.map(item => item.name),
                        datasets: [{
                            label: 'Dochód (zł)',
                            data: tableData.map(item => item.revenue),
                            borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => {
                console.error('Błąd podczas pobierania dochodu per wydarzenie:', error);
            });
        
            // Fetch event summary report
            fetch('/organizer/eventsSummaryReport')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Initialize the events table
                initializeTable('eventsTableBody', data.tableData);

                // Initialize the events chart
                const eventsChart = document.getElementById('eventsChart');
                if (eventsChart) {
                    initializeChart(eventsChart.getContext('2d'), {
                        labels: ['Nadchodzące', 'Zakończone', 'Anulowane'],
                        datasets: [{
                            label: 'Liczba wydarzeń',
                            data: [
                                data.chartData['Nadchodzące'],
                                data.chartData['Zakończone'],
                                data.chartData['Anulowane']
                            ],
                            borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => console.error('Błąd raportu wydarzeń:', error));

        // Fetch venue details
        fetch('/report/venue-details')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Initialize the venues table
                initializeTable('venuesTableBody', data.venues.map(venue => ({
                    name: venue.name,
                    events_count: venue.events_count
                })));

                // Initialize the venues chart
                const venuesChart = document.getElementById('venuesChart');
                if (venuesChart) {
                    initializeChart(venuesChart.getContext('2d'), {
                        labels: data.venues.map(venue => venue.name),
                        datasets: [{
                            label: 'Liczba wydarzeń',
                            data: data.venues.map(venue => venue.events_count),
                            borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => console.error('Błąd raportu sal:', error));

        // Fetch category details
        fetch('/report/category-details')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Initialize the categories table
                initializeTable('categoriesTableBody', data.categories.map(category => ({
                    name: category.name,
                    events_count: category.events_count
                })));

                // Initialize the categories chart
                const categoriesChart = document.getElementById('categoriesChart');
                if (categoriesChart) {
                    initializeChart(categoriesChart.getContext('2d'), {
                        labels: data.categories.map(category => category.name),
                        datasets: [{
                            label: 'Liczba wydarzeń',
                            data: data.categories.map(category => category.events_count),
                            borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => console.error('Błąd raportu kategorii:', error));

        // Fetch sold tickets details
        fetch('/report/sold-tickets-by-event')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                const tableData = Object.entries(data.soldTicketsByEvent).map(([name, count]) => ({
                    name,
                    soldTickets: count
                }));
                initializeTable('ticketsTableBody', tableData);

                const ticketsChart = document.getElementById('ticketsChart');
                if (ticketsChart) {
                    initializeChart(ticketsChart.getContext('2d'), {
                        labels: tableData.map(item => item.name),
                        datasets: [{
                        label: 'Sprzedane bilety',
                        data: tableData.map(item => item.soldTickets),
                        borderWidth: 1
                        }]
                    });
                }
            })
            .catch(error => console.error('Błąd pobierania sprzedanych biletów:', error));

        // Fetch active reservations details
        fetch('/report/active-reservations-by-event')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                const reservationsByEvent = data.activeReservationsByEvent || {};
                // Initialize reservations table
                const tableData = Object.entries(reservationsByEvent).map(([eventName, count]) => ({
                name: eventName,
                activeReservations: count
                }));
                initializeTable('reservationsTableBody', tableData);

                // Initialize reservations chart
                const reservationsChart = document.getElementById('reservationsChart');
                if (reservationsChart) {
                initializeChart(reservationsChart.getContext('2d'), {
                    labels: tableData.map(item => item.name),
                    datasets: [{
                    label: 'Aktywne rezerwacje',
                    data: tableData.map(item => item.activeReservations),
                    borderWidth: 1
                    }]
                });
                }
            })
            .catch(error => {
                console.error('Error fetching active reservations by event:', error);
            });

        // Detailed chart with filtered data

        function populateSelect(url, selectId, defaultOption = 'Wszystkie') {
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById(selectId);
                    if (select) {
                        select.innerHTML = `<option value="">${defaultOption}</option>`;
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(err => console.error(`Błąd pobierania danych dla ${selectId}:`, err));
        }
        populateSelect('/report/events-dropdown', 'eventSelect', 'Wszystkie wydarzenia');
        //populateSelect('/report/categories-dropdown', 'categorySelect', 'Wszystkie kategorie');
        populateSelect('/report/venues-dropdown', 'venueSelect', 'Wszystkie sale');

        let filteredChartInstance = null;
        function destroyPreviousChart() {
            if (filteredChartInstance) {
                filteredChartInstance.destroy();
                filteredChartInstance = null;
            }
        }
        function showChartPlaceholder(show) {
            const placeholder = document.getElementById('chartPlaceholder');
            if (placeholder) {
                placeholder.style.display = show ? 'block' : 'none';
            }
        }
        document.getElementById('applyFiltersBtn').addEventListener('click', function () {
            const eventId = document.getElementById('eventSelect').value;
            //const categoryId = document.getElementById('categorySelect').value;
            const venueId = document.getElementById('venueSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const dataType = document.querySelector('input[name="dataType"]:checked')?.value;
            const minRevenue = document.getElementById('minRevenue').value;


            if (!dataType) {
                alert('Wybierz typ danych do wyświetlenia.');
                return;
            }

            const payload = {
                eventId,
                //categoryId,
                venueId,
                startDate,
                endDate,
                dataType,
                minRevenue: minRevenue ? parseFloat(minRevenue) : null
            };

            fetch('/report/filtered-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
                .then(res => {
                    if (!res.ok) throw new Error('Błąd pobierania danych.');
                    return res.json();
                })
                .then(response => {
                    const { label, data, formatter } = response;
                    if (!data.length) {
                        alert('Brak danych dla wybranych filtrów.');
                        destroyPreviousChart();
                        showChartPlaceholder(true);
                        return;
                    }
                    const ctx = document.getElementById('filteredDataChart').getContext('2d');
                    destroyPreviousChart();
                    showChartPlaceholder(false);
                    filteredChartInstance = initializeChart(ctx, {
                        labels: data.map(item => item.label),
                        datasets: [{
                            label: label,
                            data: data.map(item => item.value),
                            borderWidth: 1,
                        }]
                    });

                    if (filteredChartInstance && formatter) {
                        const chartOptions = filteredChartInstance.options;
                        if (chartOptions && chartOptions.scales?.x?.ticks) {
                            chartOptions.scales.x.ticks.callback = function (value) {
                                if (formatter === 'currency') return value + ' zł';
                                if (formatter === 'percent') return value + '%';
                                return value;
                            };
                            filteredChartInstance.update();
                        }
                    }
                })
                .catch(err => {
                    console.error('Błąd ładowania wykresu filtrów:', err);
                });
        });

    });
</script>

<!-- CSV export handler -->
<script>
    function exportCSV(type) {
        let rows = [];
        let headers = [];
        
        const tableBody = document.getElementById(`${type}TableBody`);
        const tableHead = tableBody.closest("table").querySelector("thead");
        headers = Array.from(tableHead.querySelectorAll("th")).map(th => th.innerText.trim());
        const dataRows = tableBody.querySelectorAll("tr");
        dataRows.forEach(row => {
            const cells = Array.from(row.querySelectorAll("td")).map(td => td.innerText.trim());
            rows.push(cells);
        });

        if (rows.length === 0) {
            alert("Brak danych do eksportu.");
            return;
        }

        const csvContent = [headers, ...rows].map(e => e.join(",")).join("\n");
        const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `${type}_data.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }
</script>

<!-- Toggle switch -->
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
</script>

<!-- Card toggling functionality -->
<script>
    function toggleInsightCard(cardId) {
        const detailsContainer = document.getElementById('insight-details');
        const cardDetails = document.getElementById(cardId + '-details');
        const allDetails = document.querySelectorAll('.insight-detail');
        const allCards = document.querySelectorAll('.insight-card');
        const clickedCard = document.querySelector(`[data-card-id="${cardId}"]`);

        // Reset all cards styling
        allCards.forEach(card => {
            card.classList.remove('border-blue-500');
            card.classList.add('border-gray-200');
        });

        // If already active, hide everything
        if (!cardDetails.classList.contains('hidden')) {
            detailsContainer.classList.add('hidden');
            allDetails.forEach(detail => detail.classList.add('hidden'));
            return;
        }

        // Show selected card details
        detailsContainer.classList.remove('hidden');
        allDetails.forEach(detail => detail.classList.add('hidden'));
        cardDetails.classList.remove('hidden');

        // Highlight active card
        clickedCard.classList.remove('border-gray-200');
        clickedCard.classList.add('border-blue-500');
    }
</script>
@endsection
