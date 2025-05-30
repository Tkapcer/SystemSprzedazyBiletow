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
            <!-- <div class="mb-6">
                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer">
                        <span class="mr-3 text-sm font-medium text-gray-700">Całościowe</span>
                        <div class="relative">
                            <input type="checkbox" id="view_mode" name="view_mode" value="partial" class="sr-only">
                            <div class="block bg-gray-300 w-14 h-7 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Szczegółowe</span>
                    </label>
                </div>
            </div> -->

            <!-- Overall view (całościowe) - default view -->
            <div id="overall-view">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total revenue card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="revenue" onclick="toggleInsightCard('revenue')">
                        <div class="text-sm text-gray-600 mb-1">Łączny dochód</div>
                        <div class="text-2xl font-bold"></div>
                    </div>

                    <!-- Occupancy rate card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="occupancy" onclick="toggleInsightCard('occupancy')">
                        <div class="text-sm text-gray-600 mb-1">Obłożenie</div>
                        <div class="text-2xl font-bold"></div>
                    </div>

                    <!-- Tickets sold card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="tickets" onclick="toggleInsightCard('tickets')">
                        <div class="text-sm text-gray-600 mb-1">Sprzedanych biletów</div>
                        <div class="text-2xl font-bold"></div>
                    </div>

                    <!-- Active reservations card -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer insight-card"
                         data-card-id="reservations" onclick="toggleInsightCard('reservations')">
                        <div class="text-sm text-gray-600 mb-1">Aktywnych rezerwacji</div>
                        <div class="text-2xl font-bold"></div>
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('revenue'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('occupancy'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('tickets'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('reservations'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('events'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('venues'); return false;">Eksport CSV</a> -->
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
                            <!-- <a href="#" class="text-blue-500 hover:text-blue-700 text-sm" onclick="exportCSV('categories'); return false;">Eksport CSV</a> -->
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

            <!-- Partial view (szczegółowe) - hidden by default -->
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
        function initializeChart(ctx, data) {
            if (ctx) {
                return new Chart(ctx, {
                    type: 'bar',
                    data: data,
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
            return null;
        }

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
                    // Update the number of venues in the element
                    venuesCountElement.textContent = data.totalVenues || '0';  // Use 0 as fallback if no data
                }
            })
            .catch(error => {
                console.error('Error fetching total venues:', error);
                const venuesCountElement = document.getElementById('venuesCount');
                if (venuesCountElement) {
                    venuesCountElement.textContent = 'Error'; // Show error if fetch fails
                }
            });

        // Fetch event summary report
        fetch('/organizer/eventsSummaryReport')
            .then(res => {
                if (!res.ok) throw new Error('Błąd pobierania danych');
                return res.json();
            })
            .then(data => {
                // Initialize the events table
                const tableData = data.tableData;
                initializeTable('eventsTableBody', tableData);

                // Initialize the events chart
                const chartData = data.chartData;
                const eventsChart = document.getElementById('eventsChart');
                if (eventsChart) {
                    const chartDataForEvents = {
                        labels: ['Nadchodzące', 'Zakończone', 'Anulowane'],
                        datasets: [{
                            label: 'Liczba wydarzeń',
                            data: [
                                chartData['Nadchodzące'],
                                chartData['Zakończone'],
                                chartData['Anulowane']
                            ]
                        }]
                    };
                    initializeChart(eventsChart.getContext('2d'), chartDataForEvents);
                }
            })
            .catch(error => console.error('Błąd raportu wydarzeń:', error));

        // Fetch venue details
        fetch('/report/venue-details')
            .then(res => res.json())
            .then(data => {
                // Initialize the venues table
                const venuesTableBody = document.getElementById('venuesTableBody');
                if (venuesTableBody) {
                    venuesTableBody.innerHTML = '';  // Clear any existing rows
                    data.venues.forEach(venue => {
                        const row = document.createElement('tr');
                        const venueNameCell = document.createElement('td');
                        venueNameCell.classList.add('px-6', 'py-3', 'text-left');
                        venueNameCell.textContent = venue.name;  // Assuming 'name' field exists

                        const eventsCountCell = document.createElement('td');
                        eventsCountCell.classList.add('px-6', 'py-3', 'text-left');
                        eventsCountCell.textContent = venue.events_count;  // Assuming 'events_count' field exists

                        row.appendChild(venueNameCell);
                        row.appendChild(eventsCountCell);
                        venuesTableBody.appendChild(row);
                    });
                }

                // Initialize the venues chart
                const venuesChartCtx = document.getElementById('venuesChart');
                if (venuesChartCtx) {
                    const venuesChart = new Chart(venuesChartCtx, {
                        type: 'bar',
                        data: {
                            labels: data.venues.map(venue => venue.name),
                            datasets: [{
                                label: 'Liczba wydarzeń',
                                data: data.venues.map(venue => venue.events_count),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching venue details:', error);
            });
    });
</script>

 <!-- CSV export handler -->
<script>
    function exportCSV(tableId) {
        const tableBody = document.getElementById(`${tableId}Body`);
        if (!tableBody) {
            console.error(`Table body with ID ${tableId} not found!`);
            return;
        }

        // Get the table itself to access headers
        const table = tableBody.closest('table');
        const headers = table.querySelector('thead');
        if (!headers) {
            console.error(`Table headers for ${tableId} not found!`);
            return;
        }

        // Get header data
        const headerCells = headers.querySelectorAll('th');
        const headerRow = Array.from(headerCells).map(cell => cell.textContent.trim());
        let csvContent = headerRow.join(',') + '\n'; // Add headers to the CSV content

        // Get data from table rows
        const rows = tableBody.querySelectorAll('tr');
        if (rows.length === 0) {
            console.error(`No rows found in table with ID ${tableId}`);
            return;
        }

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = Array.from(cells).map(cell => cell.textContent.trim());
            csvContent += rowData.join(',') + '\n'; // Add each row's data
        });

        // Create a Blob and trigger the download
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${tableId}.csv`; // Name the file based on the table ID
        link.click();
        URL.revokeObjectURL(url); // Clean up after download
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
