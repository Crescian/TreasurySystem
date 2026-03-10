<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::select(['id', 'first_name', 'last_name', 'middle_initial', 'position', 'department', 'company', 'business_unit']);

        return DataTables::of($data)
        ->addColumn('action', function ($row) {
            $btn = '<div class="flex justify-center items-center gap-2">
                            <button
                                x-data
                                @click.prevent="$dispatch(\'open-modal\', \'employee-modal\'); editEmployee(' . $row->id . ');"
                                class="px-3 py-1 bg-blue-500 text-black rounded hover:bg-blue-600 transition"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="View Bank Accounts">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>';
            return $btn;
        })
        ->rawColumns(['action']) // very important for rendering HTML
        ->make(true);
        }
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
        // Create Employee record
        $Employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'position' => $request->position,
            'department' => $request->department,
            'company' => $request->company,
            'business_unit' => $request->business_unit
        ]);

        return response()->json([
            'message' => 'Employee created successfully!',
            'Employee' => $Employee
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    // Update the employee record
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'position' => $request->position,
            'department' => $request->department,
            'company' => $request->company,
            'business_unit' => $request->business_unit
        ]);

        return response()->json([
            'message' => 'Employee updated successfully!',
            'employee' => $employee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
