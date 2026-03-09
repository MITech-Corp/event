<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImportController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->paginate(20);

        return view('employees.index', compact('employees'));
    }

    public function showImportForm()
    {
        return view('employees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new EmployeesImport();
        Excel::import($import, $request->file('file'));

        $count = $import->getImportedCount();

        return redirect()
            ->route('employees.import')
            ->with('success', "Employee data imported successfully. Total records processed: {$count}");
    }
}

