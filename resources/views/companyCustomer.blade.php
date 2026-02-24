<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Header Section: Title + Add Button -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">List of Customer</h3>
                    <button x-data @click.prevent="$dispatch('open-modal', 'add-company')"
                        class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Customer
                    </button>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 CompanyList">
                </div>
            </div>
        </div>
    </div>

    {{-- Add barangay --}}
    <x-modal name="add-company" focusable>
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Company Information') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update company details, including name, industry, and contact information.') }}
                </p>
            </header>


            <div class="mt-3">
                <x-input-label for="company" :value="__('Company')" />
                <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="company" />
            </div>

            <div class="mt-3">
                <x-input-label for="address" :value="__('Address')" />
                {{-- <x-text-select id="address" name="address" class="mt-1 block w-full" required>
                    <option value="" disabled selected>Select an address</option>
                </x-text-select> --}}
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="address" />

            </div>

            <div class="mt-3">
                <x-input-label for="contact" :value="__('Contact')" />
                <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="contact" />
            </div>

            <x-primary-button class="mt-3" onclick="save();">{{ __('Save') }}</x-primary-button>
        </div>
    </x-modal>


    {{-- edit barangay --}}
    <x-modal name="edit-company" focusable>
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Edit Company Information') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update company details, including name, industry, and contact information.') }}
                </p>
            </header>


            <div class="mt-3">
                <x-input-label for="company" :value="__('Company')" />
                <x-text-input id="editCompany" name="editCompany" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="editCompany" />
            </div>

            <div class="mt-3">
                <x-input-label for="editAddress" :value="__('Address')" />
                {{-- <x-text-select id="editAddress" name="editAddress" class="mt-1 block w-full" required>
                </x-text-select> --}}
                <x-text-input id="editAddress" name="editAddress" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="editAddress" />

            </div>

            <div class="mt-3">
                <x-input-label for="contact" :value="__('Contact')" />
                <x-text-input id="editContact" name="editContact" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="editContact" />
            </div>

            <x-primary-button class="mt-3" onclick="update();">{{ __('Update') }}</x-primary-button>
        </div>
    </x-modal>

</x-app-layout>

<script>
    getCities();
    getCompanies();
    let globalID;

    // save
    function save() {
        let company = $('#company').val();
        let address = $('#address').val();
        let contact = $('#contact').val();
        $.ajax({
            url: "{{ route('company.store') }}", // Define the route to handle storing employees
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Include CSRF token
                company: company,
                address: address,
                contact: contact,
                type: 'customer',
            }, // Serialize the form data
            success: function(response) {
                CompanyListDetails(response.id, company, address, contact);
            }
        });
    }

    // edit
    function editCompany(id) {
        globalID = id;
        clearValues();
        getCities();
        setTimeout(() => {
            $.ajax({
                url: `{{ route('company.edit', ['id' => ':id']) }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    // Populate modal fields with employee data
                    $('#editCompany').val(data.company);
                    $('#editAddress').val(data.address);
                    $('#editContact').val(data.contact);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching company data:', error);
                }
            });

        }, 1);
    }

    function update() {
        let editAddress = $('#editAddress').val();
        let editContact = $('#editContact').val();
        // AJAX request to update the employee data
        $.ajax({
            url: `{{ route('company.update', ['id' => ':id']) }}`.replace(':id', globalID),
            type: 'PUT', // Update method
            dataType: 'json',
            data: {
                address: editAddress,
                contact: editContact,
                _token: "{{ csrf_token() }}" // Include CSRF token
            },
            success: function(response) {
                console.log(response);
                getCompanies();
                // Close the modal
                window.dispatchEvent(new Event('close-modal'));
            }
        });
    }

    function getCities() {
        $('#address').html("");
        $('#editAddress').html("");
        $('#address').append(`<option value="" disabled selected>Select an address</option>`);
        $('#editAddress').append(`<option value="" disabled selected>Select an address</option>`);
        $.ajax({
            url: "{{ asset('json/cities.json') }}", // Corrected URL helper
            type: 'GET', // Changed from POST to GET
            success: function(response) {
                $.each(response, (key, value) => {
                    $('#address').append(`<option value="${value.name}">${value.name}</option>`);
                    $('#editAddress').append(
                        `<option value="${value.name}">${value.name}</option>`);
                });
            }
        });
    }

    function CompanyListDetails(id, company, address, contact) {
        $('.CompanyList').append(`
            <div  x-data @click.prevent="$dispatch('open-modal', 'edit-company')" class="bg-gray-100 p-4 rounded-lg shadow-sm border border-gray-200" onclick="editCompany(${id});">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-300 flex items-center justify-center rounded-full">
                        <span class="text-gray-600 text-sm font-semibold">${getAcronym(company)}</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">${company}</h4>
                        <p class="text-sm text-gray-600">Customer Company</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-gray-700 text-sm">📍 ${address}, Philippines</p>
                    <p class="text-gray-700 text-sm">📞 ${contact}</p>
                </div>
            </div>`);
    }

    function getCompanies() {
        $('.CompanyList').html('');
        $.ajax({
            url: @json(route('company.showCustomer')), // Ensure proper URL formatting
            type: 'GET',
            dataType: 'json', // Specify JSON response type
            success: function(response) {
                if (response.length === 0) {
                    $('.CompanyList').html(
                        '<div class="alert alert-info">There are currently no customers.</div>');
                    return;
                }
                $.each(response, (key, value) => {
                    CompanyListDetails(value.id, value.company, value.address, value.contact);
                });
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching companies:", error);
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
        $('#editCompany').html("");
        $('#editAddress').html("");
        $('#editContact').html("");
    }
</script>
