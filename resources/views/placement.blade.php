<style>
    html {
        scroll-behavior: smooth;
    }
</style>
<style>
    /* From Uiverse.io by SchawnnahJ */
    .loader {
        position: relative;
        width: 2.5em;
        height: 2.5em;
        transform: rotate(165deg);
    }

    .loader:before,
    .loader:after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        display: block;
        width: 0.5em;
        height: 0.5em;
        border-radius: 0.25em;
        transform: translate(-50%, -50%);
    }

    .loader:before {
        animation: before8 2s infinite;
    }

    .loader:after {
        animation: after6 2s infinite;
    }

    @keyframes before8 {
        0% {
            width: 0.5em;
            box-shadow: 1em -0.5em rgba(225, 20, 98, 0.75), -1em 0.5em rgba(111, 202, 220, 0.75);
        }

        35% {
            width: 2.5em;
            box-shadow: 0 -0.5em rgba(225, 20, 98, 0.75), 0 0.5em rgba(111, 202, 220, 0.75);
        }

        70% {
            width: 0.5em;
            box-shadow: -1em -0.5em rgba(225, 20, 98, 0.75), 1em 0.5em rgba(111, 202, 220, 0.75);
        }

        100% {
            box-shadow: 1em -0.5em rgba(225, 20, 98, 0.75), -1em 0.5em rgba(111, 202, 220, 0.75);
        }
    }

    @keyframes after6 {
        0% {
            height: 0.5em;
            box-shadow: 0.5em 1em rgba(61, 184, 143, 0.75), -0.5em -1em rgba(233, 169, 32, 0.75);
        }

        35% {
            height: 2.5em;
            box-shadow: 0.5em 0 rgba(61, 184, 143, 0.75), -0.5em 0 rgba(233, 169, 32, 0.75);
        }

        70% {
            height: 0.5em;
            box-shadow: 0.5em -1em rgba(61, 184, 143, 0.75), -0.5em 1em rgba(233, 169, 32, 0.75);
        }

        100% {
            box-shadow: 0.5em 1em rgba(61, 184, 143, 0.75), -0.5em -1em rgba(233, 169, 32, 0.75);
        }
    }

    .loader {
        position: absolute;
        top: calc(50% - 1.25em);
        left: calc(50% - 1.25em);
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Placement') }}
        </h2>
    </x-slot>
    <div class="py-12 bg-gray-100 min-h-screen">
        {{-- Floating Sidebar Placeholder --}}
        {{-- <div id="sidebar-nav" style="padding: 10px;"></div> --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="spinner">
                    <div class="loader"></div>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-extrabold text-gray-800 uppercase">PETROLIFT GROUP OF COMPANIES</h1>
                        <h2 class="text-lg font-semibold text-gray-600 tracking-wide">SUMMARY OF PLACEMENTS - RSF</h2>
                    </div>
                    <div id="placement-wrapper" class="space-y-6 text-sm mt-3"></div>

                </div>
            </div>
        </div>
    </div>
    {{-- View Add Placement --}}
    <x-modal name="add-placement" focusable>
        <div class="p-4">
            <!-- ✅ Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Add Placement</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- ✅ Form Layout Similar to Viewing Style -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="bank" class="block font-medium text-gray-600 mb-1">Bank</label>
                    <select onchange="getPlacementBankAccountName(this.value);" id="bank" name="bank"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200">
                        <option value="" disabled selected>Select a bank...</option>
                        <!-- Add more as needed -->
                    </select>
                </div>
                <div>
                    <label for="account_name" class="block font-medium text-gray-600 mb-1">Account Name</label>
                    <select id="account_name" name="account_name"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200">
                        <option value="" disabled selected>Select account name...</option>
                        <option value="John Doe">John Doe</option>
                        <option value="Jane Smith">Jane Smith</option>
                        <option value="Corporate Account">Corporate Account</option>
                        <!-- Add more as needed -->
                    </select>
                </div>
                <div>
                    <label for="placement_date" class="block font-medium text-gray-600 mb-1">Placement Date</label>
                    <input type="date" id="placement_date" name="placement_date"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="maturity_date" class="block font-medium text-gray-600 mb-1">Maturity Date</label>
                    <input type="date" id="maturity_date" name="maturity_date"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="amount" class="block font-medium text-gray-600 mb-1">Amount</label>
                    <input type="number" id="amount" name="amount" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="interest_rate" class="block font-medium text-gray-600 mb-1">
                        Interest Rate (%)
                    </label>
                    <input type="number" id="interest_rate" name="interest_rate" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>

                <div class="mt-2">
                    <label for="interest_net" class="block font-medium text-gray-600 mb-1">
                        Net Rate (%)
                    </label>
                    <input type="number" id="interest_net" name="interest_net" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 bg-gray-100" />
                </div>
                <div>
                    <label for="days" class="block font-medium text-gray-600 mb-1">No. of Days</label>
                    <input type="number" id="days" name="days"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="interest_income" class="block font-medium text-gray-600 mb-1">Interest Income</label>
                    <input type="number" id="interest_income" name="interest_income" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
            </div>
            <!-- ✅ Modal Footer -->
            <div class="flex justify-between mt-6 space-x-2">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Close
                </button>
                <button @click="$dispatch('close')" onclick="addPlacementAction()"
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                    Add Placement
                </button>
                <button @click="$dispatch('close')" onclick="RerollPlacementAction()"
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition RerollPlacementAction">
                    Reroll Placement
                </button>
            </div>
        </div>
    </x-modal>
    {{-- View Placement --}}
    <x-modal name="view-placement" focusable>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Placement Option</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <h3 class="text-md font-semibold text-gray-600 mb-2">Placement Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 border p-4 rounded bg-gray-50 mb-4">
                <div><strong>Company:</strong> <span id="placement-company"></span></div>
                <div><strong>Bank:</strong> <span id="placement-bank"></span></div>
                <div><strong>Account:</strong> <span id="placement-account"></span></div>
                <div><strong>Placement Date:</strong> <span id="placement-date"></span></div>
                <div><strong>Maturity Date:</strong> <span id="maturity-date"></span></div>
                <div><strong>Amount:</strong> <span id="placement-amount"></span></div>
                <div><strong>Interest Rate:</strong> <span id="interest-rate"></span></div>
                <div><strong>Net Interest:</strong> <span id="net-interest"></span></div>
                <div><strong>Days:</strong> <span id="no-of-days"></span></div>
                <div><strong>Income:</strong> <span id="interest-income"></span></div>
                <div><strong>Maturity Value:</strong> <span id="maturity-value"></span></div>
            </div>
            <h3 class="text-md font-semibold text-gray-600 mb-2">Select Action</h3>
            <select onchange="getPlacementOptionValue(this.value);" id="address" name="address"
                class="block w-full border p-2 rounded" required>
                <option value="" disabled selected>Select placement option...</option>
                <option value="Reroll">🔁 Reroll Placement - Extend term with added interest</option>
                <option value="Credit">💳 Credit to Deposit - Transfer back to deposit account</option>
            </select>
            <div class="flex justify-between mt-6 space-x-2">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Close
                </button>
                <button @click="$dispatch('close')" onclick="proceedAction()"
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                    Proceed
                </button>
            </div>

        </div>
    </x-modal>
    {{-- View Reroll --}}
    <x-modal name="reroll" focusable>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Reroll</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <h3 class="text-md font-semibold text-gray-600 mb-2">Placement Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 border p-4 rounded bg-gray-50 mb-4">
                <div>
                    <label class="block font-medium text-gray-600 mb-1">Company</label>
                    <span id="reroll-placement-company" class="block px-2 py-1 bg-gray-100 rounded"></span>
                </div>
                <div>
                    <label class="block font-medium text-gray-600 mb-1">Bank</label>
                    <span id="reroll-placement-bank" class="block px-2 py-1 bg-gray-100 rounded"></span>
                </div>
                <div>
                    <label class="block font-medium text-gray-600 mb-1">Account</label>
                    <span id="reroll-placement-account" class="block px-2 py-1 bg-gray-100 rounded"></span>
                </div>
                <div>
                    <label for="reroll-placement_date" class="block font-medium text-gray-600 mb-1">Placement
                        Date</label>
                    <input type="date" id="reroll-placement_date" name="reroll-placement_date"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="reroll-maturity_date" class="block font-medium text-gray-600 mb-1">Maturity
                        Date</label>
                    <input type="date" id="reroll-maturity_date" name="reroll-maturity_date"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="reroll-amount" class="block font-medium text-gray-600 mb-1">Amount</label>
                    <input type="number" id="reroll-amount" name="reroll-amount" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200" />
                </div>
                <div>
                    <label for="reroll_interest_rate" class="block font-medium text-gray-600 mb-1">Interest Rate
                        (%)</label>
                    <input type="number" id="reroll_interest_rate" name="reroll_interest_rate" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring focus:ring-indigo-200"
                        required />
                </div>
                <div>
                    <label for="reroll_interest_net" class="block font-medium text-gray-600 mb-1">Net Rate (%)</label>
                    <input type="number" id="reroll_interest_net" name="reroll_interest_net" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1" required />
                </div>
                <div>
                    <label for="reroll_no_of_days" class="block font-medium text-gray-600 mb-1">Days</label>
                    <input type="number" id="reroll_no_of_days" name="reroll_no_of_days"
                        class="w-full border border-gray-300 rounded px-2 py-1" required />
                </div>
                <div>
                    <label for="reroll_interest_income" class="block font-medium text-gray-600 mb-1">Income</label>
                    <input type="number" id="reroll_interest_income" name="reroll_interest_income" step="0.01"
                        class="w-full border border-gray-300 rounded px-2 py-1" required />
                </div>
            </div>
            <div class="flex justify-between mt-6 space-x-2">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Close
                </button>
                <button @click="$dispatch('close')" onclick="rerollAction()"
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                    Reroll Now
                </button>
            </div>
        </div>
    </x-modal>
    <x-modal name="credit" focusable>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Credit</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <h3 class="text-md font-semibold text-gray-600 mb-2">Placement Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 border p-4 rounded bg-gray-50 mb-4">
                <div><strong>Company:</strong> <span id="credit-placement-company"></span></div>
                <div><strong>Bank:</strong> <span id="credit-placement-bank"></span></div>
                <div><strong>Account:</strong> <span id="credit-placement-account"></span></div>
                <div><strong>Placement Date:</strong> <span id="credit-placement-date"></span></div>
                <div><strong>Maturity Date:</strong> <span id="credit-maturity-date"></span></div>
                <div><strong>Amount:</strong> <span id="credit-placement-amount"></span></div>
                <div><strong>Interest Rate:</strong> <span id="credit-interest-rate"></span></div>
                <div><strong>Net Interest:</strong> <span id="credit-net-interest"></span></div>
                <div><strong>Days:</strong> <span id="credit-no-of-days"></span></div>
                <div><strong>Income:</strong> <span id="credit-interest-income"></span></div>
                <div><strong>Matury Value:</strong> <span id="credit-matury-value"></span></div>
            </div>
            <!-- ✅ Dropdowns Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Company Select -->
                <div>
                    <label for="credit-company-select" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Company
                    </label>
                    <select id="credit-company-select"
                        onchange="getBanksSelection(this.value, 'credit-bank-select', 'credit-account-select');"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-black focus:border-black credit-company-select">
                        <option value="">-- Choose Company --</option>
                        <!-- Populate via JS -->
                    </select>
                </div>

                <!-- Bank Select -->
                <div>
                    <label for="credit-bank-select" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Bank
                    </label>
                    <select onchange="getDepositAccountDetails(this.value, 'credit-account-select');"
                        id="credit-bank-select"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-black focus:border-black">
                        <option value="">-- Choose Bank --</option>
                        <!-- Populate via JS -->
                    </select>
                </div>

                <!-- Bank Account Select -->
                <div>
                    <label for="credit-account-select" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Bank Account
                    </label>
                    <select onchange="getAccountBalance(this.value, 'account_balance');" id="credit-account-select"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-black focus:border-black">
                        <option value="">-- Choose Account --</option>
                        <!-- Populate via JS -->
                    </select>
                </div>

                <!-- Amount to Credit -->
                <div>
                    <label for="credit-amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Amount to Credit
                    </label>
                    <input type="number" id="credit-amount" name="credit_amount" min="0" step="0.01"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-black focus:border-black"
                        placeholder="Enter amount (₱)">
                </div>
                <!-- ✅ Placement Date -->
                <div>
                    <label for="credit-placement-credit-date" class="block text-sm font-medium text-gray-700 mb-1">
                        Placement Date
                    </label>
                    <input type="date" id="credit-placement-credit-date" name="credit_placement_date"
                        class="w-full border-gray-300 rounded shadow-sm focus:ring-black focus:border-black">

                </div>
                <!-- Balance Display -->
                <div class="w-full sm:w-1/2 md:w-1/4 flex items-center gap-2 mt-2">
                    <span class="text-sm font-medium text-gray-700">Balance:</span>
                    <span id="account_balance" class="text-green-600 font-semibold">₱0</span>
                </div>
            </div>


            <!-- ✅ Modal Footer -->
            <div class="flex justify-between mt-6 space-x-2">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Close
                </button>
                <button @click="$dispatch('close')" onclick="creditAction()"
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                    Proceed
                </button>
            </div>
        </div>
    </x-modal>

</x-app-layout>
<script src="{{ asset('js/jquery.min.js') }}"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    loadPlacement();
    let placementOption;
    let getGlobalId;
    let companyId;
    let bankAccountId;
    let getAccountBalanceId;
    let getPlacementBalance;

    // Smooth reveal after short delay
    setTimeout(() => {
        $('.spinner').fadeOut(200, function() {
            $('thead').fadeIn(200);
            $('tbody').fadeIn(200);
        });
    }, 1000); // Optional short delay to show spinner briefly
    getCompanies();

    function getCompanies() {
        $('.credit-company-select').html('');
        $('#credit-company-select').append(`<option value="" disabled selected>Select an company</option>`);
        $.ajax({
            url: @json(route('company.show')), // Ensure proper URL formatting
            type: 'GET',
            dataType: 'json', // Specify JSON response type
            success: function(response) {
                $.each(response, (key, value) => {
                    $('#credit-company-select').append(
                        `<option value="${value.id}">${value.company}</option>`);
                });
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching credit-company-select:", error);
            }
        });
    }

    function getBanksSelection(id, bank, account) {
        $('#' + bank).html('');
        $('#' + bank).append(
            `<option value="">Select Bank...</option>`);
        $('#' + account).html("");
        $.ajax({
            url: `{{ route('bank.showSelection', ':id') }}`.replace(':id', id),
            type: 'GET', // Changed from POST to GET
            success: function(response) {
                $.each(response, (key, value) => {
                    console.log(value);
                    $('#' + bank).append(
                        `<option value="${value.id}">${value.bank}</option>`);

                });
            }
        });
    }

    function getDepositAccountDetails(id, accountId) {
        $('#' + accountId).html("");
        $.ajax({
            url: `{{ route('bankAccounts.show', ['id' => ':id']) }}`.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.length === 0) {
                    $('#' + accountId).append(`<option value="">No Account Details</option>`);
                } else {
                    $('#' + accountId).append(`<option value="">Select ...</option>`);
                    $.each(data, (key, value) => {
                        $('#' + accountId).append(
                            `<option value="${value.id}">${value.account_name} - ${value.account_number}</option>`
                        );
                    })
                }
            }
        });
    }

    function getAccountBalance(id, account_balance) {
        getAccountBalanceId = id;
        $.ajax({
            url: `{{ route('bankAccounts.balance', ['id' => ':id']) }}`.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#' + account_balance).html(`₱${Number(data.balance).toLocaleString('en-US')}`);
            }
        });
    }

    function creditAction() {
        $.ajax({
            url: `{{ route('bank.AccountController', ['id' => ':id']) }}`
                .replace(
                    ':id',
                    getAccountBalanceId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                amount: getPlacementBalance,
                flow_type: 'Inflow',
            },
            success: function(data) {
                let creditAmount = parseFloat($('#credit-amount').val());
                let resultBalance = getPlacementBalance - creditAmount;
                const datas = {
                    company_id: companyId, // replace with dynamic value if needed
                    bank_account_id: bankAccountId,
                    placement_date: $('#credit-placement-credit-date').val(),
                    maturity_date: '',
                    amount: resultBalance,
                    interest_rate: '',
                    interest_net: '',
                    no_of_days: '',
                    interest_income: '',
                    status: 'Credit',
                };

                $.ajax({
                    url: '/placements/store',
                    type: 'POST',
                    data: JSON.stringify(datas),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Swal.fire({
                        //     position: "center", // Centers the alert
                        //     icon: 'success',
                        //     title: "Placement added successfully.",
                        //     showConfirmButton: false,
                        //     timer: 1500,
                        //     width: "500px" // Makes the alert smaller
                        // });
                        location.reload(); // or update the table dynamically
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Error adding placement.");
                    }
                });

                Swal.fire({
                    position: "center", // Centers the alert
                    icon: data.status,
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    width: "500px" // Makes the alert smaller
                });
            },
            error: function(xhr) {
                Swal.fire({
                    position: "center", // Centers the alert
                    icon: "error",
                    title: "Insufficient balance for outflow.",
                    showConfirmButton: false,
                    timer: 5000,
                    width: "500px" // Makes the alert smaller
                });
            }
        });
    }

    function rerollAction() {
        // Gather values
        const placementDate = $('#reroll-placement_date').val();
        const maturityDate = $('#reroll-maturity_date').val();
        const amount = parseFloat($('#reroll-amount').val());
        const interestRate = parseFloat($('#reroll_interest_rate').val());
        const interestNet = parseFloat($('#reroll_interest_net').val());
        const noOfDays = parseInt($('#reroll_no_of_days').val());
        const interestIncome = parseFloat($('#reroll_interest_income').val());

        // ✅ Basic required validation
        if (!placementDate || !maturityDate || isNaN(amount) || isNaN(interestRate) || isNaN(interestNet) || isNaN(
                noOfDays) || isNaN(interestIncome)) {
            Swal.fire({
                icon: 'warning',
                title: 'Please fill in all required fields.',
                confirmButtonText: 'OK'
            });
            return; // Stop if any field is empty or invalid
        }

        const data = {
            company_id: companyId, // replace with dynamic value if needed
            bank_account_id: bankAccountId,
            placement_date: $('#reroll-placement_date').val(),
            maturity_date: $('#reroll-maturity_date').val(),
            amount: parseFloat($('#reroll-amount').val()),
            interest_rate: parseFloat($('#reroll_interest_rate').val()),
            interest_net: parseFloat($('#reroll_interest_net').val()),
            no_of_days: parseInt($('#reroll_no_of_days').val()),
            interest_income: parseFloat($('#reroll_interest_income').val()),
            status: 'Reroll',
        };
        data.maturity_value = data.amount + data.interest_income;
        $.ajax({
            url: '/placements/store',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    position: "center", // Centers the alert
                    icon: 'success',
                    title: "Placement added successfully.",
                    showConfirmButton: false,
                    timer: 1500,
                    width: "500px" // Makes the alert smaller
                });
                location.reload(); // or update the table dynamically
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error adding placement.");
            }
        });
    }

    $('.RerollPlacementAction').hide();
    $(function() {
        function calculateNetRate() {
            const rate = parseFloat($('#interest_rate').val());
            if (!isNaN(rate)) {
                const net = rate * 0.8; // 20% tax
                $('#interest_net').val(net.toFixed(2));
            } else {
                $('#interest_net').val('');
            }
            calculateInterestIncome();
        }

        function calculateInterestIncome() {
            const amount = parseFloat($('#amount').val());
            const netRate = parseFloat($('#interest_net').val());
            const days = parseFloat($('#days').val());

            if (!isNaN(amount) && !isNaN(netRate) && !isNaN(days)) {
                const income = amount * (netRate / 100) * (days / 360);
                $('#interest_income').val(income.toFixed(2));
            } else {
                $('#interest_income').val('');
            }
        }
        // Auto-calculate net rate and income when interest rate changes
        $('#interest_rate').on('input', calculateNetRate);

        // Recalculate income when amount or days change
        $('#amount, #days').on('input', calculateInterestIncome);

        function calculateNetRateReroll() {
            const rate = parseFloat($('#reroll_interest_rate').val());
            if (!isNaN(rate)) {
                const net = rate * 0.8; // 20% tax
                $('#reroll_interest_net').val(net.toFixed(2));
            } else {
                $('#reroll_interest_net').val('');
            }
            calculateInterestIncomeReroll();
        }

        function calculateInterestIncomeReroll() {
            const amount = parseFloat($('#reroll-amount').val());
            const netRate = parseFloat($('#reroll_interest_net').val());
            const days = parseFloat($('#reroll_no_of_days').val());

            if (!isNaN(amount) && !isNaN(netRate) && !isNaN(days)) {
                const income = amount * (netRate / 100) * (days / 360);
                $('#reroll_interest_income').val(income.toFixed(2));
            } else {
                $('#reroll_interest_income').val('');
            }
        }
        // Auto-calculate net rate and income when interest rate changes
        $('#reroll_interest_rate').on('input', calculateNetRateReroll);

        // Recalculate income when amount or days change
        $('#reroll-amount, #reroll_no_of_days').on('input', calculateInterestIncomeReroll);
    });

    $(function() {
        $('#interest_rate').on('input', function() {
            const rate = parseFloat($(this).val());
            const $net = $('#interest_net');

            if (!isNaN(rate)) {
                const net = rate * 0.80; // Rate – 20%
                $net.val(net.toFixed(2));
            } else {
                $net.val('');
            }
        });
    });

    $('#placement_date, #maturity_date').on('change', function() {
        const start = new Date($('#placement_date').val());
        const end = new Date($('#maturity_date').val());

        if (start && end && !isNaN(start) && !isNaN(end)) {
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            $('#days').val(diffDays > 0 ? diffDays : 0);
        } else {
            $('#days').val('');
        }
    });

    $('#reroll-placement_date, #reroll-maturity_date').on('change', function() {
        const start = new Date($('#reroll-placement_date').val());
        const end = new Date($('#reroll-maturity_date').val());

        if (start && end && !isNaN(start) && !isNaN(end)) {
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            $('#reroll_no_of_days').val(diffDays > 0 ? diffDays : 0);
        } else {
            $('#reroll_no_of_days').val('');
        }
    });

    function addPlacementAction() {
        const data = {
            company_id: getGlobalId, // replace with dynamic value if needed
            bank_account_id: $('#bank').val(),
            placement_date: $('#placement_date').val(),
            maturity_date: $('#maturity_date').val(),
            amount: parseFloat($('#amount').val()),
            interest_rate: parseFloat($('#interest_rate').val()),
            interest_net: parseFloat($('#interest_rate').val()),
            no_of_days: parseInt($('#days').val()),
            interest_income: parseFloat($('#interest_income').val()),
            status: 'Reroll',
        };
        data.maturity_value = data.amount + data.interest_income;
        $.ajax({
            url: '/placements/store',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    position: "center", // Centers the alert
                    icon: 'success',
                    title: "Placement added successfully.",
                    showConfirmButton: false,
                    timer: 1500,
                    width: "500px" // Makes the alert smaller
                });
                location.reload(); // or update the table dynamically
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error adding placement.");
            }
        });
    }

    function getPlacementOptionValue(value) {
        placementOption = value;
    }

    function proceedAction() {
        switch (placementOption) {
            case 'Reroll':
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'reroll'
                }));
                break;
            case 'Credit':
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'credit'
                }));
                const dateInputCredit = document.getElementById('credit-placement-credit-date');
                // Format the placementDate to YYYY-MM-DD
                const formattedCreditDate = new Date(getPlacementDateCreditProceed).toISOString().split('T')[0];
                // Set value and minimum allowed date
                dateInputCredit.value = formattedCreditDate;
                dateInputCredit.min = formattedCreditDate;
                $('#credit-company-select').val(companyId).trigger('change');

                break;
        }
    }

    function addPlacement(id) {
        getGlobalId = id; // Store the ID globally to use in getBanksSelection
        getBanksSelection(id, 'bank', 'account_name');
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'add-placement'
        }));
    }

    function getPlacementBankAccountName(id) {
        $('#account_name').html("");
        $.ajax({
            url: `{{ route('bankAccounts.showPlacements', ['id' => ':id']) }}`.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.length === 0) {
                    $('#account_name').append(`<option value="">No Account Details</option>`);
                } else {
                    $('#account_name').append(`<option value="">Select ...</option>`);
                    $.each(data, (key, value) => {
                        $('#account_name').append(
                            `<option value="${value.id}">${value.account_name} - ${value.account_number}</option>`
                        );
                    })
                }
            }
        });
    }

    function loadPlacement() {
        $.ajax({
            url: '/api/placements/grouped',
            type: 'GET',
            dataType: 'json',
            success: function(companies) {
                console.log(companies);
                let html = '';
                let navHtml = `
            <div class="fixed top-24 left-6 z-50 bg-white p-3 rounded shadow-md w-56 max-h-[80vh] overflow-auto border border-gray-200">
                <h3 class="text-lg font-semibold mb-2">Companies</h3>
                <ul class="space-y-2">`;
                companies.forEach(company => {
                    const anchorId = `company-${company.id}`;
                    navHtml +=
                        `<li><a href="#${anchorId}" class="text-blue-600 hover:underline text-sm">${company.company}</a></li>`;
                    html += `
                <div id="${anchorId}" class="bg-white p-4 rounded shadow mb-6 w-full scroll-mt-20">
                    <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">${company.company}</h2>
                    <button onclick="addPlacement(${company.id})"
                        class="px-4 py-2 bg-black text-white text-sm rounded hover:bg-gray-800 transition">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                `;
                    if (company.banks && company.banks.length > 0) {
                        company.banks.forEach(bankGroup => {
                            html +=
                                `
                        <div class="mb-4">
                            <h3 class="text-md font-semibold text-gray-600 mb-2 pl-2 border-l-4 border-gray-400">${bankGroup.bank}</h3>`;
                            bankGroup.bank_accounts.forEach(account => {
                                html += `
                            <div class="mb-2 pl-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1 italic">${account.account_name}</h4>
                                <div class="overflow-auto">
                                    <table class="w-full border text-sm text-left mb-2">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 border">Placement Date</th>
                                                <th class="px-4 py-2 border">Maturity Date</th>
                                                <th class="px-4 py-2 border">Amount</th>
                                                <th class="px-4 py-2 border">Interest Rate</th>
                                                <th class="px-4 py-2 border">Net Rate</th>
                                                <th class="px-4 py-2 border">Days</th>
                                                <th class="px-4 py-2 border">Income</th>
                                                <th class="px-4 py-2 border">Maturity Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                if (account.placements.length > 0) {
                                    const latestPlacement = account.placements
                                        .reduce((latest, curr) => {
                                            return new Date(curr
                                                    .placement_date) > new Date(
                                                    latest.placement_date) ?
                                                curr : latest;
                                        }, account.placements[0]);

                                    // Sort by created_at for accurate ordering
                                    account.placements.sort((a, b) => new Date(a
                                        .created_at) - new Date(b
                                        .created_at));

                                    account.placements.forEach(p => {
                                        console.log(p);

                                        // Determine the row color dynamically
                                        let rowColor = '';
                                        if (
                                            p.status === latestPlacement
                                            .status &&
                                            p.created_at === latestPlacement
                                            .created_at
                                        ) {
                                            rowColor =
                                                "background-color: #90EE90"; // Light green for latest
                                        }
                                        if (p.status.toLowerCase() ===
                                            'credit') {
                                            rowColor =
                                                "background-color: orange"; // Orange for credit status
                                        }

                                        html +=
                                            `
                                            <tr style='${rowColor}'>
                                                <td class="px-4 py-2 border">
                                                    ${new Date(p.placement_date).toLocaleDateString('en-US', {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                    })}
                                                </td>
                                                <td class="px-4 py-2 border">
                                                    ${p.maturity_date
                                                        ? new Date(p.maturity_date).toLocaleDateString('en-US', {
                                                            year: 'numeric',
                                                            month: 'long',
                                                            day: 'numeric'
                                                        })
                                                        : '--'}
                                                </td>
                                                <td class="px-4 py-2 border">₱${parseFloat(p.amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                                <td class="px-4 py-2 border">${p.interest_rate ? `${p.interest_rate}%` : '--'}</td>
                                                <td class="px-4 py-2 border">${p.interest_net ? `${p.interest_net}%` : '--'}</td>
                                                <td class="px-4 py-2 border">${p.no_of_days ? p.no_of_days : '--'}</td>
                                                <td class="px-4 py-2 border">₱${p.interest_income ? parseFloat(p.interest_income).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '--'}</td>
                                                <td class="px-4 py-2 border">
                                                    ₱${p.maturity_value ? parseFloat(p.maturity_value).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '--'}`;

                                        // Use created_at for determining latest placement
                                        if (p.created_at === latestPlacement
                                            .created_at) {
                                            const isCredit = p.status && p
                                                .status.toLowerCase() ===
                                                'credit';
                                            const rerollFunction =
                                                isCredit ?
                                                'handleCreditReroll' :
                                                'handleReroll';
                                            const rerollFunction2 =
                                                isCredit ? 'reroll' :
                                                'view-placement';
                                            const buttonLabel = isCredit ?
                                                'Reroll' : 'Action';

                                            html += `<button x-data
                                                    @click.prevent="$dispatch('open-modal', '${rerollFunction2}');"
                                                    onclick="${rerollFunction}(
                                                        ${p.id},
                                                        ${p.company_id},
                                                        ${p.bank_account_id},
                                                        '${company.company}',
                                                        '${bankGroup.bank}',
                                                        '${account.account_name}',
                                                        '${p.placement_date}',
                                                        '${p.maturity_date}',
                                                        '₱${parseFloat(p.amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}',
                                                        '${p.interest_rate ? `${p.interest_rate}%` : '--'}',
                                                        '${p.interest_net ? `${p.interest_net}%` : '--'}',
                                                        '${p.no_of_days ? p.no_of_days : '--'}',
                                                        '₱${p.interest_income ? parseFloat(p.interest_income).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '--'}',
                                                        '₱${p.maturity_value ? parseFloat(p.maturity_value).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '--'}'
                                                    )"
                                                    class="ml-2 inline-block px-2 py-1 bg-black text-white text-xs rounded hover:bg-gray-800 transition">
                                                    ${buttonLabel}
                                                </button>`;
                                        }

                                        html += `</td></tr>`;
                                    });

                                }
                                html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>`;
                            });

                            html += `</div>`;
                        });
                    } else {
                        html +=
                            `<p class="text-sm text-gray-500 italic">Currently there is no placement in this company.</p>`;
                    }
                    html += `</div>`;
                });
                navHtml += '</ul></div>';
                $('#sidebar-nav').html(navHtml);
                $('#placement-wrapper').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Failed to load placement data:', error);
                $('#placement-wrapper').html('<p class="text-red-500">Failed to load data.</p>');
            }
        });
    }
    let getPlacementDateCreditProceed;

    function handleCreditReroll(id, company_id, bank_account_id, company, bank, accountName, placementDate,
        maturityDate,
        amount, interestRate,
        interestNet,
        noOfDays, income, maturity_value) {
        getPlacementDateCreditProceed = placementDate;
        const dateInput = document.getElementById('reroll-placement_date');
        const dateInputMaturity = document.getElementById('reroll-maturity_date');
        const dateInputCredit = document.getElementById('credit-placement-credit-date');

        // Format the placementDate to YYYY-MM-DD
        const formattedCreditDate = placementDate.split('T')[0]; // Simpler than new Date().toISOString()

        // Reusable helper function
        function setDateValueAndMin(input, date) {
            if (input) {
                input.value = date;
                input.min = date;
            }
        }

        // Apply to all relevant inputs
        setDateValueAndMin(dateInput, formattedCreditDate);
        setDateValueAndMin(dateInputCredit, formattedCreditDate);
        // Optional: uncomment to apply min to maturity date only
        // dateInputMaturity.min = formattedCreditDate;


        let convertMaturityDate = new Date(maturityDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
        let convertPlacementDate = new Date(placementDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        getGlobalId = id;
        companyId = company_id;
        bankAccountId = bank_account_id;
        // Reroll modal fields
        let cleanAmount = amount.replace(/[^0-9.]/g, '');
        $('#reroll-placement-company').html(company);
        $('#reroll-placement-bank').html(bank);
        $('#reroll-placement-account').html(accountName);
        $('#reroll-amount').val(cleanAmount);
        let formattedDate = maturityDate.split('T')[0];
        $('#reroll-placement_date').val(formattedDate);
    }

    function handleReroll(id, company_id, bank_account_id, company, bank, accountName, placementDate, maturityDate,
        amount, interestRate,
        interestNet,
        noOfDays, income, maturity_value) {
        getPlacementDateCreditProceed = placementDate;

        const dateInput = document.getElementById('reroll-placement_date');
        const dateInputMaturity = document.getElementById('reroll-maturity_date');
        // Format the placementDate to YYYY-MM-DD
        const formattedCreditDate = new Date(placementDate).toISOString().split('T')[0];
        // Set value and minimum allowed date
        dateInput.value = formattedCreditDate;
        dateInput.min = formattedCreditDate;
        dateInputMaturity.value = formattedCreditDate;
        dateInputMaturity.min = formattedCreditDate;

        let convertMaturityDate = new Date(maturityDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
        let convertPlacementDate = new Date(placementDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        let currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        getGlobalId = id;
        companyId = company_id;
        bankAccountId = bank_account_id;
        // Fill modal fields
        document.getElementById('placement-company').textContent = company;
        document.getElementById('placement-bank').textContent = bank;
        document.getElementById('placement-account').textContent = accountName;
        document.getElementById('placement-date').textContent = convertPlacementDate;
        document.getElementById('maturity-date').textContent = convertMaturityDate;
        document.getElementById('placement-amount').textContent = amount;
        document.getElementById('interest-rate').textContent = interestRate;
        document.getElementById('net-interest').textContent = interestNet;
        document.getElementById('no-of-days').textContent = noOfDays;
        document.getElementById('interest-income').textContent = income;
        document.getElementById('maturity-value').textContent = maturity_value;
        // Reroll modal fields
        let cleanAmount = amount.replace(/[^0-9.]/g, '');
        let cleanIncome = income.replace(/[^0-9.]/g, '');
        let getMaturityValue = parseFloat(cleanAmount) + parseFloat(cleanIncome);
        getPlacementBalance = convertMaturityDate == currentDate ? getMaturityValue : cleanAmount;
        $('#credit-amount').val(parseFloat(getPlacementBalance));
        $('#reroll-placement-company').html(company);
        $('#reroll-placement-bank').html(bank);
        $('#reroll-placement-account').html(accountName);
        $('#reroll-amount').val(getMaturityValue);
        let formattedDate = maturityDate.split('T')[0];
        $('#reroll-placement_date').val(formattedDate);
        // Credit modal fields
        $('#credit-placement-company').html(company);
        $('#credit-placement-bank').html(bank);
        $('#credit-placement-account').html(accountName);
        $('#credit-placement-date').html(convertPlacementDate);
        $('#credit-maturity-date').html(convertMaturityDate);
        $('#credit-placement-amount').html(amount);
        $('#credit-interest-rate').html(interestRate);
        $('#credit-net-interest').html(interestNet);
        $('#credit-no-of-days').html(noOfDays);
        $('#credit-interest-income').html(income);
        $('#credit-matury-value').html(maturity_value);
    }
</script>
