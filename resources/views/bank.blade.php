<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bank') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Header Section: Title + Add Button -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">List of Bank</h3>
                    <button x-data @click.prevent="$dispatch('open-modal', 'add-bank')"
                        class="bg-black-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-black-700">
                        + Add Bank
                    </button>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 BankList">
                </div>
            </div>
        </div>
    </div>

    {{-- Add bank --}}
    <x-modal name="add-bank" focusable>
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Bank Information') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update bank details, including bank name, branch, and account information.') }}
                </p>
            </header>

            {{-- <div class="mt-3">
                <x-input-label for="company" :value="__('Company')" />
                <x-text-select id="company" name="company" class="mt-1 block w-full" required>
                    <option value="" disabled selected>Select a company</option>
                </x-text-select>

            </div> --}}

            <div class="mt-3">
                <x-input-label for="bank" :value="__('Bank')" />
                <x-text-input id="bank" name="bank" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="bank" />
            </div>

            <div class="mt-3">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-select id="address" name="address" class="mt-1 block w-full" required>
                    <option value="" disabled selected>Select an address</option>
                </x-text-select>

            </div>

            <div class="mt-3">
                <x-input-label for="contact" :value="__('Contact')" />
                <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="contact" />
            </div>

            <x-primary-button class="mt-3" onclick="save();">{{ __('Save') }}</x-primary-button>
        </div>
    </x-modal>

    {{-- edit bank --}}
    <x-modal name="edit-bank" focusable maxWidth="5xl">
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Edit Bank Information') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update bank details, including name, industry, and contact information.') }}
                </p>
            </header>

            {{-- <div class="mt-3">
                <x-input-label for="editCompany" :value="__('Company')" />
                <x-text-select id="editCompany" name="editCompany" class="mt-1 block w-full" required>
                    <option value="" disabled selected>Select an company</option>
                </x-text-select>
            </div> --}}
            <div class="mt-3">
                <x-input-label for="editBank" :value="__('Bank')" />
                <x-text-input id="editBank" name="editBank" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="editBank" />
            </div>

            <div class="mt-3">
                <x-input-label for="editAddress" :value="__('Address')" />
                <x-text-input id="editAddress" name="editAddress" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="editAddress" />
            </div>

            <div class="mt-3">
                <x-input-label for="contact" :value="__('Contact')" />
                <x-text-input id="editContact" name="editContact" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="editContact" />
            </div>

            <x-primary-button class="mt-3" onclick="update();">{{ __('Update') }}</x-primary-button>
            {{-- Account Numbers Section --}}
            <div class="mt-5">
                <h3 class="text-md font-medium text-gray-900">{{ __('Account Numbers') }}</h3>
                <table class="w-full mt-2 border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">#</th>
                            <th class="border p-2">Account Name</th>
                            <th class="border p-2">Account Number</th>
                            <th class="border p-2">Balance</th>
                            <th class="border p-2">Balance Type</th>
                            <th class="border p-2">Company</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="accountNumbersTable">
                    </tbody>
                </table>

                {{-- Input for adding a new account number --}}
                <div class="mt-3 flex gap-2">
                    <x-text-input id="newAccountName" name="newAccountName" type="text" class="block w-full"
                        placeholder="Enter account name" />
                    <x-text-input id="newAccountNumber" name="newAccountNumber" type="text" class="block w-full"
                        placeholder="Enter account number" />
                    <x-text-input id="newBalance" name="newBalance" type="text" class="block w-full"
                        placeholder="Enter balance" />

                    <select id="newBalanceType" name="newBalanceType"
                        class="block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="" disabled selected>Select balance type</option>
                        <option value="Deposit">Deposit</option>
                        <option value="Operating Placements">Operating Placements</option>
                        <option value="Non-Operating Placements">Non-Operating Placements</option>
                    </select>

                    <select id="newCompany" name="newCompany" class="block w-full border-gray-300 rounded-md shadow-sm">
                    </select>

                    <x-primary-button onclick="addAccountNumber();">{{ __('Add Account Number') }}</x-primary-button>
                </div>
            </div>
        </div>
    </x-modal>

