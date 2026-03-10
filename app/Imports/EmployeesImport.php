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

    /**
     * Ambil nilai dari row dengan key case-insensitive (header Excel bisa kapital).
     */
    private function getRowValue(Collection|array $row, string ...$possibleKeys): mixed
    {
        $arr = $row instanceof Collection ? $row->all() : $row;

        foreach ($possibleKeys as $needle) {
            $needleLower = strtolower(trim($needle));
            foreach ($arr as $header => $value) {
                if (strtolower(trim((string) $header)) === $needleLower && (string) $value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            // Support "Employee ID No" (Google Form export) dan "Employee ID Number"
            $employeeId = $this->getRowValue($row, 'Employee ID No', 'employee id no', 'employee id number', 'Employee ID Number', 'employee_id', 'nip');

            if (! $employeeId) {
                continue;
            }

            Employee::updateOrCreate(
                ['employee_id' => $employeeId],
                [
                    'name' => $this->getRowValue($row, 'Full Name', 'full name', 'name', 'nama'),
                    'phone' => $this->getRowValue($row, 'Phone Number', 'phone number', 'Phone Number (WhatsApp)', 'phone number (whatsapp)', 'phone', 'telepon'),
                    'email' => $this->getRowValue($row, 'Your Email', 'your email', 'email'),
                    'position' => $this->getRowValue($row, 'Role / Position', 'role / position', 'position', 'jabatan'),
                    'office' => $this->getRowValue($row, 'Office Placement', 'office placement', 'office', 'kantor'),
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

