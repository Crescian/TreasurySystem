<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ADB') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">

                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button
                                class="tab-btn text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm border-b-2 border-transparent focus:outline-none active"
                                data-tab="legacy-view">
                                Legacy View ADB
                            </button>
                            <button
                                class="tab-btn text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm border-b-2 border-transparent focus:outline-none"
                                data-tab="table-view">
                                Table View ADB
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Panels -->
                    <div id="tab-content">
                        <!-- Summary Tab -->
                        <div id="legacy-view" class="tab-pane">
                            <h2 class="text-lg font-medium text-gray-900">Details View</h2>
                            <p class="mt-1 text-sm text-gray-600">You can place detailed ADB breakdowns or graphs here.
                            </p>
                            <div id="adb-container" class="overflow-auto mt-4">
                                <table id="adb-table"
                                    class="min-w-full border text-xs text-center table-auto shadow-md">
                                    <thead class="bg-gray-100 sticky top-0">
                                        <tr id="adb-header">
                                            <th class="border px-4 py-2 bg-white">Company</th>
                                            <!-- Bank columns will be inserted here -->
                                        </tr>
                                    </thead>
                                    <tbody id="adb-body">
                                        <!-- Data rows will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Details Tab -->
                        <div id="table-view" class="tab-pane hidden">
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Average Daily Balance (ADB)') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Average Daily Balance (ADB) is a banking calculation method used to determine interest, fees, or account balances by averaging the daily balances over a specific period. This approach ensures accurate financial computations for loans, credit accounts, and savings.') }}
                            </p>
                            <div class="table-responsive mt-4">
                                <table id="adbTable" class="table-sm w-100">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Company</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Banks</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total Balance</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- View Bank Account --}}
    <x-modal name="view-bank-account" focusable>
        <div class="p-4">
            <!-- ✅ Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">View Bank Accounts</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- ✅ Bank Accounts Container (Scrollable) -->
            <div class="max-h-80 overflow-y-auto bg-gray-50 rounded-lg shadow-inner p-3 border border-gray-200">
                <!-- ✅ Loop through multiple accounts (example with 3) -->
                <div class="space-y-4">
                    <!-- Account Item -->
                    <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bank Name -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Bank Name:</label>
                                <p class="text-base text-gray-800 font-medium">BDO</p>
                            </div>
                            <!-- Account Name -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Name:</label>
                                <p class="text-base text-gray-800 font-medium">Juan Dela Cruz</p>
                            </div>
                            <!-- Account Number -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Number:</label>
                                <p class="text-base text-gray-800 font-medium">1234-5678-9012</p>
                            </div>
                            <!-- Balance -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Balance:</label>
                                <p class="text-base text-green-600 font-semibold">₱25,000.30</p>
                            </div>
                        </div>
                    </div>

                    <!-- Another Account Item -->
                    <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Bank Name:</label>
                                <p class="text-base text-gray-800 font-medium">BPI</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Name:</label>
                                <p class="text-base text-gray-800 font-medium">Maria Santos</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Number:</label>
                                <p class="text-base text-gray-800 font-medium">9876-5432-1000</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Balance:</label>
                                <p class="text-base text-green-600 font-semibold">₱18,500.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Another Account Item -->
                    <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Bank Name:</label>
                                <p class="text-base text-gray-800 font-medium">Metrobank</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Name:</label>
                                <p class="text-base text-gray-800 font-medium">Pedro Reyes</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Account Number:</label>
                                <p class="text-base text-gray-800 font-medium">4567-8901-2233</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-600 font-semibold">Balance:</label>
                                <p class="text-base text-green-600 font-semibold">₱12,000.00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ✅ Modal Footer -->
            <div class="flex justify-end mt-4">
                <button @click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Close
                </button>
            </div>
        </div>
    </x-modal>

</x-app-layout>


<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        displayDataTable();
    });

    function viewBankAccounts(id) {
        window.dispatchEvent(new Event('open-modal'));
    }
    loadAdbTable();

    function loadAdbTable() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/adb-data', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const banks = response.banks;
                    const adbData = response.data;

                    const headerRow = document.getElementById('adb-header');
                    headerRow.innerHTML = '<th class="border px-4 py-2">Company</th>';
                    banks.forEach(bank => {
                        const th = document.createElement('th');
                        th.className = 'border px-4 py-2 text-[10px]';
                        th.textContent = bank.bank.toUpperCase();
                        headerRow.appendChild(th);
                    });

                    const tbody = document.getElementById('adb-body');
                    tbody.innerHTML = ''; // Clear existing rows
                    adbData.forEach(row => {
                        const tr = document.createElement('tr');

                        const companyCell = document.createElement('td');
                        companyCell.className = 'border px-4 py-2';
                        companyCell.textContent = row.company;
                        tr.appendChild(companyCell);

                        banks.forEach(bank => {
                            const td = document.createElement('td');
                            td.className = 'border px-4 py-2';
                            td.textContent = row[bank.bank] ?? '-';
                            tr.appendChild(td);
                        });

                        tbody.appendChild(tr);
                    });
                } else {
                    console.error('Error fetching ADB data. Status:', xhr.status);
                }
            }
        };

        xhr.send();
    }
    // ✅ Function to display DataTable
    function displayDataTable() {
        $('#adbTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('company.balances') }}', // ✅ Ensure route is correct
            columns: [{
                    data: 'company',
                    name: 'company'
                },
                {
                    data: 'bank_name',
                    name: 'bank_name'
                },
                {
                    data: 'total_balance',
                    name: 'total_balance',
                    render: function(data) {
                        return '₱' + parseFloat(data).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            drawCallback: function(settings) {
                // ✅ Reinitialize tooltips after each draw
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }


    const tabs = document.querySelectorAll('.tab-btn');
    const panes = document.querySelectorAll('.tab-pane');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active classes
            tabs.forEach(t => t.classList.remove('border-indigo-500', 'text-indigo-600', 'active'));
            panes.forEach(p => p.classList.add('hidden'));

            // Add active to clicked tab
            tab.classList.add('border-indigo-500', 'text-indigo-600', 'active');
            const tabId = tab.getAttribute('data-tab');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
</script>
