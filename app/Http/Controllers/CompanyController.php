<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Validation\Rule;


class CompanyController extends Controller
{

    public function getCompanyBalances(Request $request)
    {
        if ($request->ajax()) {
            $companies = Company::leftJoin('bank_accounts', 'companies.id', '=', 'bank_accounts.company_id')
                ->leftJoin('banks', 'banks.id', '=', 'bank_accounts.banks_id')
                ->select([
                    'companies.id',
                    'companies.company',
                    'banks.id AS bankss_id',
                    \DB::raw("COALESCE(banks.bank, 'No Bank') AS bank_name"),
                    \DB::raw("COALESCE(SUM(bank_accounts.balance), 0) AS total_balance")
                ])
                ->groupBy('companies.id', 'companies.company', 'banks.id', 'banks.bank');

            return DataTables::of($companies)
                ->filterColumn('company', function ($query, $keyword) {
                    $query->whereRaw('LOWER(companies.company) LIKE ?', ["%".strtolower($keyword)."%"]);
                })
                ->filterColumn('bank_name', function ($query, $keyword) {
                    $query->whereRaw('LOWER(banks.bank) LIKE ?', ["%".strtolower($keyword)."%"]);
                })
                ->orderColumn('company', function ($query, $order) {
                    $query->orderBy('companies.company', $order);
                })
                ->orderColumn('bank_name', function ($query, $order) {
                    $query->orderBy('banks.bank', $order);
                })
                ->addColumn('total_balance', function ($row) {
                    return number_format($row->total_balance, 2); // optional formatting
                })
                ->addColumn('action', function ($row) {
                    $banksId = $row->bankss_id ?? 'null';
                    return '
                        <div class="flex justify-center items-center gap-2">
                            <button
                                x-data
                                @click.prevent="$dispatch(\'open-modal\', \'view-bank-account\'); viewBankAccounts(' . $banksId . ');"
                                class="px-3 py-1 bg-blue-500 text-black rounded hover:bg-blue-600 transition"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="View Bank Accounts">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('companies.balances');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string|unique:companies,company',
        ]);

        $company = Company::create([
            'company' => $request->company,
            'address' => $request->address,
            'contact' => $request->contact,
            'type' => $request->type
        ]);

        return response()->json([
            'message' => 'Company added successfully.',
            'id' => $company->id
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $companies = Company::select('id', 'company', 'address', 'contact')
            ->where('type', 'internal')
            ->orderBy('company', 'asc')
            ->get();

        // Return the output as a JSON response
        return response()->json($companies);
    }
    public function showVendor(Company $company)
    {
        $companies = Company::select('id', 'company', 'address', 'contact')
            ->where('type', 'vendor')
            ->orderBy('company', 'asc')
            ->get();

        // Return the output as a JSON response
        return response()->json($companies);
    }
    public function showCustomer(Company $company)
    {
        $companies = Company::select('id', 'company', 'address', 'contact')
            ->where('type', 'customer')
            ->orderBy('company', 'asc')
            ->get();

        // Return the output as a JSON response
        return response()->json($companies);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $id)
    {
        // Fetch the employee by ID
        // $companies = Company::findOrFail($id);

        // // Return the employee details as JSON
        // return response()->json($id);
        return response($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'address' => 'required|string|max:255',
            'contact' => 'required|string',
        ]);

        // Find the company by ID
        $company = Company::findOrFail($id);

        // Update company details
        $company->address = $request->input('address');
        $company->contact = $request->input('contact');
        $company->save(); // Save the updated details

        return response()->json([
            'message' => 'Company updated successfully',
            'company' => $company
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
