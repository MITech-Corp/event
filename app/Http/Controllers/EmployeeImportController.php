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
                'search' => null,
            ]);
        }

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $lower = mb_strtolower($search);
            $employees = $employees->filter(function ($e) use ($lower) {
                $fields = [
                    (string) ($e->employee_id ?? ''),
                    (string) ($e->name ?? ''),
                    (string) ($e->email ?? ''),
                    (string) ($e->position ?? ''),
                    (string) ($e->office ?? ''),
                ];

                foreach ($fields as $field) {
                    if (str_contains(mb_strtolower($field), $lower)) {
                        return true;
                    }
                }

                return false;
            })->values();
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
            'search' => $search !== '' ? $search : null,
        ]);
    }
}

