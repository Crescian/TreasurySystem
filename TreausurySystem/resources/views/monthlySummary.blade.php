<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Monthly Summary') }}
    </h2>
  </x-slot>

  {{-- Loader Styles --}}
  <style>
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
  </style>

  <div class="py-6">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Title + Filters --}}
      <div class="mb-6 flex flex-wrap items-center justify-between gap-4">

        <h1 class="text-xl font-semibold text-gray-800 tracking-tight">
          PETROLIFT INC. SOURCES AND USE OF FUNDS
        </h1>

        <div class="flex items-center gap-4">

          {{-- Month --}}
          <div class="flex items-center gap-2">
            <label for="month" class="text-sm font-medium text-gray-600">
              Month
            </label>
            <select id="month" name="month" onchange="filterDate();" class="h-9 w-40 rounded-md border border-gray-300
                     bg-white px-2 pr-8 text-sm text-gray-700
                     shadow-sm
                     focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" style="width:100px">
              <option>January</option>
              <option>February</option>
              <option>March</option>
              <option>April</option>
              <option>May</option>
              <option>June</option>
              <option>July</option>
              <option>August</option>
              <option>September</option>
              <option>October</option>
              <option>November</option>
              <option>December</option>
            </select>
          </div>

          {{-- Year --}}
          <div class="flex items-center gap-2">
            <label for="year" class="text-sm font-medium text-gray-600">
              Year
            </label>
            <select id="year" name="year" onchange="filterDate();" class="h-9 w-28 rounded-md border border-gray-300
                     bg-white px-2 pr-8 text-sm text-gray-700
                     shadow-sm
                     focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" style="width:100px">
              <option>2024</option>
              <option>2025</option>
              <option>2026</option>
              <option>2027</option>
            </select>
          </div>

        </div>
      </div>

      {{-- Spinner --}}
      <div class="spinner fixed inset-0 z-50 hidden items-center justify-center bg-white/70 backdrop-blur-sm">
        <div class="loader"></div>
      </div>

      {{-- Table --}}
      <!-- <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-400 border-collapse">
            <thead class="bg-gray-100">
              <tr>
                <th rowspan="2"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                </th>
                <th colspan="6"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                  PETROLIFT
                </th>

                <th colspan="6"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                  LAND
                </th>
              </tr>
              <tr>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 1
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 2
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 3
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 4
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 5
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  TOTAL
                </th>

                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 1
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 2
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 3
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 4
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 5
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  TOTAL
                </th>
              </tr>
            </thead>

            <tbody>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">GROSS REVENUES</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>

                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">FREIGHT CHARGES</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>

                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">1000</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
                <td class="px-4 py-3 text-sm text-gray-800 border border-gray-400">500</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div> -->

      <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-400 border-collapse">
            <thead id="monthly-summary-head" class="bg-gray-100">
              <!-- <tr>
                <th rowspan="2"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                </th>
                <th colspan="6"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                  PETROLIFT
                </th>

                <th colspan="6"
                  class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-400">
                  LAND
                </th>
              </tr> -->
              <tr>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 1
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 2
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 3
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 4
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 5
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  TOTAL
                </th>

                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 1
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 2
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 3
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 4
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  Week 5
                </th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-400">
                  TOTAL
                </th>
              </tr>
            </thead>

            <tbody id="monthly-summary-body">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- jQuery --}}
  <script src="{{ asset('js/jquery.min.js') }}"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $('.spinner').hide();
    $('#summary-head').hide();
    $('#summary-body').hide();

    $(document).ready(function () {
      fetchMonthlySummary();
    });

    $('#month, #year').on('change', fetchMonthlySummary);

    function filterDate() {
      alert('testFilter');
    }


    function fetchMonthlySummary() {
      axios.get("{{ route('monthlySummary.show') }}")
        .then(res => {
          const data = res.data;
          console.log(data);

          renderHeader(data);
          renderBody(data);
        })
        .catch(err => console.error(err));
    }

    function renderHeader(data) {
      const thead = document.getElementById('monthly-summary-head');
      thead.innerHTML = '';

      const companies = [...new Set(data.map(d => d.company_name))];
      const weeks = [...new Set(data.map(d => d.week_no))]
        .sort((a, b) => a - b);

      /* Add Consolidated */
      const allColumns = [...companies, 'CONSOLIDATED'];

      /* ===== Row 1 ===== */
      let row1 = `
    <tr>
      <th rowspan="2"
        class="px-4 py-3 border border-gray-400">
      </th>
  `;

      allColumns.forEach(company => {
        row1 += `
      <th colspan="${weeks.length + 1}"
        class="px-4 py-3 text-center font-semibold border border-gray-400 bg-gray-200">
        ${company}
      </th>
    `;
      });

      row1 += `</tr>`;

      /* ===== Row 2 ===== */
      let row2 = `<tr>`;

      allColumns.forEach(() => {
        weeks.forEach(week => {
          row2 += `
        <th class="px-4 py-2 text-sm text-center border border-gray-400">
          Week ${week}
        </th>
      `;
        });

        row2 += `
      <th class="px-4 py-2 text-sm text-center border border-gray-400">
        TOTAL
      </th>
    `;
      });

      row2 += `</tr>`;

      thead.innerHTML = row1 + row2;
    }
    function renderBody(data) {
      const tbody = document.getElementById('monthly-summary-body');
      tbody.innerHTML = '';

      const companies = [...new Set(data.map(d => d.company_name))];
      const weeks = [...new Set(data.map(d => d.week_no))]
        .sort((a, b) => a - b);

      const inflowOrder = [
        "GROSS REVENUES",
        "DIRECT COSTS",
        "INDIRECT COSTS",
        "OVERHEAD",
        "OTHER EXPENSES (INCOME)",
        "CAPEX",
        "NONTRADE TRANSACTIONS",
        "STOCKHOLDERS",
        "FINANCE TRANSACTIONS"
      ];

      let inflowTypes = [...new Set(data.map(d => d.inflow_type_name))];

      inflowTypes.sort((a, b) => {
        const indexA = inflowOrder.indexOf(a);
        const indexB = inflowOrder.indexOf(b);
        if (indexA === -1) return 1;
        if (indexB === -1) return -1;
        return indexA - indexB;
      });

      inflowTypes.forEach(inflowType => {

        /* ===== LEVEL 1: INFLOW TYPE ===== */
        tbody.insertAdjacentHTML('beforeend', `
      <tr class="bg-gray-100">
        <td class="px-4 py-3 font-bold text-blue-700 border border-gray-400">
          ${inflowType}
        </td>
        ${renderEmptyCells(companies.length + 1, weeks.length)}
      </tr>
    `);

        const dailyInflows = [
          ...new Set(
            data
              .filter(d => d.inflow_type_name === inflowType)
              .map(d => d.daily_inflow_name)
          )
        ];

        dailyInflows.forEach(dailyInflow => {

          tbody.insertAdjacentHTML('beforeend', `
        <tr class="bg-gray-50">
          <td class="px-4 py-3 pl-4 font-semibold border border-gray-400">
            ${dailyInflow}
          </td>
          ${renderEmptyCells(companies.length + 1, weeks.length)}
        </tr>
      `);

          const customers = [
            ...new Set(
              data
                .filter(d =>
                  d.inflow_type_name === inflowType &&
                  d.daily_inflow_name === dailyInflow
                )
                .map(d => d.customer)
            )
          ];

          customers.forEach(customer => {

            let row = `
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 pl-8 text-sm border border-gray-400">
              ${customer}
            </td>
        `;

            /* ===================== */
            /* PER COMPANY VALUES    */
            /* ===================== */
            companies.forEach(company => {

              let companyTotal = 0;

              weeks.forEach(week => {
                const weekSum = data
                  .filter(d =>
                    d.company_name === company &&
                    d.inflow_type_name === inflowType &&
                    d.daily_inflow_name === dailyInflow &&
                    d.customer === customer &&
                    d.week_no === week
                  )
                  .reduce((sum, d) => sum + Number(d.amount_num), 0);

                companyTotal += weekSum;

                row += `
              <td class="px-4 py-3 text-right border border-gray-400">
                ${weekSum ? weekSum.toLocaleString() : ''}
              </td>
            `;
              });

              row += `
            <td class="px-4 py-3 font-semibold text-right border border-gray-400">
              ${companyTotal ? companyTotal.toLocaleString() : ''}
            </td>
          `;
            });

            /* ===================== */
            /* CONSOLIDATED VALUES   */
            /* ===================== */

            let consolidatedTotal = 0;

            weeks.forEach(week => {
              const weekSum = data
                .filter(d =>
                  d.inflow_type_name === inflowType &&
                  d.daily_inflow_name === dailyInflow &&
                  d.customer === customer &&
                  d.week_no === week
                )
                .reduce((sum, d) => sum + Number(d.amount_num), 0);

              consolidatedTotal += weekSum;

              row += `
            <td class="px-4 py-3 text-right border border-gray-400 bg-gray-50">
              ${weekSum ? weekSum.toLocaleString() : ''}
            </td>
          `;
            });

            row += `
          <td class="px-4 py-3 font-bold text-right border border-gray-400 bg-gray-100">
            ${consolidatedTotal ? consolidatedTotal.toLocaleString() : ''}
          </td>
        `;

            row += `</tr>`;
            tbody.insertAdjacentHTML('beforeend', row);

          });

        });

      });
    }
    
    function renderEmptyCells(companyCount, weekCount) {
      let html = '';

      for (let i = 0; i < companyCount; i++) {
        for (let w = 0; w < weekCount; w++) {
          html += `<td class="border border-gray-400"></td>`;
        }
        html += `<td class="border border-gray-400"></td>`;
      }

      return html;
    }

  </script>
</x-app-layout>