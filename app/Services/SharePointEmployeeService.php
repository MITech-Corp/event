<?php

namespace App\Services;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SharePointEmployeeService
{
    public function __construct(
        protected MicrosoftGraphService $graph
    ) {}

    /**
     * Ambil daftar karyawan dari file Excel/CSV di SharePoint.
     * Mengembalikan collection of stdClass dengan property: employee_id, name, phone, email, position, office.
     *
     * @return Collection<int, object>|null null jika gagal atau belum dikonfigurasi
     */
    public function getEmployees(): ?Collection
    {
        if (! $this->graph->isConfigured()) {
            return null;
        }

        $token = $this->graph->getAccessToken();
        if (! $token) {
            return null;
        }

        $content = $this->fetchFileContent($token);
        if ($content === null) {
            return null;
        }

        $path = config('services.ms.file_path');
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $ext === 'csv'
            ? $this->parseCsv($content)
            : $this->parseExcel($content, $ext);
    }

    /**
     * @return string|null raw file content, atau null jika gagal
     */
    protected function fetchFileContent(string $token): ?string
    {
        $filePath = config('services.ms.file_path');
        $pathSegment = str_replace(' ', '%20', ltrim($filePath, '/'));
        // Graph path syntax must be: /root:/path/to/file:/content
        $pathSuffix = '/root:/'.$pathSegment.':/content';

        $userUpn = config('services.ms.user_upn');
        if (! empty($userUpn)) {
            // OneDrive for Business (personal) – link seperti ...-my.sharepoint.com/.../personal/...
            $url = 'https://graph.microsoft.com/v1.0/users/'.rawurlencode($userUpn).'/drive'.$pathSuffix;
        } else {
            // SharePoint team site / document library
            $driveId = config('services.ms.drive_id');
            $url = "https://graph.microsoft.com/v1.0/drives/{$driveId}{$pathSuffix}";
        }

        $response = Http::withToken($token)->get($url);

        if (! $response->successful()) {
            Log::error('SharePoint/OneDrive fetch file error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->body();
    }

    protected function parseCsv(string $raw): Collection
    {
        $lines = array_filter(explode("\n", trim($raw)), fn ($l) => trim($l) !== '');
        if (empty($lines)) {
            return collect();
        }

        $rows = array_map(function ($line) {
            return str_getcsv($line);
        }, $lines);

        $header = array_shift($rows);
        $header = array_map('trim', $header);
        $result = collect();

        foreach ($rows as $row) {
            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = $row[$i] ?? '';
            }
            $employee = $this->mapRowToEmployee($assoc);
            if ($employee !== null) {
                $result->push($employee);
            }
        }

        return $result->sortBy('name')->values();
    }

    /**
     * @param string $raw raw file content
     * @param string $ext e.g. xlsx, xls
     */
    protected function parseExcel(string $raw, string $ext): Collection
    {
        $tmp = tempnam(sys_get_temp_dir(), 'sp_emp_');
        if ($tmp === false) {
            Log::error('SharePoint: could not create temp file');

            return collect();
        }

        try {
            file_put_contents($tmp, $raw);
            $spreadsheet = IOFactory::load($tmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } finally {
            @unlink($tmp);
        }

        if (empty($rows)) {
            return collect();
        }

        $headerRow = array_shift($rows);
        $headerRow = array_map(fn ($v) => trim((string) $v), $headerRow);
        $result = collect();

        foreach ($rows as $row) {
            $assoc = [];
            foreach ($headerRow as $i => $key) {
                $assoc[$key] = isset($row[$i]) ? trim((string) $row[$i]) : '';
            }
            $employee = $this->mapRowToEmployee($assoc);
            if ($employee !== null) {
                $result->push($employee);
            }
        }

        return $result->sortBy('name')->values();
    }

    /**
     * Map row (associative array, key = header) ke object employee.
     * Key matching case-insensitive, berbagai nama kolom (Employee ID No, Full Name, dll.).
     */
    protected function mapRowToEmployee(array $row): ?object
    {
        $employeeId = $this->getRowValue($row, 'Employee ID No', 'employee id no', 'employee id number', 'Employee ID Number', 'employee_id', 'nip');
        if (! $employeeId) {
            return null;
        }

        return (object) [
            'employee_id' => (string) $employeeId,
            'name' => $this->getRowValue($row, 'Full Name', 'full name', 'name', 'nama') ?? '',
            'phone' => $this->getRowValue($row, 'Phone Number', 'phone number', 'Phone Number (WhatsApp)', 'phone number (whatsapp)', 'phone', 'telepon') ?? '',
            'email' => $this->getRowValue($row, 'Your Email', 'your email', 'email') ?? '',
            'position' => $this->getRowValue($row, 'Role / Position', 'role / position', 'position', 'jabatan') ?? '',
            'office' => $this->getRowValue($row, 'Office Placement', 'office placement', 'office', 'kantor') ?? '',
        ];
    }

    private function getRowValue(array $row, string ...$possibleKeys): mixed
    {
        $rowLower = array_change_key_case(array_map(fn ($v) => (string) $v, $row), CASE_LOWER);

        foreach ($possibleKeys as $needle) {
            $needleLower = strtolower(trim($needle));
            foreach ($rowLower as $header => $value) {
                if (strtolower(trim($header)) === $needleLower && trim($value) !== '') {
                    return $value;
                }
            }
        }

        return null;
    }
}
