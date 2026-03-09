<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class EmployeesImport implements ToCollection, WithHeadingRow
{
    protected int $imported = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $employeeId = $row['employee id number'] ?? null;

            if (! $employeeId) {
                continue;
            }

            Employee::updateOrCreate(
                ['employee_id' => $employeeId],
                [
                    'name' => $row['full name'] ?? null,
                    'phone' => $row['phone number (whatsapp)'] ?? null,
                    'email' => $row['your email'] ?? null,
                    'position' => $row['role / position'] ?? null,
                    'office' => $row['office placement'] ?? null,
                ]
            );

            $this->imported++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }
}

