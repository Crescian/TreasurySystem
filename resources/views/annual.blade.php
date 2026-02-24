<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Annual Summary') }}
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

        /* Annual Summary Table Styles */
        .annual-summary-table {
            min-width: 1000px;
            /* ← tweak as needed */
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: sans-serif, 'Segoe UI', Tahoma, Geneva, Verdana;
        }

        .annual-summary-table th,
        .annual-summary-table td {
            border: 2px solid black;
            padding: 6px;
            font-size: 0.75rem;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* After (FIXED) */
        .annual-summary-table .section-col {
            width: 200px;
            text-align: left;
            padding-left: 10px;
        }

        /* Total column can auto-size based on content */
        .annual-summary-table .total-col {
            white-space: nowrap;
            /* optional: prevent wrapping */
        }

        .annual-summary-table .company-header {
            font-weight: bold;
            font-size: 0.875rem;
            text-align: center;
            color: #111827;
        }

        .annual-summary-table .section-header {

            /* background-color: #e5e7eb; */
            font-weight: 600;
            color: #1f2937;
        }

        .annual-summary-table .subsection-header {
            font-style: italic;
            text-decoration: underline;
            text-align: left;
            padding-left: 20px;
            color: #374151;
        }

        .annual-summary-table .customer-name {
            text-align: left;
            padding-left: 40px;
            color: #4b5563;
            font-size: 0.75rem;
        }

        .annual-summary-table .section-total {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #111827;
        }

        .annual-summary-table .placeholder-cell {
            color: #6b7280;
        }

        /* Only left and right borders for month and total cells */
        .annual-summary-table .range-col {
            width: 60px;
            border-top: none !important;
            border-bottom: 2px solid #111827 !important;
            border-left: 2px solid black;
            border-right: 2px solid black;
        }

        .annual-summary-table tr:last-child .range-col {
            border-bottom: 2px solid black !important;
        }


        /* Only apply full borders to the TOTAL column in the table header */
        .annual-summary-table thead th.range-col.total-col {
            border-top: 2px solid black;
            border-right: 2px solid black;
            border-left: 2px solid black;
        }

        /* Remove top and bottom borders from section, subsection, and customer rows */
        .annual-summary-table .section-header td,
        .annual-summary-table .subsection-header,
        .annual-summary-table .customer-name {
            border-top: 1px solid #d1d5db !important;
            /* gray-300 */
            border-bottom: 1px solid #d1d5db !important;
            /* gray-300 */
        }

        /* If the cells for these rows have class 'range-col', also apply the same rule */
        .annual-summary-table tr.section-header td.range-col,
        .annual-summary-table tr .subsection-header~td.range-col,
        .annual-summary-table tr .customer-name~td.range-col {
            border-top: none !important;
            border-bottom: none !important;
        }
    </style>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Title + Year selector only --}}
            <form id="filter-form" class="mb-6 inline-flex items-center space-x-8 whitespace-nowrap"
                action="javascript:void(0);">
                <h1 class="text-2xl font-bold flex-shrink-0">
                    PETROLIFT INC. SOURCES AND USE OF FUNDS (Annual)
                </h1>

                {{-- Year dropdown only --}}
                <div class="flex items-center space-x-2 w-32 flex-none">
                    <label for="year" class="text-sm font-medium text-gray-700">Year:</label>
                    <select id="year" name="year" class="w-full h-8 rounded border-gray-300 px-2 text-sm">
                        @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                            <option value="{{ $y }}" @selected((int) $year === $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Spinner (hidden by default) --}}
            <div class="spinner fixed inset-0 flex items-center justify-center bg-white bg-opacity-75 z-50 hidden">
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto w-full">
                <table class="annual-summary-table min-w-full table-auto border-collapse">
                    <thead id="summary-head">
                        {{-- Filled in by JS (e.g., months Jan–Dec) --}}
                    </thead>
                    <tbody id="summary-body">
                        {{-- Filled in by JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Hide spinner and table initially
        $('.spinner').hide();
        $('#summary-head').hide();
        $('#summary-body').hide();

        // On page load, fetch the JSON
        $(document).ready(function () {
            fetchAnnual();
        });

        // Whenever month or year changes, reload data
        $('#year').on('change', fetchAnnual);
        function fetchAnnual() {
            const year = $('#year').val();

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $.ajax({
                url: `{{ route('annual.data') }}?year=${encodeURIComponent(year)}`,
                type: 'GET',
                dataType: 'json',
                beforeSend() {
                    $('.spinner').show();
                    $('#summary-head, #summary-body').hide();
                },
                success(resp) {
                    console.group("📦 RAW ANNUAL DATA RESPONSE");
                    console.log("Full response:", resp);
                    console.log("Sections:", resp.sections);
                    console.log("Summary:", resp.summary);
                    console.log("Months:", resp.months);
                    console.log("Previous Beginning Balance:", resp.previous_beginning_balance);
                    console.groupEnd();
                    const sections = resp.sections;
                    const summary = resp.summary;
                    const months = resp.months; // [1..12]
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                    // ✅ Create main table and header elements
                    const $table = $('<table class="annual-summary-table"></table>');
                    const $thead = $('<thead class="bg-gray-100"></thead>');
                    // ✅ Row 1: Section | Consolidated (spans 12 months)
                    const $row1 = $('<tr class="font-bold text-center"></tr>');
                    $row1.append('<th class="section-col company-header">Section</th>');
                    $row1.append(`<th colspan="${months.length}" class="company-header">Consolidated</th>`);
                    //$row1.append('<th class="range-col total-col">Total</th>'); // ⬅️ Added total column
                    $row1.append('<th class="total-col" style="border: 2px solid black !important;">Total</th>');



                    // formula for Gross Revenue sum of all inflows just create variables for all values and if they are not in the table use value zero to represent them
                    // formula for



                    $thead.append($row1);
                    $table.append($thead);
                    // $table.append($tbody);
                    $('.annual-summary-table').replaceWith($table); // Replace old table
                    $('.spinner').hide();
                }
            })
        }





    </script>
    </script>
</x-app-layout>