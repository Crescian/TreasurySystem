<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Monthly Summary') }}
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

  <div class="py-6">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Title + Month/Year selectors --}}
      <form id="filter-form" class="mb-6 inline-flex items-center space-x-8 whitespace-nowrap"
        action="javascript:void(0);">
        <h1 class="text-2xl font-bold flex-shrink-0">
          PETROLIFT INC. SOURCES AND USE OF FUNDS
        </h1>

        {{-- Month dropdown --}}
        <div class="flex items-center space-x-2 w-40 flex-none">
          <label for="month" class="text-sm font-medium text-gray-700">Month:</label>
          <select id="month" name="month" class="w-full h-8 rounded border-gray-300 px-2 text-sm">
            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
              <option value="{{ $m }}" @selected($monthName === $m)>{{ $m }}</option>
            @endforeach
          </select>
        </div>

        {{-- Year dropdown --}}
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
      <div class="spinner fixed inset-0 flex items-center justify-center bg-white bg-opacity-75 z-50">
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
      </div>

      {{-- Table --}}
      <div class="overflow-x-auto">
        <table class="min-w-max table-auto border-collapse">
          <thead id="summary-head">
            {{-- Filled in by JS --}}
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
      fetchMonthlySummary();
    });

    // Whenever month or year changes, reload data
    $('#month, #year').on('change', fetchMonthlySummary);


    function computeNetTotals(sectionTotalsJson, companies, ranges) {
      const stopSections = [
        "OTHER EXPENSES (INCOME)",
        "CAPEX",
        "NONTRADE TRANSACTIONS",
        "STOCKHOLDERS",
        "FINANCE TRANSACTIONS"
      ];

      const result = {};

      // Collect all actual ranges used
      const allRanges = new Set();

      for (const section in sectionTotalsJson) {
        for (const subsection in sectionTotalsJson[section]) {
          for (const company in sectionTotalsJson[section][subsection]) {
            const companyData = sectionTotalsJson[section][subsection][company];
            for (const range in companyData) {
              if (range !== "Total" && range !== undefined && range !== null) {
                allRanges.add(range);
              }
            }
          }
        }
      }

      //console.log("🧾 Detected Ranges from sectionTotalsJson:", Array.from(allRanges));

      // Loop through companies in given order
      companies.forEach(companyObj => {
        const companyName = companyObj.label;
        const companyTotals = {};

        //console.log(`\n📊 Processing company: ${companyName}`);

        // Initialize all ranges with 0
        ranges.forEach(range => {
          companyTotals[range] = 0;
        });

        for (const section in sectionTotalsJson) {
          if (stopSections.includes(section)) {
            console.log(`⛔️ Stopping at section: ${section}`);
            break;
          }

          //console.log(`🔍 Section: ${section}`);

          for (const subsection in sectionTotalsJson[section]) {
            const companyData = sectionTotalsJson[section][subsection][companyName];
            if (!companyData) continue;

            //console.log(`  ▶ Subsection: ${subsection}`);

            for (const range in companyData) {
              if (range === "Total" || !ranges.includes(range)) continue;

              const value = companyData[range] || 0;
              const operation = section === "GROSS REVENUES" ? "ADD" : "SUBTRACT";

              //console.log(`    ➤ ${operation} [${range}] = ${value}`);

              if (section === "GROSS REVENUES") {
                companyTotals[range] += value;
              } else {
                companyTotals[range] -= value;
              }
            }
          }
        }

        // Compute total and assign to result
        let total = 0;
        result[companyName] = {};
        ranges.forEach(range => {
          const val = companyTotals[range] || 0;
          result[companyName][range] = val;
          total += val;
        });
        result[companyName]["Total"] = total;

        //console.log(`✅ Totals for ${companyName}:`, result[companyName]);
      });

      //console.log("\n📦 Final Net Totals Result:", JSON.stringify(result, null, 2));
      return result;
    }

    function fetchMonthlySummary() {
      const month = $('#month').val();
      const year = $('#year').val();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: `{{ route('monthly_summary.data') }}?month=${encodeURIComponent(month)}&year=${encodeURIComponent(year)}`,
        type: 'GET',
        dataType: 'json',

        beforeSend: function () {
          $('.spinner').show();
          $('#summary-head').hide();
          $('#summary-body').hide();
        },

        success: function (resp) {
          // Extract data from response
          const companies = resp.companies;
          const sections = resp.sections;
          const summary = resp.summary;
          const invoiceLabels = resp.invoiceLabels;
          const ranges = Object.keys(resp.ranges); // Convert object keys to array





          // Create main table and header elements
          // const $table = $('<table class="table-auto w-full border-collapse border border-gray-300"></table>');
          const $table = $('<table class="table-auto w-full border-collapse" style="border: 2px solid black;"></table>');
          const $thead = $('<thead class="bg-gray-100"></thead>');

          // Build company name row
          const $companyColumns = $('<tr></tr>');
          $companyColumns.append('<th style="border: 2px solid black; padding: 8px;">Section</th>');

          companies.forEach(company => {
            $companyColumns.append(`
            <th colspan="${ranges.length}" style="border: 2px solid black; padding: 8px; background-color: ${company.color};">
              ${company.label}
            </th>
          `);
          });

          // Build range header row
          const $rowColumns = $('<tr></tr>');
          $rowColumns.append('<th style="border: 2px solid black; padding: 8px;"></th>');

          companies.forEach(company => {
            ranges.forEach(range => {
              $rowColumns.append(`
            <th style="border: 2px solid black; padding: 4px; font-size: 0.75rem;" class="col-${company.key}">
              ${range}
            </th>
          `);
            });
          });


          // Initialize body and data containers
          const $tbody = $('<tbody></tbody>');
          const subsectionRowMap = {}; // Used for referencing customer row positions
          const accumulatedTotals = {}; // Totals for each company per range
          const inflowOutflowTotal = {}; // Stores inflow - outflow values
          const sectionTotalsJson = {}; // Global accumulator

          const inflowOutflowSections = [
            "OTHER EXPENSES (INCOME)",
            "CAPEX",
            "NONTRADE TRANSACTIONS",
            "STOCKHOLDERS",
            "FINANCE TRANSACTIONS"
          ];




          // Initialize totals for each company and range
          companies.forEach(company => {
            accumulatedTotals[company.label] = {};
            inflowOutflowTotal[company.label] = {}; // NEW: initialize inflowOutflowTotal
            ranges.forEach(range => {
              accumulatedTotals[company.label][range] = 0;
              inflowOutflowTotal[company.label][range] = 0;
            });
          });




          // Loop through each section
          for (const sectionName in sections) {
            const subsections = sections[sectionName];



            const isInflowOutflow = inflowOutflowSections.includes(sectionName);

            // Store current accumulated totals before deduction
            let inflowOutflowBase = {};
            if (isInflowOutflow) {
              companies.forEach(company => {
                inflowOutflowBase[company.label] = {};
                ranges.forEach(range => {
                  inflowOutflowBase[company.label][range] = accumulatedTotals[company.label][range];
                });
              });
            }

            // Section title row
            const sectionRow = $('<tr class="bg-gray-200 font-semibold" style="border-left: 2px solid black;"></tr>');

            //sectionRow.append(`<td class="px-4 py-2 text-left text-gray-800 border">${sectionName}</td>`);
            sectionRow.append(`<td style="padding: 8px; text-align: left; color: #1f2937; border-right: 2px solid black;">${sectionName}</td>`);

            // companies.forEach(() => {
            //   ranges.forEach(() => {
            //     sectionRow.append(`<td class="border px-2 py-1 text-xs text-center text-gray-800" >–</td>`);
            //   });
            // });

            companies.forEach(() => {
              ranges.forEach((_, index) => {
                sectionRow.append(`
                <td class="border px-2 py-1 text-xs text-center text-gray-800"
                    ${index === 5 ? 'style="border-right: 2px solid black !important;"' : ''}>
                  –
                </td>
              `);
              });
            });
            $tbody.append(sectionRow);

            // Subsections and customer rows
            subsections.forEach(sub => {
              // Subsection row
              const subsectionRow = $('<tr class="font-medium"></tr>');
              subsectionRow.append(`<td class="px-4 py-2 pl-6 border text-gray-700 underline" style="font-style: italic; border-right: 2px solid black !important; border-left: 2px solid black !important;">${sub.toLocaleString()}</td>`);

              // companies.forEach(() => {
              //   ranges.forEach(() => {
              //     subsectionRow.append(`<td class="border px-2 py-1 text-xs text-center">–</td>`);
              //   });
              // });
              companies.forEach(() => {
                ranges.forEach((_, index) => {
                  subsectionRow.append(`
                <td class="border px-2 py-1 text-xs text-center"
                    ${index === 5 ? 'style="border-right: 2px solid black !important;"' : ''}>
                  –
                </td>
                `);
                });
              });
              $tbody.append(subsectionRow);

              // Keep track of inserted customers to avoid duplicates
              const seenCustomers = new Set();

              const subsectionSummary = summary?.[sectionName]?.[sub];
              if (subsectionSummary) {
                companies.forEach(company => {
                  const companyData = subsectionSummary?.[company.label];
                  if (!companyData) return;

                  for (const customer in companyData) {
                    if (seenCustomers.has(customer)) continue;
                    seenCustomers.add(customer);

                    const $customerRow = $('<tr></tr>');
                    //$customerRow.append(`<td class="px-4 py-2 pl-10 border text-sm text-gray-600">${customer}</td>`);
                    $customerRow.append(`<td class="px-4 py-2 pl-10 border text-sm text-gray-600" style="border-right: 2px solid black !important;border-left: 2px solid black !important;">${customer}</td>`);


                    // companies.forEach(comp => {
                    //   ranges.forEach(range => {
                    //     const value = summary?.[sectionName]?.[sub]?.[comp.label]?.[customer]?.[range];
                    //     const display = (typeof value === 'undefined' || value === 0) ? '–' : value;
                    //     $customerRow.append(`<td class="border px-2 py-1 text-xs text-center">${display.toLocaleString()}</td>`);
                    //   });
                    // });
                    companies.forEach(comp => {
                      ranges.forEach((range, index) => {
                        const value = summary?.[sectionName]?.[sub]?.[comp.label]?.[customer]?.[range];
                        const display = (typeof value === 'undefined' || value === 0) ? '–' : value;

                        $customerRow.append(`
                        <td class="border px-2 py-1 text-xs text-center"
                            ${index === 5 ? 'style="border-right: 2px solid black !important;"' : ''}>
                          ${display.toLocaleString()}
                        </td>
                        `);
                      });
                    });

                    $tbody.append($customerRow);
                  }
                });
              }
            });

            // Set display label for section total
            let displaySectionName;
            if (sectionName === "OTHER EXPENSES (INCOME)") {
              displaySectionName = `(OTHER INFLOW / (OUTFLOW))`;
            } else if (sectionName === "CAPEX") {
              displaySectionName = "Sub-Total";
            } else if (sectionName === "NONTRADE TRANSACTIONS") {
              displaySectionName = "Net Advances to Affiliate";
            } else if (sectionName === "STOCKHOLDERS") {
              displaySectionName = "Net Advances to Stockholders";
            } else if (sectionName === "FINANCE TRANSACTIONS") {
              displaySectionName = "Net Finance Transactions";
            } else {
              displaySectionName = `Total ${sectionName}`;
            }


            // Build section total row
            const sectionTotalRow = $('<tr style="border-top: 2px solid black; border-bottom: 2px solid black; background-color: #f3f4f6;"></tr>');
            //sectionTotalRow.append(`<td class="px-4 py-2 text-left border text-black-900" style="font-weight: 600;">${displaySectionName}</td>`);
            sectionTotalRow.append(`<td class="px-4 py-2 text-left border text-black-900" style="font-weight: 600; border-right: 2px solid black !important;border-left: 2px solid black !important;">${displaySectionName}</td>`);




            // Initialize section if not yet present
            if (!sectionTotalsJson[sectionName]) {
              sectionTotalsJson[sectionName] = {};
            }

            companies.forEach(company => {
              let i = 0;
              ranges.forEach(range => {
                i++;
                let totalValue = 0;
                const subsectionSummary = summary[sectionName];

                for (const subsection in subsectionSummary) {
                  const companyData = subsectionSummary[subsection][company.label];
                  if (!companyData) continue;

                  for (const customer in companyData) {
                    const value = companyData[customer]?.[range];
                    if (typeof value !== 'undefined') {
                      totalValue += value;

                      // Ensure hierarchy exists
                      if (!sectionTotalsJson[sectionName][subsection]) {
                        sectionTotalsJson[sectionName][subsection] = {};
                      }
                      if (!sectionTotalsJson[sectionName][subsection][company.label]) {
                        sectionTotalsJson[sectionName][subsection][company.label] = {};
                      }

                      // Add value per customer/range — can use += if you want to sum over customers
                      if (!sectionTotalsJson[sectionName][subsection][company.label][range]) {
                        sectionTotalsJson[sectionName][subsection][company.label][range] = 0;
                      }

                      sectionTotalsJson[sectionName][subsection][company.label][range] += value;
                    }
                  }
                }

                if (!isInflowOutflow) {
                  accumulatedTotals[company.label][range] += totalValue;
                }

                const display = totalValue === 0 ? '–' : totalValue.toLocaleString();
                //sectionTotalRow.append(`<td class="border px-2 py-1 text-xs text-center text-gray-900" style="font-weight: 600;">${display.toLocaleString()}</td>`);
                sectionTotalRow.append(`
                  <td class="border px-2 py-1 text-xs text-center text-gray-900"
                      style="font-weight: 600; ${((i + 1) === 7) ? 'border-right: 2px solid !important;' : ''}">
                    ${display}
                  </td>
                `);



              });
            });

            $tbody.append(sectionTotalRow);

          }




          const inflowOutFlowTotals = computeNetTotals(sectionTotalsJson, companies, ranges);

          const inflowOutflowMarkers = [
            '(OTHER INFLOW / (OUTFLOW))',
            'Sub-Total',
            'Net Advances to Affiliate',
            'Net Advances to Stockholders',
            'Net Finance Transactions'
          ];

          const inflowOutflowName = [
            'OTHER EXPENSES (INCOME)',
            'CAPEX',
            'NONTRADE TRANSACTIONS',
            'STOCKHOLDERS',
            'FINANCE TRANSACTIONS'
          ];

          const companiesInOrder = Object.keys(inflowOutFlowTotals);
          const baseData = JSON.parse(JSON.stringify(inflowOutFlowTotals)); // Deep copy
          let currentData = JSON.parse(JSON.stringify(baseData)); // Running totals
          let netRowInserted = false; // NEW: Track if inflow/outflow row was already inserted


          $tbody.children('tr').each(function () {
            const $row = $(this);
            const firstCell = $row.find('td:first').text().trim();
            const markerIndex = inflowOutflowMarkers.indexOf(firstCell);
            const inflowRow = $('<tr class="bg-blue-50 italic font-semibold"></tr>');


            if (markerIndex !== -1) {
              netRowInserted = true; // NEW: Mark that we inserted it here
              const sectionName = inflowOutflowName[markerIndex] || "UNKNOWN SECTION";
              const sectionData = sectionTotalsJson[sectionName];

              // Subtract current section BEFORE rendering
              if (sectionData) {
                for (const subsection in sectionData) {
                  const subsectionData = sectionData[subsection];
                  for (const company of companiesInOrder) {
                    const companyData = subsectionData[company];
                    if (!companyData) continue;

                    for (const range in companyData) {
                      if (!currentData[company][range]) currentData[company][range] = 0;
                      ///// hack fix to when positive and negative values is computed it results to negative even when the positive is bigger
                      const subVal = Number(companyData[range]) || 0;
                      currentData[company][range] = (currentData[company][range] >= 0 && subVal < 0) || (currentData[company][range] < 0 && subVal >= 0)
                        ? Number(currentData[company][range]) + Math.abs(subVal)
                        : Number(currentData[company][range]) - Math.abs(subVal);

                    }
                  }
                }
              }


              // Render the INFLOW / (OUTFLOW) or NET CASH INFLOW / OUTFLOW row
              const displayName = sectionName === 'FINANCE TRANSACTIONS'
                ? 'NET CASH INFLOW / OUTFLOW'
                : `INFLOW / (OUTFLOW) - ${sectionName}`;

              // Always append the section-specific label
              inflowRow.append(`
              <td class="px-4 py-2 text-left border text-black-900" style="border-right: 2px solid black !important;border-left: 2px solid black !important;">
                ${displayName}
              </td>
              `);




              const endingValues = [];

              for (const company of companiesInOrder) {
                const data = currentData[company];
                const rangesOnly = Object.keys(data).filter(r => r !== "Total");
                let total = 0;


                // for (const range of rangesOnly) {
                //   const val = data[range] || 0;
                //   inflowRow.append(`<td class="border px-2 py-1 text-xs text-center">${val === 0 ? "–" : val.toLocaleString()}</td>`);
                //   endingValues.push(val);
                //   total += Number(val);

                // }
                let i = 0;
                for (const range of rangesOnly) {
                  const val = data[range] || 0;

                  inflowRow.append(`
                  <td class="border px-2 py-1 text-xs text-center"
                      ${((i + 1) === 6) ? 'style="border-right: 2px solid black !important;"' : ''}>
                    ${val === 0 ? '–' : val.toLocaleString()}
                  </td>
                `);

                  endingValues.push(val);
                  total += Number(val);
                  i++;
                }

                //inflowRow.append(`<td class="border px-2 py-1 text-xs text-center font-semibold">${total === 0 ? "–" : total.toLocaleString()}</td>`);
                inflowRow.append(`
                <td class="border px-2 py-1 text-xs text-center font-semibold"
                    ${((i + 1) === 6) ? 'style="border-right: 2px solid black !important;"' : ''}>
                  ${total === 0 ? '–' : total.toLocaleString()}
                </td>
              `);
                endingValues.push(total);
              }



              // 💡 Change: insert inflowRow last (after both balance rows)
              $row.after(inflowRow); // ⬅️ Leave this here as placeholder

              // Add Beginning & Ending rows if NOT finance
              if (sectionName !== 'FINANCE TRANSACTIONS') {
                const beginningRow = $('<tr class="bg-white italic text-gray-700"></tr>');
                beginningRow.append(`<td class="bg-gray-100 px-4 py-2 text-left border" style="border-right: 2px solid black !important;border-left: 2px solid black !important; border-bottom: 2px solid black !important;">Beginning Balance</td>`);

                // for (let i = 0; i < endingValues.length; i++) {
                //   beginningRow.append(`<td class=" bg-gray-100 border px-2 py-1 text-xs text-center">–</td>`);
                // }
                // for (let i = 0; i < endingValues.length; i++) {
                //   beginningRow.append(`
                //   <td class="bg-gray-100 px-2 py-1 text-xs text-center border"
                //       style="border-bottom: 2px solid black !important;">
                //     –
                //   </td>
                // `);
                // }
                for (let i = 0; i < endingValues.length; i++) {
                  beginningRow.append(`
                  <td class="bg-gray-100 px-2 py-1 text-xs text-center border"
                      style="
                        border-bottom: 2px solid black !important;
                        ${((i + 1) % 6 === 0) ? 'border-right: 2px solid black !important;' : ''}
                      ">
                    –
                  </td>
                `);
                }

                const endingRow = $('<tr class="bg-gray-100 font-semibold"></tr>');
                // Change: bold the label text
                endingRow.append(`<td class="px-4 py-2 text-left border text-black-900 font-bold" style="background-color: #fff9db; border: 2px solid black !important;border-top: 2px solid black !important;border-bottom: 2px solid black !important;border-right: 2px solid black !important;border-left: 2px solid black !important;">ENDING CASH BEF - ${sectionName}</td>`);

                for (const val of endingValues) {
                  endingRow.append(`
                  <td class="border-l border-r border-gray-300 px-2 py-1 text-xs text-center"
                      style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;border-left: 2px solid black !important; border-right: 2px solid black !important">
                    ${val === 0 ? "–" : val.toLocaleString()}
                  </td>
                `);
                }

                // Change: insert in correct order — first inflow, then beginning, then ending
                inflowRow.after(beginningRow);   // ⬅️ Step 1: inflow → beginning
                beginningRow.after(endingRow);   // ⬅️ Step 2: beginning → ending
              }
            }

          });





          // previous beginning balance
          const prevBalanceBalanceRawData = resp.previous_beginning_balance || [];
          console.log("🟡 previousBeginningBalanceRaw:", prevBalanceBalanceRawData);

          // create a for loop that will count number of ranges in first company
          // console.log("Ranges:", ranges.length);

          // 📅 Determine previous month
          const monthMap = {
            January: 1, February: 2, March: 3, April: 4,
            May: 5, June: 6, July: 7, August: 8,
            September: 9, October: 10, November: 11, December: 12
          };

          const selectedMonthName = $('#month').val();
          const selectedYear = parseInt($('#year').val(), 10);
          // console.log("📅 Selected month/year:", selectedMonthName, selectedYear);

          if (!monthMap[selectedMonthName] || isNaN(selectedYear)) {
            console.warn("❌ Invalid month or year.");
          } else {
            let monthNum = monthMap[selectedMonthName];
            let prevMonth = monthNum - 1;
            let yearForPrevMonth = selectedYear;

            if (prevMonth === 0) {
              prevMonth = 12;
              yearForPrevMonth -= 1;
            }

            const lastDayOfPrevMonth = new Date(yearForPrevMonth, prevMonth, 0);
            const formattedLabel = `${lastDayOfPrevMonth.getMonth() + 1}.${lastDayOfPrevMonth.getDate()}.${lastDayOfPrevMonth.getFullYear() % 100}`;
            const balanceLabel = `Beginning Balance ${formattedLabel}`;
            // console.log("🧾 Balance row label:", balanceLabel);

            // ✅ Create beginning balance row
            const $beginningRow = $('<tr class="bg-white italic text-gray-700"></tr>');
            $beginningRow.append(`<td class="bg-gray-100 px-4 py-2 text-left border" style="border-right: 2px solid black !important;border-left: 2px solid black !important;">${balanceLabel}</td>`);

            const numberTotalColumns = Object.keys(resp.companies).length * ranges.length;
            console.log("Count:", numberTotalColumns);

            const rangesLength = ranges.length;
            let balanceIndex = 0;

            let lastDisplay = '–'; // store last used display value

            // for (let i = 0; i < numberTotalColumns; i++) {
            //   if (i % rangesLength === 0 && balanceIndex < prevBalanceBalanceRawData.length) {
            //     const amount = parseFloat(prevBalanceBalanceRawData[balanceIndex].amount);
            //     const display = (!amount || isNaN(amount)) ? '–' : amount.toLocaleString();
            //     $beginningRow.append(`<td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold">${display.toLocaleString()}</td>`);
            //     lastDisplay = display; // save this for use in total column
            //     balanceIndex++;
            //   }

            //   // Total column of each group (i.e., 5, 11, 17, etc.) — except the last total column
            //   else if ((i + 1) % rangesLength === 0 && i + 1 !== numberTotalColumns) {
            //     $beginningRow.append(`<td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold">${lastDisplay.toLocaleString()}</td>`);
            //   }

            //   else {
            //     $beginningRow.append(`<td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold">–</td>`);
            //   }
            // }
            for (let i = 0; i < numberTotalColumns; i++) {

              // Start of each group
              if (i % rangesLength === 0 && balanceIndex < prevBalanceBalanceRawData.length) {
                const amount = parseFloat(prevBalanceBalanceRawData[balanceIndex].amount);
                const display = (!amount || isNaN(amount)) ? '–' : amount.toLocaleString();

                $beginningRow.append(`
                <td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold"
                    ${((i + 1) % 6 === 0) ? 'style="border-right: 2px solid black !important;"' : ''}>
                  ${display}
                </td>
              `);

                lastDisplay = display;
                balanceIndex++;
              }

              // Total column of each group
              else if ((i + 1) % rangesLength === 0 && i + 1 !== numberTotalColumns) {
                $beginningRow.append(`
                <td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold"
                    ${((i + 1) % 6 === 0) ? 'style="border-right: 2px solid black !important;"' : ''}>
                  ${lastDisplay}
                </td>
              `);
              }

              // Empty cell
              else {
                $beginningRow.append(`
              <td class="border bg-gray-100 px-2 py-1 text-xs text-center font-semibold"
                  ${((i + 1) % 6 === 0) ? 'style="border-right: 2px solid black !important;"' : ''}>
                –
              </td>
            `);
              }
            }

            // ✅ Append to end
            $tbody.append($beginningRow);
            // console.log("✅ Beginning Balance row appended.");
          }

          // create ending balance row

          // // 🔍 Locate the dated "Beginning Balance" row (e.g., "Beginning Balance 5.31.25")
          // const $beginningRow = $tbody.find('tr').filter(function () {
          //   return $(this).find('td:first').text().trim().match(/^Beginning Balance \d{1,2}\.\d{1,2}\.\d{2}$/);
          // }).last();
          const rangesLength = ranges.length;
          const $netCashRow = $tbody.find('tr:contains("NET CASH INFLOW / OUTFLOW")').last();

          const $finalBeginningRow = $tbody.find('tr').filter(function () {
            return $(this).find('td:first').text().trim().match(/^Beginning Balance \d{1,2}\.\d{1,2}\.\d{2}$/);
          }).last();
          console.log("NetCash row found:", $netCashRow.length);
          console.log("Beginning row found:", $finalBeginningRow.length);
          //const $endingRow = $('<tr class="bg-green-100 font-bold uppercase"></tr>');
          const $endingRow = $('<tr class="bg-green-100 font-bold uppercase" data-ending-row="true"></tr>');
          $endingRow.append(`<td class="px-4 py-2 text-left border text-black-900 font-bold" style="background-color: #fff9db;border-top: 2px solid black  !important;border-right: 2px solid black !important;border-left: 2px solid black !important;border-bottom: 2px solid black !important;">ENDING BALANCE</td>`);


          // variable for the last current balance
          let lastCurrentBalance = 0;
          let currentBalanceArray = [];
          // for (let i = 1; i < $netCashRow.find('td').length; i++) {
          //   // Get net cash value for this column
          //   const netCash = $netCashRow.find('td').eq(i).text().trim();
          //   const netCashValue = netCash === '–' ? 0 : parseFloat(netCash.replace(/,/g, ""));

          //   // Get beginning balance value for this column
          //   const finalBeginning = $finalBeginningRow.find('td').eq(i).text().trim();
          //   const finalBeginningValue = finalBeginning === '–' ? 0 : parseFloat(finalBeginning.replace(/,/g, "")) || 0;

          //   // Compute current balance
          //   let currentBalance = netCashValue + finalBeginningValue;
          //   // 1. If this is a "total" column (i.e., at the end of a company block), reset lastCurrentBalance
          //   if (i % rangesLength === 0) {
          //     lastCurrentBalance = 0;
          //     // Do not overwrite beginning balance cell for total columns
          //     $endingRow.append(`<td class="border px-2 py-1 text-xs text-center" style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;">${currentBalance.toLocaleString()}</td>`);
          //     lastCurrentBalance = currentBalance;
          //     currentBalanceArray.push(lastCurrentBalance);
          //     continue;
          //   }

          //   // 2. If this is the first column of a company (i.e., i == 1, 1 + rangesLength, 1 + 2*rangesLength, ...)
          //   //    Do not overwrite the beginning balance cell for these columns
          //   if ((i - 1) % rangesLength === 0) {
          //     $endingRow.append(`<td class="border px-2 py-1 text-xs text-center" style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;">${currentBalance === 0 ? '–' : currentBalance.toLocaleString()}</td>`);
          //     lastCurrentBalance = currentBalance;
          //     currentBalanceArray.push(lastCurrentBalance);
          //     continue;
          //   }

          //   // 3. If this is the last column of a company (i.e., i == rangesLength, 2*rangesLength, 3*rangesLength, ...)
          //   //    Do not overwrite the beginning balance cell for these columns, and reset lastCurrentBalance
          //   if (i % rangesLength === 0) {
          //     lastCurrentBalance = 0;
          //     $endingRow.append(`<td class="border px-2 py-1 text-xs text-center" style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;">${currentBalance === 0 ? '–' : currentBalance.toLocaleString()}</td>`);
          //     lastCurrentBalance = currentBalance;
          //     currentBalanceArray.push(lastCurrentBalance);
          //     continue;
          //   }
          //   // 4. For all other columns, carry over lastCurrentBalance, insert it into the next beginning balance cell, and compute new balance
          //   //$finalBeginningRow.find('td').eq(i).html(`${lastCurrentBalance}`);
          //   $finalBeginningRow.find('td').eq(i).html(`${lastCurrentBalance === 0 ? '–' : lastCurrentBalance.toLocaleString()}`);
          //   const newBalance = netCashValue + lastCurrentBalance;
          //   //$endingRow.append(`<td class="border px-2 py-1 text-xs text-center" style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;">${newBalance.toLocaleString()}</td>`);
          //   $endingRow.append(`<td class="border px-2 py-1 text-xs text-center" style="border-top: 2px solid black !important; border-bottom: 2px solid black !important;">${newBalance === 0 ? '–' : newBalance.toLocaleString()}</td>`);

          //   lastCurrentBalance = newBalance;
          //   currentBalanceArray.push(lastCurrentBalance);

          // }
          for (let i = 1; i < $netCashRow.find('td').length; i++) {
            const netCash = $netCashRow.find('td').eq(i).text().trim();
            const netCashValue = netCash === '–' ? 0 : parseFloat(netCash.replace(/,/g, ""));

            const finalBeginning = $finalBeginningRow.find('td').eq(i).text().trim();
            const finalBeginningValue =
              finalBeginning === '–' ? 0 : parseFloat(finalBeginning.replace(/,/g, "")) || 0;

            let currentBalance = netCashValue + finalBeginningValue;

            if (i % rangesLength === 0) {
              lastCurrentBalance = 0;

              $endingRow.append(`
            <td class="border px-2 py-1 text-xs text-center"
                style="
                  border-top: 2px solid black !important;
                  border-bottom: 2px solid black !important;
                ">
              ${currentBalance.toLocaleString()}
            </td>
          `);

              console.log(i)
              lastCurrentBalance = currentBalance;
              currentBalanceArray.push(lastCurrentBalance);
              continue;
            }

            if ((i - 1) % rangesLength === 0) {
              $endingRow.append(`
              <td class="border px-2 py-1 text-xs text-center"
                  style="
                    border-top: 2px solid black !important;
                    border-bottom: 2px solid black !important;
                    ${((i + 1) % 6 === 0) ? 'border-right: 2px solid black !important;' : ''}
                  ">
                ${currentBalance === 0 ? '–' : currentBalance.toLocaleString()}
              </td>
            `);

              lastCurrentBalance = currentBalance;
              currentBalanceArray.push(lastCurrentBalance);
              continue;
            }

            $finalBeginningRow.find('td').eq(i)
              .html(`${lastCurrentBalance === 0 ? '–' : lastCurrentBalance.toLocaleString()}`);

            const newBalance = netCashValue + lastCurrentBalance;

            $endingRow.append(`
            <td class="border px-2 py-1 text-xs text-center"
                style="
                  border-top: 2px solid black !important;
                  border-bottom: 2px solid black !important;
                ">
              ${newBalance === 0 ? '–' : newBalance.toLocaleString()}
            </td>
          `);

            lastCurrentBalance = newBalance;
            currentBalanceArray.push(lastCurrentBalance);
          }
          
          // now just get last row of the table  and put evey 6 to be red solid !important border
          console.log(currentBalanceArray);
          let TotalArray = [];
          for (let i = 0; i < currentBalanceArray.length; i++) {
            if ((i + 1) % rangesLength === 0) {
              // Only insert when index is at a multiple of rangesLength
              TotalArray.push(currentBalanceArray[i]);
            }
          }

          //console.log("TotalArray:", TotalArray);

          //create for loop for current balanceArray that will insert for == equal to rangesLength in the Total Array
          $tbody.append($endingRow);
          // Finalize table structure
          $thead.append($companyColumns);
          $thead.append($rowColumns);
          $table.append($thead);
          $table.append($tbody);
          // Inject the complete table into the DOM
          $('#summary-body').html($table);
          $('#summary-head').show();
          $('#summary-body').show();
          collectAndSendEndingBalance(companies, TotalArray);

        },

        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error);
          alert('An error occurred while fetching the summary.');
        },

        complete: function () {
          $('.spinner').hide();
        }
      });
    }


    function collectAndSendEndingBalance(companies, TotalArray) {
      try {
        const monthMap = {
          January: 1, February: 2, March: 3, April: 4,
          May: 5, June: 6, July: 7, August: 8,
          September: 9, October: 10, November: 11, December: 12
        };

        const selectedMonthName = $('#month').val();
        const selectedMonth = monthMap[selectedMonthName];
        const selectedYear = parseInt($('#year').val(), 10);

        if (!selectedMonth || isNaN(selectedYear)) {
          alert("⚠️ Please select a valid month and year.");
          return;
        }

        const values = [];

        companies.forEach((company, i) => {
          if (company.label === 'Consolidated') return;

          let value = TotalArray[i] ?? 0;

          if (value === "–") value = 0;
          if (typeof value === 'string') {
            value = parseFloat(value.replace(/,/g, '')) || 0;
          }

          values.push({
            company_label: company.label,
            period: "Total",
            value: value,
            currency: 'PHP'
          });
        });

        // Send to backend
        $.ajax({
          url: '/monthly-summaries/store',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({
            values: values,
            period_year: selectedYear,
            period_month: selectedMonth
          }),
          success: function () {
            console.log('✅ Total ending balances sent successfully.');
          },
          error: function (xhr, status, error) {
            console.error('❌ AJAX Error:', status, error);
          }
        });

      } catch (err) {
        console.error('❌ Unexpected JS error:', err);
      }
    }



  </script>
</x-app-layout>