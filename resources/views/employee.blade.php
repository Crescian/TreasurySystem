<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee') }}
        </h2>
    </x-slot>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <!-- ✅ Button Above DataTable -->
                    <div class="flex justify-end mb-4">

                        <button x-data @click.prevent="$dispatch('open-modal', 'employee-modal')"
                            class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700">
                            + Add Employee
                        </button>
                    </div>

                    <!-- ✅ DataTable -->
                    <div class="table-responsive">
                        <table id="employee-table" class="table-sm w-100">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        First Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Name</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Middle Initial</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Position</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Company</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Business Unit</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Data Rows Here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Bank Account --}}
    <x-modal name="employee-modal" focusable>
        <div class="p-4 space-y-4">
            <!-- ✅ Modal Header -->
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-700">Employee Details</h2>
                <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- ✅ Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium text-gray-600">First Name</label>
                    <input type="text" id="first_name" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Last Name</label>
                    <input type="text" id="last_name" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Middle Initial</label>
                    <input type="text" id="middle_initial" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Position</label>
                    <input type="text" id="position" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Department</label>
                    <input type="text" id="department" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Company</label>
                    <input type="text" id="company" class="w-full mt-1 p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Business Unit</label>
                    <input type="text" id="business_unit" class="w-full mt-1 p-2 border rounded">
                </div>
            </div>

            <!-- ✅ Modal Footer -->
            <div class="flex justify-between mt-4 space-x-2">
                <button onclick="save();"
                    class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700 save">
                    Save
                </button>
                <button onclick="update();"
                    class="bg-blue-600 text-black border border-black px-4 py-2 rounded-lg hover:bg-blue-700 update">
                    Update
                </button>

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
    displayTable();
    $('.update').hide();

    function viewBankAccounts(id) {
        window.dispatchEvent(new Event('open-modal'));
    }

    function save() {
        $('.save').show();
        $('.update').hide();
        $.ajax({
            url: "{{ route('employees.store') }}", // Define the route to handle storing employees
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // Include CSRF token
                first_name: $('#first_name').val(), // Get the value of the input field
                last_name: $('#last_name').val(), // Get the value of the input field
                middle_initial: $('#middle_initial').val(), // Get the value of the input field'),
                position: $('#position').val(), // Get the value of the input field
                department: $('#department').val(), // Get the value of the input field
                company: $('#company').val(), // Get the value of the input field
                business_unit: $('#business_unit').val(), // Get the value of the input field')
            }, // Serialize the form data
            success: function(response) {
                console.log(response);
                displayTable();
            }
        });
    }

    function displayTable() {
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#employee-table')) {
            $('#employee-table').DataTable().clear().destroy();
        }

        $('#employee-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees.data') }}",
            columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'middle_initial',
                    name: 'middle_initial'
                },
                {
                    data: 'position',
                    name: 'position'
                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'company',
                    name: 'company'
                },
                {
                    data: 'business_unit',
                    name: 'business_unit'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }

    let globalId;

    function editEmployee(id) {
        globalId = id;
        $('.save').hide();
        $('.update').show();
        $.ajax({
            url: `/employees/${id}`, // Use template literal to insert the ID
            type: 'GET',
            success: function(response) {
                console.log(response);
                $('#first_name').val(response.first_name);
                $('#last_name').val(response.last_name);
                $('#middle_initial').val(response.middle_initial);
                $('#position').val(response.position);
                $('#department').val(response.department);
                $('#company').val(response.company);
                $('#business_unit').val(response.business_unit);

                window.dispatchEvent(new Event('open-modal'));
            }
        });
    }

    function update() {
        $.ajax({
            url: "{{ route('employees.update', ['id' => ':id']) }}".replace(':id', globalId),
            type: 'PUT',
            data: {
                _token: "{{ csrf_token() }}",
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                middle_initial: $('#middle_initial').val(),
                position: $('#position').val(),
                department: $('#department').val(),
                company: $('#company').val(),
                business_unit: $('#business_unit').val(),
            },
            success: function(response) {
                console.log(response);
                displayTable();
            }
        });
    }
</script>
