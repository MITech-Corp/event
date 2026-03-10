<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('employee_identifier')->nullable()->after('id');
            $table->string('employee_name')->nullable()->after('employee_identifier');
            $table->string('employee_position')->nullable()->after('employee_name');
            $table->string('employee_office')->nullable()->after('employee_position');
        });

        $rows = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->select('attendances.id', 'employees.employee_id as identifier', 'employees.name', 'employees.position', 'employees.office')
            ->get();

        foreach ($rows as $row) {
            DB::table('attendances')->where('id', $row->id)->update([
                'employee_identifier' => $row->identifier,
                'employee_name' => $row->name,
                'employee_position' => $row->position,
                'employee_office' => $row->office,
            ]);
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->after('id')->constrained('employees')->nullOnDelete();
        });

        $employees = DB::table('employees')->pluck('id', 'employee_id');
        foreach (DB::table('attendances')->get() as $row) {
            $empId = $employees[$row->employee_identifier] ?? null;
            if ($empId) {
                DB::table('attendances')->where('id', $row->id)->update(['employee_id' => $empId]);
            }
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['employee_identifier', 'employee_name', 'employee_position', 'employee_office']);
        });
    }
};
