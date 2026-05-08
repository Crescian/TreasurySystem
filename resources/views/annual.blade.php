<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Annual Summary') }}
    </h2>
  </x-slot>

  <style>
    .loader {
      position: relative; width: 2.5em; height: 2.5em; transform: rotate(165deg);
    }
    .loader:before, .loader:after {
      content: ""; position: absolute; top: 50%; left: 50%;
      display: block; width: 0.5em; height: 0.5em;
      border-radius: 0.25em; transform: translate(-50%, -50%);
    }
    .loader:before { animation: before8 2s infinite; }
    .loader:after  { animation: after6  2s infinite; }
    @keyframes before8 {
      0%   { width: 0.5em; box-shadow: 1em -0.5em rgba(225,20,98,.75), -1em 0.5em rgba(111,202,220,.75); }
      35%  { width: 2.5em; box-shadow: 0 -0.5em rgba(225,20,98,.75), 0 0.5em rgba(111,202,220,.75); }
      70%  { width: 0.5em; box-shadow: -1em -0.5em rgba(225,20,98,.75), 1em 0.5em rgba(111,202,220,.75); }
      100% { box-shadow: 1em -0.5em rgba(225,20,98,.75), -1em 0.5em rgba(111,202,220,.75); }
    }
    @keyframes after6 {
      0%   { height: 0.5em; box-shadow: 0.5em 1em rgba(61,184,143,.75), -0.5em -1em rgba(233,169,32,.75); }
      35%  { height: 2.5em; box-shadow: 0.5em 0 rgba(61,184,143,.75), -0.5em 0 rgba(233,169,32,.75); }
      70%  { height: 0.5em; box-shadow: 0.5em -1em rgba(61,184,143,.75), -0.5em 1em rgba(233,169,32,.75); }
      100% { box-shadow: 0.5em 1em rgba(61,184,143,.75), -0.5em -1em rgba(233,169,32,.75); }
    }
  </style>

  <div class="py-6">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Title + Filters --}}
      <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-xl font-semibold text-gray-800 tracking-tight">
          PETROLIFT INC. SOURCES AND USE OF FUNDS (Annual)
        </h1>

        <div class="flex items-center gap-2">
          <label for="year" class="text-sm font-medium text-gray-600">Year</label>
          <select id="year" name="year" onchange="fetchAnnual();"
            class="h-9 w-28 rounded-md border border-gray-300 bg-white px-2 pr-8 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
            style="width:100px">
            @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
              <option value="{{ $y }}" @selected((int) $year === $y)>{{ $y }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Spinner --}}
      <div class="spinner fixed inset-0 z-50 hidden items-center justify-center bg-white/70 backdrop-blur-sm">
        <div class="loader"></div>
      </div>

      <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-400 border-collapse">
            <thead id="annual-summary-head" class="bg-gray-100"></thead>
            <tbody id="annual-summary-body"></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script src="{{ asset('js/jquery.min.js') }}"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    const MONTH_NAMES = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    $(document).ready(function () { fetchAnnual(); });
    $('#year').on('change', fetchAnnual);

    // ── fetch ─────────────────────────────────────────────────────────────────
    function fetchAnnual() {
      $('.spinner').removeClass('hidden').addClass('flex');

      const year = $('#year').val();
      axios.get("{{ route('annual.data') }}", { params: { year } })
        .then(res => {
          const data                  = res.data.details;
          const totals                = res.data.inflow_totals;
          const before_other_capex    = res.data.before_other_capex;
          const beforeAffiliate       = res.data.before_affiliate;
          const afterCapex            = res.data.after_capex;
          const afterAffiliateAdvances= res.data.after_affiliate_advances;
          const financeTransactions   = res.data.finance_transactions;
          const netCash               = res.data.net_cash;
          const beginningBalanceRow   = res.data.beginning_balance_row;
          const beginningBalanceLabel = res.data.beginning_balance_label;
          const endingBalance         = res.data.ending_balance;

          renderHeader(data);
          renderBody(
            data, totals,
            before_other_capex, beforeAffiliate, afterCapex,
            afterAffiliateAdvances, financeTransactions,
            netCash, beginningBalanceRow, beginningBalanceLabel, endingBalance
          );
        })
        .catch(err => console.error(err))
        .finally(() => $('.spinner').addClass('hidden').removeClass('flex'));
    }

    // ── header (companies × months + TOTAL) ───────────────────────────────────
    function renderHeader(data) {
      const thead    = document.getElementById('annual-summary-head');
      const companies  = [...new Set(data.map(d => d.company_name))];
      const months     = [...new Set(data.map(d => d.month_no))].sort((a, b) => a - b);
      const allColumns = [...companies, 'CONSOLIDATED'];

      let row1 = `<tr><th rowspan="2" class="px-4 py-3 border border-gray-400"></th>`;
      allColumns.forEach(company => {
        row1 += `<th colspan="${months.length + 1}"
          class="px-4 py-3 text-center font-semibold border border-gray-400 bg-gray-200">
          ${company}</th>`;
      });
      row1 += `</tr>`;

      let row2 = `<tr>`;
      allColumns.forEach(() => {
        months.forEach(m => {
          row2 += `<th class="px-4 py-2 text-sm text-center border border-gray-400">${MONTH_NAMES[m - 1]}</th>`;
        });
        row2 += `<th class="px-4 py-2 text-sm text-center border border-gray-400">TOTAL</th>`;
      });
      row2 += `</tr>`;

      thead.innerHTML = row1 + row2;
    }

    // ── body ──────────────────────────────────────────────────────────────────
    function renderBody(
      data, totals,
      before_other_capex, beforeAffiliate, afterCapex,
      afterAffiliateAdvances, financeTransactions,
      netCash, beginningBalanceRow, beginningBalanceLabel, endingBalance
    ) {
      const tbody = document.getElementById('annual-summary-body');
      tbody.innerHTML = '';

      const fmt = (val) => {
        if (val === 0 || val == null) return '-';
        if (val < 0) return `<span style="color:red;">(${Math.abs(val).toLocaleString()})</span>`;
        return val.toLocaleString();
      };

      const companies = [...new Set(data.map(d => d.company_name))];
      const months    = [...new Set(data.map(d => d.month_no))].sort((a, b) => a - b);

      const inflowOrder = [
        'GROSS REVENUES','DIRECT COSTS','INDIRECT COSTS','OVERHEAD',
        'OTHER EXPENSES (INCOME)','CAPEX','NONTRADE TRANSACTIONS',
        'STOCKHOLDERS','FINANCE TRANSACTIONS'
      ];
      const totalMapping = {
        'CAPEX'                   : 'SUB-TOTAL',
        'NONTRADE TRANSACTIONS'   : 'NET ADVANCES TO AFFILIATE',
        'STOCKHOLDERS'            : 'NET ADVANCES TO STOCKHOLDERS',
        'FINANCE TRANSACTIONS'    : 'NET FINANCE TRANSACTIONS',
        'OTHER EXPENSES (INCOME)' : 'OTHER INFLOW / OUTFLOW',
      };

      let inflowTypes = [...new Set(data.map(d => d.inflow_type_name))];
      inflowTypes.sort((a, b) => {
        const ia = inflowOrder.indexOf(a), ib = inflowOrder.indexOf(b);
        if (ia === -1) return 1;
        if (ib === -1) return -1;
        return ia - ib;
      });

      inflowTypes.forEach(inflowType => {
        // Section header row
        tbody.insertAdjacentHTML('beforeend', `
          <tr class="bg-gray-100">
            <td class="px-4 py-3 border border-gray-400" style="font-weight:bold;">${inflowType}</td>
            ${renderEmptyCells(companies.length + 1, months.length)}
          </tr>`);

        const dailyInflows = [...new Set(
          data.filter(d => d.inflow_type_name === inflowType).map(d => d.daily_inflow_name)
        )];

        dailyInflows.forEach(dailyInflow => {
          tbody.insertAdjacentHTML('beforeend', `
            <tr>
              <td class="px-4 py-3 pl-4"><i><strong><u>${dailyInflow}</u></strong></i></td>
              ${renderEmptyCells(companies.length + 1, months.length)}
            </tr>`);

          const customers = [...new Set(
            data.filter(d => d.inflow_type_name === inflowType && d.daily_inflow_name === dailyInflow).map(d => d.customer)
          )];

          customers.forEach(customer => {
            let row = `<tr class="hover:bg-gray-50">
              <td class="px-4 py-3 pl-8 text-sm border border-gray-400">${customer}</td>`;

            companies.forEach(company => {
              let companyTotal = 0;
              months.forEach(month => {
                const monthSum = data
                  .filter(d => d.company_name === company && d.inflow_type_name === inflowType &&
                               d.daily_inflow_name === dailyInflow && d.customer === customer && d.month_no === month)
                  .reduce((s, d) => s + Number(d.amount_num), 0);
                companyTotal += monthSum;
                row += `<td class="px-4 py-3 text-right border border-gray-400">${fmt(monthSum)}</td>`;
              });
              row += `<td class="px-4 py-3 font-semibold text-right border border-gray-400">${fmt(companyTotal)}</td>`;
            });

            let conTotal = 0;
            months.forEach(month => {
              const monthSum = data
                .filter(d => d.inflow_type_name === inflowType && d.daily_inflow_name === dailyInflow &&
                             d.customer === customer && d.month_no === month)
                .reduce((s, d) => s + Number(d.amount_num), 0);
              conTotal += monthSum;
              row += `<td class="px-4 py-3 text-right border border-gray-400 bg-gray-50">${fmt(monthSum)}</td>`;
            });
            row += `<td class="px-4 py-3 font-bold text-right border border-gray-400 bg-gray-100">${fmt(conTotal)}</td></tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
          });
        });

        // Section total row
        const lookupType = totalMapping[inflowType] || inflowType;
        const totalLabel = totalMapping[inflowType] ? totalMapping[inflowType] : `TOTAL ${inflowType}`;

        let totalRow = `<tr class="bg-white-100">
          <td class="px-4 py-3 font-bold border border-gray-400">${totalLabel}</td>`;

        companies.forEach(company => {
          let companyTotal = 0;
          months.forEach(month => {
            const record = totals.find(t => t.company_name === company && t.inflow_type_name === lookupType && t.month_no === month);
            const value  = record ? Number(record.inflow_total) : 0;
            companyTotal += value;
            totalRow += `<td class="px-4 py-3 text-right border border-gray-400 font-semibold">${fmt(value)}</td>`;
          });
          totalRow += `<td class="px-4 py-3 text-right border border-gray-400 font-bold">${fmt(companyTotal)}</td>`;
        });

        let conTotal = 0;
        months.forEach(month => {
          const monthTotal = totals
            .filter(t => t.inflow_type_name === lookupType && t.month_no === month)
            .reduce((s, t) => s + Number(t.inflow_total), 0);
          conTotal += monthTotal;
          totalRow += `<td class="px-4 py-3 text-right border border-gray-400 bg-white-50 font-semibold">${fmt(monthTotal)}</td>`;
        });
        totalRow += `<td class="px-4 py-3 text-right border border-gray-400 bg-yellow-200 font-bold">${fmt(conTotal)}</td></tr>`;
        tbody.insertAdjacentHTML('beforeend', totalRow);
      });

      // ── insert computed rows ───────────────────────────────────────────────
      insertComputation(
        [
          ...Object.values(before_other_capex),
          ...Object.values(beforeAffiliate),
          ...Object.values(afterCapex),
          ...Object.values(afterAffiliateAdvances),
          ...Object.values(financeTransactions),
          ...Object.values(netCash),
          ...Object.values(beginningBalanceRow),
          ...Object.values(endingBalance),
        ],
        companies, months, beginningBalanceLabel
      );
    }

    function renderEmptyCells(companyCount, colCount) {
      let html = '';
      for (let i = 0; i < companyCount; i++) {
        for (let c = 0; c < colCount; c++) {
          html += `<td class="border border-gray-400 text-center align-middle">-</td>`;
        }
        html += `<td class="border border-gray-400 text-center align-middle">-</td>`;
      }
      return html;
    }

    // ── insert computed rows ──────────────────────────────────────────────────
    function insertComputation(computationData, companies, months, beginningBalanceLabel) {
      const tbody = document.getElementById('annual-summary-body');
      const computation = Array.isArray(computationData) ? computationData : Object.values(computationData || {});

      const grouped = {};
      computation.forEach(item => {
        if (!item?.inflow_type_name) return;
        if (!grouped[item.inflow_type_name]) grouped[item.inflow_type_name] = [];
        grouped[item.inflow_type_name].push(item);
      });

      const steps = [
        ['INFLOW / (OUTFLOW) BEFORE OTHER CAPEX', 'OTHER INFLOW / OUTFLOW',            'beforebegin'],
        ['INFLOW / (OUTFLOW) BEF ADV TO AFF.',    'SUB-TOTAL',                          'afterend'],
        ['INFLOW / (OUTFLOW) AFT ADV TO AFF.',    'NET ADVANCES TO AFFILIATE',          'afterend'],
        ['INFLOW / (OUTFLOW) FINANCE TRANSACTIONS','NET ADVANCES TO STOCKHOLDERS',      'afterend'],
        ['NET CASH INFLOW',                        'NET FINANCE TRANSACTIONS',           'afterend'],
        [beginningBalanceLabel,                    'NET CASH INFLOW',                    'afterend'],
        ['ENDING BALANCE',                         beginningBalanceLabel,                'afterend'],
      ];

      steps.forEach(([inflowName, targetText, position]) => {
        const records   = grouped[inflowName] || [];
        const allRows   = [...tbody.querySelectorAll('tr')];
        const targetRow = allRows.find(r => r.querySelector('td')?.textContent.trim().includes(targetText));
        if (!targetRow) return;
        targetRow.insertAdjacentHTML(position, buildRowHTML(inflowName, records, companies, months));
      });
    }

    function buildRowHTML(inflowName, records, companies, cols) {
      const fmt = (val) => {
        if (val == null || val === 0) return '-';
        if (val < 0) return `<span style="color:red;">(${Math.abs(val).toLocaleString()})</span>`;
        return val.toLocaleString();
      };

      const isEnding    = inflowName.startsWith('ENDING');
      const isBeginning = /BEGINNING BALANCE \d/.test(inflowName);
      const bgClass = isEnding ? 'bg-yellow-100' : (isBeginning ? 'bg-blue-50' : '');

      // month_no is the column identifier for annual
      const colKey = records[0]?.month_no !== undefined ? 'month_no' : 'week_no';

      const sortedCols = [...cols].sort((a, b) => a - b);
      const firstCol   = sortedCols[0];
      const lastCol    = sortedCols[sortedCols.length - 1];

      let html = `<tr class="bg-white-100">
        <td class="px-4 py-3 font-semibold border border-gray-400 ${bgClass}">${inflowName}</td>`;

      companies.forEach(company => {
        const companyMatch = r => r.company_name?.trim().toLowerCase() === company?.trim().toLowerCase();
        let companyTotal = 0;
        cols.forEach(col => {
          const rec   = records.find(r => companyMatch(r) && r[colKey] === col);
          const value = rec ? Number(rec.inflow_total ?? 0) : 0;
          companyTotal += value;
          html += `<td class="px-4 py-3 text-right border border-gray-400 bg-gray-50">${fmt(value)}</td>`;
        });

        // Beginning balance total = first period value; ending balance total = last period value
        let totalValue = companyTotal;
        if (isBeginning) {
          const rec = records.find(r => companyMatch(r) && r[colKey] === firstCol);
          totalValue = rec ? Number(rec.inflow_total ?? 0) : 0;
        } else if (isEnding) {
          const rec = records.find(r => companyMatch(r) && r[colKey] === lastCol);
          totalValue = rec ? Number(rec.inflow_total ?? 0) : 0;
        }
        html += `<td class="px-4 py-3 text-right border border-gray-400 font-bold">${fmt(totalValue)}</td>`;
      });

      let conTotal = 0;
      cols.forEach(col => {
        const colTotal = records.filter(r => r[colKey] === col)
          .reduce((s, r) => s + Number(r.inflow_total || 0), 0);
        conTotal += colTotal;
        html += `<td class="px-4 py-3 text-right border border-gray-400 bg-gray-50">${fmt(colTotal)}</td>`;
      });

      // Consolidated total: first/last period sum across all companies
      let conTotalValue = conTotal;
      if (isBeginning) {
        conTotalValue = records.filter(r => r[colKey] === firstCol)
          .reduce((s, r) => s + Number(r.inflow_total || 0), 0);
      } else if (isEnding) {
        conTotalValue = records.filter(r => r[colKey] === lastCol)
          .reduce((s, r) => s + Number(r.inflow_total || 0), 0);
      }
      html += `<td class="px-4 py-3 text-right border border-gray-400 bg-gray-200 font-bold">${fmt(conTotalValue)}</td></tr>`;
      return html;
    }
  </script>
</x-app-layout>