</x-app-layout>
<script>
    getCities();
    getBanks();
    let globalID;
    getCompanies();

    function getCompanies() {
        $('.newCompany').html('');
        // $('.editCompany').html('');
        $.ajax({
            url: @json(route('company.show')), // Ensure proper URL formatting
            type: 'GET',
            dataType: 'json', // Specify JSON response type
            success: function (response) {
                $.each(response, (key, value) => {
                    $('#newCompany').append(
                        `<option value="${value.id}">${value.company}</option>`);
                    // $('#editCompany').append(
                    //     `<option value="${value.id}">${value.company}</option>`);
                });
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching companies:", error);
            }
        });
    }
    const balanceInput = document.getElementById('newBalance');

    balanceInput.addEventListener('input', function (e) {
        // Remove all non-digit characters first
        let value = this.value.replace(/\D/g, '');

        // Format with commas
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
    function addAccountNumber() {
        let newCompany = $('#newCompany').val();
        let newAccountName = $('#newAccountName').val();
        let newAccountNumber = $('#newAccountNumber').val();
        let newBalance = $('#newBalance').val().replace(/,/g, '');;
        let newBalanceType = $('#newBalanceType').val();
        let newCompanyText = $('#newCompany option:selected').text();
        $.ajax({
            url: "{{ route('bankAccounts.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                banks_id: globalID,
                company_id: newCompany,
                account_name: newAccountName,
                account_number: newAccountNumber,
                balance: newBalance,
                balance_type: $('#newBalanceType').val(),
            },

            success: function (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Account Number Successfully Added",
                    showConfirmButton: false,
                    timer: 5000,
                    width: "500px"
                });

                $('#accountNumbersTable').append(`<tr>
                    <td class="border p-2 bg-green-200"></td>
                    <td class="border p-2">${newAccountName}</td>
                    <td class="border p-2">${newAccountNumber}</td>
                    <td class="border p-2">${new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', minimumFractionDigits: 2 }).format(newBalance)}</td>
                    <td class="border p-2">${newBalanceType}</td>
                    <td class="border p-2">${newCompanyText}</td>
                    <td class="border p-2">
                        <button onclick="deleteAccountNumber('${response.id}')" class="text-red-500">
                            Delete
                        </button>
                    </td>
                </tr>`);
            },

            error: function (xhr) {

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    if (errors.account_name) {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: errors.account_name[0]
                        });
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong",
                        text: "Please try again."
                    });
                }
            }
        });
    }

    function deleteAccountNumber(id) {
        $.ajax({
            url: `/bankAccounts/${id}`, // Laravel route to delete
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // CSRF token for Laravel
            },
            success: function (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Deleted Successfully",
                    showConfirmButton: false,
                    timer: 5000,
                    width: "500px"
                });
            }
        });

        // Find the button that was clicked and remove its parent <tr>
        $(`button[onclick="deleteAccountNumber('${id}')"]`).closest('tr').remove();
    }

    function getCities() {
        $('#address').html("");
        $('#editAddress').html("");
        $('#address').append(`<option value="" disabled selected>Select an address</option>`);
        $('#editAddress').append(`<option value="" disabled selected>Select an address</option>`);
        $.ajax({
            url: "{{ asset('json/cities.json') }}", // Corrected URL helper
            type: 'GET', // Changed from POST to GET
            success: function (response) {
                $.each(response, (key, value) => {
                    $('#address').append(`<option value="${value.name}">${value.name}</option>`);
                    $('#editAddress').append(
                        `<option value="${value.name}">${value.name}</option>`);
                });
            }
        });
    }

    // save
    function save() {
        // let company = $('#company').val();
        let bank = $('#bank').val();
        let address = $('#address').val();
        let contact = $('#contact').val();
        $.ajax({
            url: "{{ route('bank.store') }}", // Define the route to handle storing employees
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Include CSRF token
                // company_id: company,
                bank: bank,
                address: address,
                contact: contact,
            }, // Serialize the form data
            success: function (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Bank Added Successfully",
                    showConfirmButton: false,
                    timer: 5000,
                    width: "500px"
                });
                BankListDetails(response.id, bank, address, contact);
            }
        });
    }

    function getBanks() {
        $('.BankList').html('');
        $.ajax({
            url: @json(route('bank.show')), // Ensure proper URL formatting
            type: 'GET',
            dataType: 'json', // Specify JSON response type
            success: function (response) {
                if (response.length === 0) {
                    $('.BankList').html(
                        '<div class="alert alert-info">There are currently no bank.</div>');
                    return;
                }
                $.each(response, (key, value) => {
                    BankListDetails(value.id, value.bank, value.address, value.contact);
                });
            }
        });
    }

    function BankListDetails(id, bank, address, contact) {
        $('.BankList').append(`
            <div  x-data @click.prevent="$dispatch('open-modal', 'edit-bank')" class="bg-gray-100 p-4 rounded-lg shadow-sm border border-gray-200" onclick="editBank(${id});">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-300 flex items-center justify-center rounded-full">
                        <span class="text-gray-600 text-sm font-semibold">${getAcronym(bank)}</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">${bank}</h4>
                        <p class="text-sm text-gray-600">Industry: Bank</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-gray-700 text-sm">📍 ${address}, Philippines</p>
                    <p class="text-gray-700 text-sm">📞 ${contact}</p>
                </div>
            </div>`);
    }

    function editBank(id) {
        globalID = id;
        clearValues();
        getCities();
        setTimeout(() => {
            $.ajax({
                url: `{{ route('bank.edit', ['id' => ':id']) }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Populate modal fields with employee data
                    $('#editCompany').val(data.company_id);
                    $('#editBank').val(data.bank);
                    $('#editAddress').val(data.address);
                    $('#editContact').val(data.contact);
                }
            });
        }, 1);
        getAccountNameDetails(id);

    }
    let bankAccountCount = 0;

    function getAccountNameDetails(id) {
        bankAccountCount = 0
        $.ajax({
            url: `{{ route('bankAccounts.show', ['id' => ':id']) }}`.replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log(data);

                if (data.length === 0) {
                    $('#accountNumbersTable').append(`
                    <tr>
                        <td class="border p-2 text-center" colspan="4">Currently, there is no Account Number</td>
                    </tr>`);
                } else {
                    $.each(data, (key, value) => {
                        bankAccountCount++;
                        $('#accountNumbersTable').append(`<tr>
                            <td class="border p-2">${bankAccountCount}</td>
                            <td class="border p-2">${value.account_name}</td>
                            <td class="border p-2">${value.account_number}</td>
                            <td class="border p-2">${new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', minimumFractionDigits: 2 }).format(value.balance)}</td>
                            <td class="border p-2">${value.balance_type}</td>
                            <td class="border p-2">${value.company_name}</td>
                            <td class="border p-2"><button onclick="deleteAccountNumber('${value.id}')"
                                    class="text-red-500">Delete</button></td>
                        </tr>`)
                    })

                }
            }
        });
    }

    function update() {
        let editAddress = $('#editAddress').val();
        let editContact = $('#editContact').val();
        // AJAX request to update the employee data
        $.ajax({
            url: `{{ route('bank.update', ['id' => ':id']) }}`.replace(':id', globalID),
            type: 'PUT', // Update method
            dataType: 'json',
            data: {
                address: editAddress,
                contact: editContact,
                _token: "{{ csrf_token() }}" // Include CSRF token
            },
            success: function (response) {
                getBanks();
                // Close the modal
                window.dispatchEvent(new Event('close-modal'));
            }
        });
    }

    function getAcronym(phrase) {
        // Split the phrase into words
        let words = phrase.split(" ");
        let acronym = "";

        words.forEach(word => {
            // Match the first vowel and its preceding consonant (i.e., first syllable start)
            let match = word.match(/^[^aeiouAEIOU]*[aeiouAEIOU]?/);
            if (match) {
                acronym += match[0].charAt(0).toUpperCase(); // Take the first letter
            }
        });

        return acronym;
    }

    function clearValues() {
        $('#editBank').html("");
        $('#editAddress').html("");
        $('#editContact').html("");

        $('#newAccountName').val("");
        $('#newAccountNumber').val("");
        $('#balance').val("");

        $('#accountNumbersTable').html("");
    }
</script>