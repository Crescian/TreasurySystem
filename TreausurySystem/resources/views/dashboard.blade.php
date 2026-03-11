<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Treasury Dashboard') }}
            </h2>
            <div class="flex items-center gap-2">
                <label for="dashboard-date" class="text-sm font-medium text-gray-600">Date:</label>
                <input type="date" id="dashboard-date" value="{{ date('Y-m-d') }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Intro -->
            <p class="text-sm text-gray-600">
                {{ __('Monitor daily treasury operations, track cash flow, and access key modules for decision-making and reconciliation.') }}
            </p>

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-emerald-500">
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <i class="fas fa-arrow-down text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Inflow</p>
                                <p class="text-xl font-bold text-gray-900" id="kpi-inflow">₱0</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-rose-500">
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                <i class="fas fa-arrow-up text-rose-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Outflow</p>
                                <p class="text-xl font-bold text-gray-900" id="kpi-outflow">₱0</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-balance-scale text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Net Cash Flow</p>
                                <p class="text-xl font-bold text-gray-900" id="kpi-net">₱0</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-amber-500">
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-receipt text-amber-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Transactions</p>
                                <p class="text-xl font-bold text-gray-900" id="kpi-count">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual: Inflow vs Outflow -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Cash Flow Summary</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Inflow</span>
                                <span class="font-medium text-emerald-600" id="chart-inflow-label">₱0</span>
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" id="chart-inflow-bar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Outflow</span>
                                <span class="font-medium text-rose-600" id="chart-outflow-label">₱0</span>
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full transition-all duration-500" id="chart-outflow-bar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Proportional view of selected date’s inflow vs outflow. Data from logs.</p>
                </div>
            </div>

            <!-- Daily Modules – Quick Access -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Daily Treasury Modules</h3>
                    <p class="text-sm text-gray-600 mb-6">Quick access to record and manage daily operations.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('daily') }}?report={{ urlencode('Fund Transfer') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors"><i class="fas fa-exchange-alt text-indigo-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-indigo-800">Fund Transfer</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-indigo-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Collection') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors"><i class="fas fa-coins text-emerald-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-emerald-800">Collection</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-emerald-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Disbursement/Cash Advance') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-rose-300 hover:bg-rose-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-rose-100 flex items-center justify-center group-hover:bg-rose-200 transition-colors"><i class="fas fa-money-bill-wave text-rose-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-rose-800">Disbursement / Cash Advance</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-rose-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Liquidation / Reimbursement') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-amber-300 hover:bg-amber-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors"><i class="fas fa-file-invoice-dollar text-amber-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-amber-800">Liquidation / Reimbursement</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-amber-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Management Fees') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-violet-300 hover:bg-violet-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors"><i class="fas fa-percentage text-violet-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-violet-800">Management Fees</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-violet-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Interest Income') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-teal-300 hover:bg-teal-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center group-hover:bg-teal-200 transition-colors"><i class="fas fa-chart-line text-teal-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-teal-800">Interest Income</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-teal-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Placement Maturity') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors"><i class="fas fa-calendar-check text-blue-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-blue-800">Placement Maturity</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                        </a>
                        <a href="{{ route('daily') }}?report={{ urlencode('Investment Maturity') }}" class="group flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-cyan-300 hover:bg-cyan-50 transition-all duration-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-cyan-100 flex items-center justify-center group-hover:bg-cyan-200 transition-colors"><i class="fas fa-piggy-bank text-cyan-600"></i></div>
                            <div class="min-w-0 flex-1"><p class="font-medium text-gray-900 group-hover:text-cyan-800">Investment Maturity</p><p class="text-xs text-gray-500">Open in Daily</p></div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-cyan-600"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Reconciliation -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('daily') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-plus-circle text-indigo-500"></i>
                                <span class="text-sm font-medium text-gray-700">New transaction (Daily)</span>
                            </a>
                            <a href="{{ route('cashpo') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-wallet text-emerald-500"></i>
                                <span class="text-sm font-medium text-gray-700">Cash position</span>
                            </a>
                            <a href="{{ route('logs') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-list text-amber-500"></i>
                                <span class="text-sm font-medium text-gray-700">View logs</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-4">Reconciliation</h3>
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-check-double text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Daily reconciliation</p>
                                <p class="text-sm text-gray-500">Use Daily and Cash Position to match bank statements and clear variances.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const dateEl = document.getElementById('dashboard-date');
            const kpiInflow = document.getElementById('kpi-inflow');
            const kpiOutflow = document.getElementById('kpi-outflow');
            const kpiNet = document.getElementById('kpi-net');
            const kpiCount = document.getElementById('kpi-count');
            const chartInflowBar = document.getElementById('chart-inflow-bar');
            const chartOutflowBar = document.getElementById('chart-outflow-bar');
            const chartInflowLabel = document.getElementById('chart-inflow-label');
            const chartOutflowLabel = document.getElementById('chart-outflow-label');

            function formatPhp(n) {
                return '₱' + Number(n).toLocaleString('en-US');
            }

            function updateKpis(data) {
                const inflow = data.inflow ?? 0;
                const outflow = data.outflow ?? 0;
                const net = inflow - outflow;
                const count = data.count ?? 0;

                kpiInflow.textContent = formatPhp(inflow);
                kpiOutflow.textContent = formatPhp(outflow);
                kpiNet.textContent = formatPhp(net);
                kpiNet.classList.toggle('text-emerald-600', net >= 0);
                kpiNet.classList.toggle('text-rose-600', net < 0);
                kpiCount.textContent = count;

                chartInflowLabel.textContent = formatPhp(inflow);
                chartOutflowLabel.textContent = formatPhp(outflow);

                const total = Math.max(inflow + outflow, 1);
                const inflowPct = Math.min(100, (inflow / total) * 100);
                const outflowPct = Math.min(100, (outflow / total) * 100);
                chartInflowBar.style.width = inflowPct + '%';
                chartOutflowBar.style.width = outflowPct + '%';
            }

            function fetchDashboardData() {
                const date = dateEl ? dateEl.value : new Date().toISOString().split('T')[0];
                fetch('{{ route('dashboard.daily') }}?date=' + encodeURIComponent(date), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) { return r.ok ? r.json() : null; })
                    .then(function (data) {
                        if (data && typeof data.inflow === 'number' && typeof data.outflow === 'number') {
                            updateKpis(data);
                        } else {
                            updateKpis({ inflow: 0, outflow: 0, count: 0 });
                        }
                    })
                    .catch(function () {
                        updateKpis({ inflow: 0, outflow: 0, count: 0 });
                    });
            }

            if (dateEl) dateEl.addEventListener('change', fetchDashboardData);
            fetchDashboardData();
        })();
    </script>
</x-app-layout>
