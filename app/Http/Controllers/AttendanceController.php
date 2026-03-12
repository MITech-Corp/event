<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\SharePointEmployeeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function checkin(Request $request, SharePointEmployeeService $sharePoint)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'string'],
        ]);

        $employees = $sharePoint->getEmployees();
        if ($employees === null) {
            return back()
                ->withInput()
                ->with('error', 'Employee list is not available. Please configure SharePoint.');
        }

        $employee = $employees->firstWhere('employee_id', trim($data['employee_id']));
        if (! $employee) {
            return back()
                ->withInput()
                ->with('error', 'Employee ID not found');
        }

        $today = Carbon::today();
        $alreadyCheckedIn = Attendance::where('employee_identifier', $employee->employee_id)
            ->whereDate('checkin_time', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return back()
                ->withInput()
                ->with('warning', 'You have already checked in');
        }

        Attendance::create([
            'employee_identifier' => $employee->employee_id,
            'employee_name' => $employee->name,
            'employee_position' => $employee->position,
            'employee_office' => $employee->office,
            'checkin_time' => now(),
        ]);

        return back()
            ->with('success', 'Attendance recorded successfully. Welcome, ' . $employee->name)
            ->with('employee_name', $employee->name)
            ->with('employee_position', $employee->position);
    }

    public function adminIndex(SharePointEmployeeService $sharePoint)
    {
        $filter = request('filter', 'sudah_hadir');
        $attendances = Attendance::orderByDesc('checkin_time')->paginate(20);

        $absentEmployees = null;
        if ($filter === 'belum_hadir') {
            $employees = $sharePoint->getEmployees();
            if ($employees !== null) {
                $hadirIds = Attendance::query()->distinct()->pluck('employee_identifier')->map(fn ($id) => (string) $id)->flip();
                $absentEmployees = $employees->filter(fn ($e) => ! $hadirIds->has(trim((string) $e->employee_id)))->values();
                $page = (int) request('page', 1);
                $perPage = 20;
                $total = $absentEmployees->count();
                $items = $absentEmployees->forPage($page, $perPage)->values();
                $absentEmployees = new LengthAwarePaginator(
                    $items,
                    $total,
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
        }

        return view('attendances.index', compact('attendances', 'filter', 'absentEmployees'));
    }

    public function reset()
    {
        Attendance::query()->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Rekap kehadiran telah direset. Semua data absensi telah dihapus.');
    }

    public function findEmployee(Request $request, SharePointEmployeeService $sharePoint)
    {
        $request->validate([
            'employee_id' => ['required', 'string'],
        ]);

        $employees = $sharePoint->getEmployees();
        if ($employees === null) {
            return response()->json(['message' => 'Not available'], 503);
        }

        $employee = $employees->firstWhere('employee_id', trim($request->query('employee_id')));
        if (! $employee) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'name' => $employee->name,
            'position' => $employee->position,
        ]);
    }
}

