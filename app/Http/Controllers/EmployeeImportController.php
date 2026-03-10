<?php

namespace App\Http\Controllers;

use App\Services\SharePointEmployeeService;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeImportController extends Controller
{
    public function index(SharePointEmployeeService $sharePoint)
    {
        $employees = $sharePoint->getEmployees();

        if ($employees === null) {
            return view('employees.index', [
                'employees' => new LengthAwarePaginator([], 0, 20),
                'fromSharePoint' => false,
                'sharePointConfigured' => false,
            ]);
        }

        $page = (int) request('page', 1);
        $perPage = 20;
        $total = $employees->count();
        $items = $employees->forPage($page, $perPage)->values();
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('employees.index', [
            'employees' => $paginator,
            'fromSharePoint' => true,
            'sharePointConfigured' => true,
        ]);
    }
}

