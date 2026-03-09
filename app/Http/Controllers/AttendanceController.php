<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function checkin(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'string'],
        ]);

        $employee = Employee::where('employee_id', $data['employee_id'])->first();

        if (! $employee) {
            return back()
                ->withInput()
                ->with('error', 'Nomor karyawan tidak terdaftar');
        }

        $today = Carbon::today();

        $alreadyCheckedIn = Attendance::where('employee_id', $employee->id)
            ->whereDate('checkin_time', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return back()
                ->withInput()
                ->with('warning', 'Anda sudah melakukan absensi hari ini');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'checkin_time' => now(),
        ]);

        return back()
            ->with('success', 'Absensi berhasil. Selamat datang, ' . $employee->name);
    }

    public function adminIndex()
    {
        $attendances = Attendance::with('employee')
            ->orderByDesc('checkin_time')
            ->paginate(20);

        return view('attendances.index', compact('attendances'));
    }

    public function findEmployee(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'string'],
        ]);

        $employee = Employee::where('employee_id', $request->query('employee_id'))->first();

        if (! $employee) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'name' => $employee->name,
            'position' => $employee->position,
        ]);
    }
}

