<x-app-layout>
    <style>
        .invalid-feedback {
            color: rgb(150, 3, 3);
            font-size: 0.875rem;
            display: none;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Daily') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Manage the daily treasury operations efficiently with this system. Track fund collections, process payments, handle fund transfers, and ensure accurate reconciliation of financial transactions. The system streamlines cash flow monitoring, ensuring transparency and accuracy in financial reporting.') }}
                    </p>
                    <!-- Select Report Type -->
                    <label for="report-type" class="block text-sm font-medium text-gray-700 text-center mt-4">Select
                        Report Type</label>
                    <div class="flex justify-center mt-2">
                        <select id="report-type" name="report-type" onchange="typeOfReport(this.value);" ;
                            class="w-[300px] py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select ...</option>
                            <option value="Fund Transfer">Fund Transfer</option>
                            <option value="Collection" class="text-green">Collection</option>
                            <option value="Disbursement/Cash Advance" class="bg-red">Disbursement/Cash Advance</option>
                            <option value="Liquidation / Reimbursement" class="bg-red">Liquidation / Reimbursement
                            </option>
                            <option value="Management Fees">Management Fees</option>
                            <option value="Interest Income">Interest Income</option>
                            <option value="Placement Maturity">Placement Maturity</option>
                            <option value="Investment Maturity">Investment Maturity</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="inputsDetail mb-6" style="margin-bottom: 30px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Bank') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('This section allows you to manage and select your preferred bank for treasury operations, ensuring efficient financial transactions and services.') }}
                    </p>

                    <div class="bank">
                        <div class="flex flex-wrap gap-4 mt-2">
                            <div class="w-full">
                                <label for="flow_type" class="block text-sm font-medium text-gray-700">Flow
                                    Type</label>
                                <select id="flow_type" name="flow_type" onchange="getVendorOrCustomer(this.value);"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select ...</option>
                                    <option value="Inflow">Inflow</option>
                                    <option value="Outflow">Outflow</option>
                                </select>
                                <div class="invalid-feedback">test</div>
                            </div>
                            <div class="w-full">
                                <label for="companies" class="block text-sm font-medium text-gray-700">Companies</label>
                                <select id="companies" name="companies"
                                    onchange="getBanksSelection(this.value, 'bank', 'account_id');"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="w-full">
                                <label for="bank" class="block text-sm font-medium text-gray-700">Bank</label>
                                <select id="bank" name="bank" onchange="getAccountDetails(this.value, 'account_id');"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option>Select Bank...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="w-full">
                                <label for="account_id" class="block text-sm font-medium text-gray-700">Account
                                    Name</label>
                                <select id="account_id" name="account_id"
                                    onchange="getAccountBalance(this.value, 'account_balance');"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="w-full flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-700">Balance:</span>
                                <span id="account_balance" class="text-green-600 font-semibold">₱0</span>
                            </div>
                        </div>

                    </div>
                    <div class="FundTransferSection">
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6  mt-2">
                            <div class="mb-2">
                                <div class="w-full">
                                    <label for="fundTransferFromcompanies"
                                        class="block text-sm font-medium text-gray-700">Companies</label>
                                    <select id="fundTransferFromcompanies" name="fundTransferFromcompanies"
                                        onchange="getBanksSelection(this.value, 'FundTransferfromBank', 'FundTransferFromAccountId');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="w-full mt-3">
                                    <label for="FundTransferfromBank"
                                        class="block text-sm font-medium text-gray-700">From Bank</label>
                                    <select id="FundTransferfromBank" name="FundTransferfromBank"
                                        onchange="getAccountDetails(this.value, 'FundTransferFromAccountId');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select ...</option>
                                    </select>
                                </div>
                                <div class="w-full mt-3">
                                    <label for="FundTransferFromAccountId"
                                        class="block text-sm font-medium text-gray-700">Account
                                        Name</label>
                                    <select id="FundTransferFromAccountId" name="FundTransferFromAccountId"
                                        onchange="getAccountBalance(this.value, 'from_account_balance');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="w-full flex items-center gap-2 mt-3">
                                    <span class="text-sm font-medium text-gray-700">Balance:</span>
                                    <span id="from_account_balance" class="text-green-600 font-semibold">₱0</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="w-full">
                                    <label for="fundTransferTocompanies"
                                        class="block text-sm font-medium text-gray-700">Companies</label>
                                    <select id="fundTransferTocompanies" name="fundTransferTocompanies"
                                        onchange="getBanksSelection(this.value, 'FundTransfertoBank', 'FundTransferToAccountId');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="w-full mt-3">
                                    <label for="FundTransfertoBank" class="block text-sm font-medium text-gray-700">To
                                        Bank</label>
                                    <select id="FundTransfertoBank" name="FundTransfertoBank"
                                        onchange="getAccountDetails(this.value, 'FundTransferToAccountId');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select ...</option>
                                    </select>
                                </div>
                                <div class="w-full mt-3">
                                    <label for="FundTransferToAccountId"
                                        class="block text-sm font-medium text-gray-700">Account
                                        Name</label>
                                    <select id="FundTransferToAccountId" name="FundTransferToAccountId"
                                        onchange="getAccountBalance(this.value, 'to_account_balance');"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="w-full flex items-center gap-2 mt-3">
                                    <span class="text-sm font-medium text-gray-700">Balance:</span>
                                    <span id="to_account_balance" class="text-green-600 font-semibold">₱0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="inputsDetail">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6  mt-2">
                            <div class="CollectionSection">
                                <!-- Radio Buttons -->
                                <div class="mb-2 text-center">
                                    <div class="flex justify-center gap-6">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="entity_type" value="customer"
                                                class="form-radio text-indigo-600" checked>
                                            <span class="ml-2 text-sm text-gray-700">Customer</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="entity_type" value="vendor"
                                                class="form-radio text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700">Vendor</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Select Dropdown -->
                                <div class="mb-2">
                                    <label for="vendorcustomer"
                                        class="block text-sm font-medium text-gray-700 vendorcustomerlabel">Customer</label>
                                    <select id="vendorcustomer" name="vendorcustomer"
                                        class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 vendorcustomer">
                                    </select>
                                    <div class="invalid-feedback text-red-500"></div>
                                </div>
                            </div>

                            <div class="mb-2 inflow_type mt-4">
                                <label for="inflow_type" class="block text-sm font-medium text-gray-700">Inflow
                                    Type</label>
                                <select id="inflow_type" name="inflow_type" onchange="getInflowName(this.value);"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="" disabled selected>Select ... </option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-2 mt-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" id="amount" name="amount"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-2 inflow_name mt-4">
                                <label for="inflow_name" class="block text-sm font-medium text-gray-700">Inflow
                                    Name</label>
                                <select id="inflow_name" name="inflow_name"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="" disabled selected>Select ... </option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-2 invoice mt-4">
                                <label for="invoice"
                                    class="block text-sm font-medium text-gray-700 invoice">Invoice</label>
                                <input type="text" id="invoice" name="invoice"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-2 mt-4">
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" id="date" name="date"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-2">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" cols="30" rows="5"
                                    class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 mt-6 w-full justify-end">
                            <button onclick="save();"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let typeOfReports;
        $('.inputsDetail').hide();
        hideAllSection();
        getCompanies();

        const today = new Date().toISOString().split('T')[0];
        $('#date').val(today);

        const reportParam = new URLSearchParams(window.location.search).get('report');
        if (reportParam) {
            const $select = $('#report-type');
            const $opt = $select.find('option').filter(function () { return $(this).val() === reportParam; });
            if ($opt.length) {
                $select.val(reportParam);
                typeOfReport(reportParam);
            }
        }

        $('input[name="entity_type"]').change(function () {
            const selectedType = $(this).val(); // "customer" or "vendor"

            // Capitalize first letter for label
            const labelText = selectedType.charAt(0).toUpperCase() + selectedType.slice(1);

            if (labelText == 'Customer') {
                getVendorOrCustomer('Inflow');
            } else {
                getVendorOrCustomer('Outflow');
            }
        });

        function getInflowName(id) {
            $.ajax({
                url: `{{ route('inflows.show', ['id' => ':id']) }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#inflow_name').html('');
                    $('#inflow_name').append(`<option value="" disabled selected>Select ...</option>`);
                    $.each(response, (key, value) => {
                        $('#inflow_name').append(
                            `<option value="${value.inflow}">${value.inflow}</option>`);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching inflow names:", error);
                }
            });
        }

        function getVendorOrCustomer(val) {
            $('.vendorcustomer').html('');
            $('#vendorcustomer').append(`<option value="" disabled selected>Select ...</option>`);
            const customerUrl = @json(route('company.showCustomer'));
            const vendorUrl = @json(route('company.showVendor'));

            let url = val === 'Inflow' ? customerUrl : vendorUrl;
            // Automatically check the appropriate radio button
            if (val === 'Inflow') {
                $('input[name="entity_type"][value="customer"]').prop('checked', true);
                $('.vendorcustomerlabel').text('Customer'); // Optional: update label
            } else {
                $('input[name="entity_type"][value="vendor"]').prop('checked', true);
                $('.vendorcustomerlabel').text('Vendor'); // Optional: update label
            }
            val === 'Inflow' ? $('.vendorcustomerlabel').html('Customer') : $('.vendorcustomerlabel').html('Vendor');
            $.ajax({
                url: url, // Ensure proper URL formatting
                type: 'GET',
                dataType: 'json', // Specify JSON response type
                success: function (response) {
                    if (response.length === 0) {
                        $('#vendorcustomer').append(
                            `<option value="" disabled selected>Currently No data ...</option>`);
                        return;
                    }
                    $.each(response, (key, value) => {
                        $('#vendorcustomer').append(
                            `<option value="${value.company}">${value.company}</option>`);
                    });
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching companies:", error);
                }
            });
        }

        function hideAllSection() {
            $('.CollectionSection').hide();
            $('.FundTransferSection').hide();
        }

        function getCompanies() {
            $('.companies').html('');
            $('#companies').append(`<option value="" disabled selected>Select an company</option>`);
            $('.fundTransferFromcompanies').html('');
            $('#fundTransferFromcompanies').append(`<option value="" disabled selected>Select an company</option>`);
            $('.fundTransferTocompanies').html('');
            $('#fundTransferTocompanies').append(`<option value="" disabled selected>Select an company</option>`);
            $.ajax({
                url: @json(route('company.show')), // Ensure proper URL formatting
                type: 'GET',
                dataType: 'json', // Specify JSON response type
                success: function (response) {
                    $.each(response, (key, value) => {
                        $('#companies').append(`<option value="${value.id}">${value.company}</option>`);
                        $('#fundTransferFromcompanies').append(
                            `<option value="${value.id}">${value.company}</option>`);
                        $('#fundTransferTocompanies').append(
                            `<option value="${value.id}">${value.company}</option>`);
                    });
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching companies:", error);
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
                success: function (response) {
                    console.log(response);
                    $.each(response, (key, value) => {
                        $('#' + bank).append(
                            `<option value="${value.id}">${value.bank}</option>`);

                    });
                }
            });
        }
        function clearAllInputs() {
            // Text, number, date, textarea
            $('input, textarea').val('');

            // Select - reset to first option
            $('select').val('');

            // Checkboxes and radio buttons
            $('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        }
        function save() {
            let dataValues = {};
            let dataValuesFrom = {};
            let dataValuesTo = {};
            if (typeOfReports === 'Fund Transfer') {
                if (formValidation('fundTransferFromcompanies', 'FundTransferfromBank', 'FundTransferFromAccountId',
                    'fundTransferTocompanies', 'FundTransfertoBank', 'FundTransferToAccountId', 'amount', 'date',
                    'description')) {

                    const dataArray = [{
                        banks_id: $('#FundTransferfromBank').val(),
                        bank_accounts_id: $('#FundTransferFromAccountId').val(),
                        customer: $('#vendorcustomer').val(),
                        invoice: $('#invoice').val(),
                        amount: $('#amount').val(),
                        company: $('#fundTransferFromcompanies').val(),
                        date: $('#date').val(),
                        description: $('#description').val(),
                        flow_type: "Outflow",
                        inflow_type: $('#inflow_type').val(),
                        inflow_name: $('#inflow_name').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    {
                        banks_id: $('#FundTransfertoBank').val(),
                        bank_accounts_id: $('#FundTransferToAccountId').val(),
                        customer: $('#vendorcustomer').val(),
                        invoice: $('#invoice').val(),
                        amount: $('#amount').val(),
                        company: $('#fundTransferTocompanies').val(),
                        date: $('#date').val(),
                        description: $('#description').val(),
                        flow_type: "Inflow",
                        inflow_type: $('#inflow_type').val(),
                        inflow_name: $('#inflow_name').val(),
                        _token: "{{ csrf_token() }}"
                    }
                    ];

                    if (Object.keys(dataArray).length !== 0) {
                        for (let i = 0; i < dataArray.length; i++) {
                            console.log(dataArray[i]);
                            // You can also use jQuery here, e.g., appending to the DOM
                            // $("body").append("<p>Loop " + i + "</p>");

                            const flowType = i === 0 ? 'Outflow' : 'Inflow'; // First is Outflow, second is Inflow
                            const accountId = i === 0 ? $('#FundTransferFromAccountId').val() : $(
                                '#FundTransferToAccountId').val(); // First is Outflow, second is Inflow
                            console.log(accountId);
                            $.ajax({
                                url: `{{ route('daily.store') }}`,
                                type: 'POST',
                                data: dataArray[i],
                                success: function (data) {
                                    console.log('Data saved successfully', data);

                                    $.ajax({
                                        url: `{{ route('bank.AccountController', ['id' => ':id']) }}`
                                            .replace(
                                                ':id',
                                                accountId),
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            amount: $('#amount').val(),
                                            flow_type: flowType,
                                        },
                                        success: function (data) {
                                            let type = flowType; // "inflow" or "outflow"
                                            let sign = type === 'Inflow' ? '+' : '-';
                                            let colorClass = type === 'Inflow' ? 'text-green-600' :
                                                'text-red-600';
                                            if (flowType === 'Inflow') {
                                                $('#to_account_balance').html(
                                                    `₱${Number(data.new_balance).toLocaleString('en-US')} <span class="${colorClass} font-semibold">(${sign} ₱${$('#amount').val()})</span>`
                                                );
                                            } else {
                                                $('#from_account_balance').html(
                                                    `₱${Number(data.new_balance).toLocaleString('en-US')} <span class="${colorClass} font-semibold">(${sign} ₱${$('#amount').val()})</span>`
                                                );
                                                Swal.fire({
                                                    position: "center", // Centers the alert
                                                    icon: data.status,
                                                    title: data.message,
                                                    showConfirmButton: false,
                                                    timer: 1500,
                                                    width: "500px" // Makes the alert smaller
                                                });
                                            }
                                            saveLogs($('#date').val(), accountId, $(
                                                '#amount')
                                                .val(), $('#invoice')
                                                    .val(), $('#description').val(), flowType, $(
                                                        '#vendorcustomer')
                                                        .val(), $('#report-type').val(), $(
                                                            '#inflow_type').val(), $(
                                                                '#inflow_name').val()
                                            );
                                            console.log(data.message);
                                            console.log("New Balance:", data.new_balance);
                                            setTimeout(function () {
                                                clearAllInputs();
                                            }, 2000); // 2000ms = 2 seconds
                                        },
                                        error: function (xhr) {
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
                            });
                        }

                    }
                }
            } else {
                if (formValidation('flow_type', 'invoice', 'companies', 'bank',
                    'account_id', 'vendorcustomer', 'inflow_type', 'amount', 'date', 'description')) {
                    dataValues = {
                        banks_id: $('#bank').val(),
                        bank_accounts_id: $('#account_id').val(),
                        customer: $('#vendorcustomer').val(),
                        invoice: $('#invoice').val(),
                        amount: $('#amount').val(),
                        company: $('#companies').val(),
                        date: $('#date').val(),
                        description: $('#description').val(),
                        flow_type: $('#flow_type').val(),
                        inflow_type: $('#inflow_type').val(),
                        inflow_name: $('#inflow_name').val(),
                        _token: "{{ csrf_token() }}"
                    };
                }

                // Only send AJAX if dataValues is not empty
                if (Object.keys(dataValues).length !== 0) {
                    $.ajax({
                        url: `{{ route('daily.store') }}`,
                        type: 'POST',
                        data: dataValues,
                        success: function (data) {
                            console.log('Data saved successfully', data);

                            $.ajax({
                                url: `{{ route('bank.AccountController', ['id' => ':id']) }}`
                                    .replace(
                                        ':id',
                                        $(
                                            '#account_id').val()),
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    amount: $('#amount').val(),
                                    flow_type: $('#flow_type').val(),
                                },
                                success: function (data) {
                                    let type = $('#flow_type').val(); // "inflow" or "outflow"
                                    let sign = type === 'Inflow' ? '+' : '-';
                                    let colorClass = type === 'Inflow' ? 'text-green-600' :
                                        'text-red-600';
                                    Swal.fire({
                                        position: "center", // Centers the alert
                                        icon: data.status,
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500,
                                        width: "500px" // Makes the alert smaller
                                    });

                                    $('#account_balance').html(
                                        `₱${Number(data.new_balance).toLocaleString('en-US')} <span class="${colorClass} font-semibold">(${sign} ₱${$('#amount').val()})</span>`
                                    );
                                    saveLogs($('#date').val(), $('#account_id').val(), $(
                                        '#amount')
                                        .val(), $('#invoice')
                                            .val(), $('#description').val(), $('#flow_type')
                                                .val(), $('#vendorcustomer')
                                                    .val(), $('#report-type').val(), $('#inflow_type')
                                                        .val(), $(
                                                            '#inflow_name').val()
                                    );
                                    console.log(data.message);
                                    console.log("New Balance:", data.new_balance);
                                    setTimeout(function () {
                                        clearAllInputs();
                                    }, 2000); // 2000ms = 2 seconds
                                },
                                error: function (xhr) {
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
                    });
                } else {
                    console.log('No data to send');
                }
            }
        }

        // saveLogs
        function saveLogs(date, account_name, amount, invoice, description, flow_type, vendorcustomer, report_type,
            inflow_type, inflow_name) {
            $.ajax({
                url: "{{ route('logs.store') }}", // Define the route to handle storing employees
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // Include CSRF token
                    date: date,
                    account_name: account_name,
                    amount: amount,
                    invoice: invoice,
                    description: description,
                    flow_type: flow_type,
                    vendorcustomer: vendorcustomer,
                    performed_by: "{{ Auth::user()->name }}",
                    report_type: report_type,
                    inflow_type: inflow_type,
                    inflow_name: inflow_name,
                }, // Serialize the form data
                success: function (response) {
                    console.log(response);
                }
            });
        }

        function typeOfReport(val) {
            $('#bank').html('');
            $('#account_id').html("");

            $('#FundTransferfromBank').html('');
            $('#FundTransferFromAccountId').html("");

            $('#bank').append(
                `<option value="">Select Bank...</option>`);
            $('#FundTransferfromBank').append(
                `<option value="">Select Bank...</option>`);

            $('#account_balance').html("");
            $('#from_account_balance').html("");
            $('#to_account_balance').html("");
            
            typeOfReports = val;
            if (val == "Fund Transfer") {
                $('.inputsDetail').show();
                $('.bank').hide();
                $('.CollectionSection').hide();
                $('.FundTransferSection').show();
                // $('.invoice').hide();
                $('.invoice').hide();
            } else {
                $('.inputsDetail').show();
                $('.bank').show();
                $('.CollectionSection').show();
                $('.FundTransferSection').hide();
                // $('.invoice').show();
                $('.invoice').show();
            }
        }

        function getAccountBalance(id, account_balance) {
            $.ajax({
                url: `{{ route('bankAccounts.balance', ['id' => ':id']) }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#' + account_balance).html(`₱${Number(data.balance).toLocaleString('en-US')}`);
                }
            });
        }

        function getAccountDetails(id, accountId) {
            $('#' + accountId).html("");
            $.ajax({
                url: `{{ route('bankAccounts.show', ['id' => ':id']) }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
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
        getInflowType();

        function getInflowType() {
            $.ajax({
                url: "{{ route('inflowType.show') }}",
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#inflow_type').html('');
                    $('#inflow_type').append(`<option value="" disabled selected>Select ...</option>`);
                    $.each(response, (key, value) => {
                        $('#inflow_type').append(
                            `<option value="${value.id}">${value.inflow_name}</option>`);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching inflow types:", error);
                }
            })
        }

        function formValidation(...args) {
            let isValidated = true;

            $.each(args, function (i, fieldId) {
                const element = $(`#${fieldId}`);

                if (element.length === 0) {
                    console.warn(`Field with ID '${fieldId}' not found.`);
                    isValidated = false;
                    return;
                }

                const value = element.val();
                if (value === null || value === '') {
                    invalidField(fieldId, 'Field is required.');
                    isValidated = false;
                } else {
                    validField(fieldId);
                }
            });

            return isValidated;
        }

        function invalidField(fieldId, msg) {
            const element = $(`#${fieldId}`);
            element.addClass('is-invalid').removeClass('is-valid');

            // Look for the closest .invalid-feedback
            const feedback = element.closest('div').find('.invalid-feedback');
            feedback.html(msg).show();
        }

        function validField(fieldId) {
            const element = $(`#${fieldId}`);
            element.addClass('is-valid').removeClass('is-invalid');

            const feedback = element.closest('div').find('.invalid-feedback');
            feedback.html('').hide();
        }


        function clearValues() {
            $('#to_account_balance').html("");
            $('#from_account_balance').html("");
            $('input').val('');
            $('select').find('option:first').prop('selected', 'selected');
        }
    </script>
</x-app-layout>