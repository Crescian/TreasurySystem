<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inflow Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Header Section: Title + Add Button -->
                <!-- Header Section: Title + Add Buttons -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">
                        List of Inflow & Outflow Categories
                    </h3>

                    <div class="flex gap-2">
                        <!-- Add Inflow Category -->
                        <button x-data @click.prevent="$dispatch('open-modal', 'add-inflow-category')"
                            class="bg-black-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-black-700">
                            + Add Inflow Category
                        </button>

                        <!-- Add Outflow Category -->
                        <button x-data @click.prevent="$dispatch('open-modal', 'add-outflow-category')"
                            class="bg-black-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-black-700">
                            + Add Outflow Category
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 InflowCategoryList">
                </div>
            </div>
        </div>
    </div>

    {{-- Add inflow category --}}
    <x-modal name="add-inflow-category" focusable>
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
                <x-input-label for="inflow_category" :value="__('Inflow Category')" />
                <x-text-input id="inflow_category" name="inflow_category" type="text" class="mt-1 block w-full" required
                    autofocus autocomplete="inflow_category" />
            </div>

            <x-primary-button class="mt-3" onclick="saveInflowCategory('inflow');">{{ __('Save') }}</x-primary-button>
        </div>
    </x-modal>

    {{-- Add outflow category --}}
    <x-modal name="add-outflow-category" focusable>
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
                <x-input-label for="outflow_category" :value="__('Outflow Category')" />
                <x-text-input id="outflow_category" name="outflow_category" type="text" class="mt-1 block w-full"
                    required autofocus autocomplete="outflow_category" />
            </div>

            <x-primary-button class="mt-3" onclick="saveInflowCategory('outflow');">{{ __('Save') }}</x-primary-button>
        </div>
    </x-modal>

    {{-- edit Inflow List --}}
    <x-modal name="edit-inflow-category" focusable>
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Edit Inflow List Information') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update company details, including name, industry, and contact information.') }}
                </p>
            </header>
            <!-- Inflow Table Section -->
            <div class="mt-4">
                <div class="flex justify-center items-center mb-4">
                    <button x-data @click.prevent="
                            $dispatch('close-modal', 'edit-inflow-category');
                            $dispatch('open-modal', 'add-inflow');
                        " class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Inflow
                    </button>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="w-full table-auto bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100 text-white" style="background: #a50202;">
                            <tr>
                                <th class="text-left px-6 py-3 border-b text-sm font-medium text-gray-700"
                                    style="color: white; font-weight: bold;">Inflow Name
                                </th>
                                <th class="text-center px-6 py-3 border-b text-sm font-medium text-gray-700"
                                    style="color: white; font-weight: bold;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="inflowBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </x-modal>

    {{-- add Inflow List --}}
    <x-modal name="add-inflow" focusable>
        <div class="p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Add Inflow List') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('View and update company details, including name, industry, and contact information.') }}
                </p>
            </header>
            {{-- Inflow Name --}}
            <div class="mb-4">
                <label for="inflow" class="block text-sm font-medium text-gray-700 mt-3">Inflow Name</label>
                <input type="text" id="inflow" name="inflow"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="e.g., Inflow Name" required>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400" x-data
                    @click.prevent="
                            $dispatch('close-modal', 'add-inflow');
                            $dispatch('open-modal', 'edit-inflow-category');
                        " onclick="cancel();">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700"
                    onclick="save();">Save</button>
            </div>
        </div>
    </x-modal>

</x-app-layout>

{{-- <x-primary-button class="mt-3" onclick="update();">{{ __('Update') }}</x-primary-button> --}}
<script>
    let globalID;

    function getInflowList(typeId) {
        globalID = typeId; // Store the ID globally for later use
        $('.inflowBody').html(""); // Clear previous inflow list

        $.ajax({
            url: `/inflows/type/${typeId}`,
            method: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('.inflowBody').append(`
                    <tr>
                        <td colspan="2" class="px-6 py-3 border-b text-center text-gray-500">
                            Currently there is no inflow name.
                        </td>
                    </tr>
                `);
                    return;
                }

                $.each(data, function (index, inflow) {
                    $('.inflowBody').append(`
                    <tr>
                        <td class="px-6 py-3 border-b">${inflow.inflow}</td>
                        <td class="px-6 py-3 border-b text-center">
                            <button x-data
                                @click.prevent="
                                    $dispatch('close-modal', 'edit-inflow-category');
                                    $dispatch('open-modal', 'add-inflow');
                                "
                                class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700"
                                onclick="editInflow(${inflow.id})">
                                Edit
                            </button>
                        </td>
                    </tr>
                `);
                });
            },
            error: function () {
                alert('Failed to fetch inflows.');
            }
        });
    }


    function editInflow(id) {
        globalID = id; // Store the ID globally for later use
        $.ajax({
            url: "{{ route('inflows.show2', ['id' => ':id']) }}".replace(':id', id),
            type: 'get',
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                console.log(response);
                $('#inflow').val(response.inflow);
            }
        });
    }

    // save
    function save() {
        let inflow = $('#inflow').val();
        $.ajax({
            url: "{{ route('inflows.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                inflow: inflow,
                type_id: globalID // correctly named as type_id
            },
            success: function (response) {

            }
        });
    }

    function cancel() {
        getInflowList(globalID)
    }

    function saveInflowCategory(value) {
        let inflow_category = value === 'outflow' ? $('#outflow_category').val() : $('#inflow_category').val();
        $.ajax({
            url: "{{ route('inflowType.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                inflow_name: inflow_category,
                category: value
            },
            success: function (response) {
                InflowCategoryDetails(response.id, response.inflow_name, response.category);
            }
        });
    }



    function InflowCategoryDetails(id, inflowName, inflowCategory) {
        // console.log(inflow + ' ' + inflowCategory)
        $('.InflowCategoryList').append(`
            <div  x-data @click.prevent="$dispatch('open-modal', 'edit-inflow-category')" class="bg-gray-100 p-4 rounded-lg shadow-sm border border-gray-200" onclick="getInflowList(${id})">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-300 flex items-center justify-center rounded-full">
                        <span class="text-gray-600 text-sm font-semibold">${getAcronym(inflowName)}</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">${inflowName}</h4>
                        <p class="text-sm text-gray-600">${inflowCategory}</p>
                    </div>
                </div>
            </div>`);
    }
    getInflowType();

    function getInflowType() {
        $.ajax({
            url: "{{ route('inflowType.show') }}",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.length === 0) {
                    $('.InflowCategoryList').html(
                        '<div class="alert alert-info">There are currently no Inflow Category.</div>');
                    return;
                }
                $.each(response, (key, value) => {
                    console.log(value);
                    InflowCategoryDetails(value.id, value.inflow_name, value.category);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching inflow types:", error);
            }
        })
    }

    function getAcronym(phrase) {
        // Guard clause
        if (!phrase || typeof phrase !== 'string') {
            return '';
        }

        let words = phrase.trim().split(/\s+/);
        let acronym = '';

        words.forEach(word => {
            // Match the first consonant(s) + vowel (syllable start)
            let match = word.match(/^[^aeiouAEIOU]*[aeiouAEIOU]?/);
            if (match && match[0]) {
                acronym += match[0].charAt(0).toUpperCase();
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