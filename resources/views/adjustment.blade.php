<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adjustment') }}
        </h2>
    </x-slot>
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
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-8xl mx-auto">
            <div class="bg-white shadow-md rounded p-6 sm:p-8">
                <div class="flex justify-center items-center min-h-[100px] mt-6 spindiv">
                    <div class="spinner">
                        <div class="loader"></div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Report Type
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account Name
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    Cust/Vend/Emp</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Inflow Type
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Performed By
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Performed
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script src="{{ asset('js/jquery.min.js') }}"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.spinner').hide();
    $('thead').hide();
    $('tbody').hide(); // Hide body initially
    getLogs();

    function getLogs() {
        $.ajax({
            url: `{{ route('logs.show') }}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.spinner').show();
                $('thead').hide();
                $('tbody').hide();
            },
            success: function(response) {
                let tbody = '';
                $.each(response, (key, value) => {
                    const formattedAmount = parseFloat(value.amount).toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    });
                    tbody += `
                        <tr>
                            <td class="px-4 py-2">${value.date}</td>
                            <td class="px-4 py-2">${value.report_type}</td>
                            <td class="px-4 py-2">${value.bank}</td>
                            <td class="px-4 py-2">${value.acc_name}</td>
                            <td class="px-4 py-2">${value['cust/vend/emp']}</td>
                            <td class="px-4 py-2 text-${value.flow_type == 'Outflow' ? 'red' : 'green'}-600 font-semibold">
                                ${value.flow_type == 'Outflow' ? '-' : '+'}${formattedAmount}
                            </td>
                            <td class="px-4 py-2">${value.inflow_name}</td>
                            <td class="px-4 py-2">${value.description}</td>
                            <td class="px-4 py-2">${value.performed_by}</td>
                            <td class="px-4 py-2">${new Date(value.created_at).toLocaleString()}</td>
                        </tr>`;
                });
                $('tbody').html(tbody);

                // Smooth reveal after short delay
                setTimeout(() => {
                    $('.spinner').fadeOut(200, function() {
                        $('thead').fadeIn(200);
                        $('tbody').fadeIn(200);
                    });
                }, 1000); // Optional short delay to show spinner briefly
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('.spinner').fadeOut();
            }
        });
    }
</script>
