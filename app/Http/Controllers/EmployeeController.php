<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('employees.index', compact('employees', 'search'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'leave_taken' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            $employee->update([
                'leave_taken' => $request->leave_taken,
            ]);
            DB::commit();

            return back()->with('success', 'Data cuti berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update leave taken: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();

            $collection = (new FastExcel)->withoutHeaders()->import($request->file('file'));

            foreach ($collection as $row) {
                // Ensure array has enough columns (Index 1: EMPL.ID, 2: Nama, 3: Jabatan, 4: Tanggal)
                if (! isset($row[1]) || ! isset($row[2]) || ! isset($row[3]) || ! isset($row[4])) {
                    continue;
                }

                // Skip header rows or title row
                if ($row[1] === 'EMPL.ID' || stripos((string) $row[0], 'Daftar Karyawan') !== false) {
                    continue;
                }

                $employeeId = $row[1];

                // Parse date
                $joinDate = null;
                $rawDate = $row[4];

                if ($rawDate instanceof \DateTimeInterface) {
                    $joinDate = $rawDate->format('Y-m-d');
                } else {
                    // Try parsing string date
                    try {
                        $joinDate = Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        try {
                            $joinDate = Carbon::parse($rawDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $joinDate = null;
                        }
                    }
                }

                if (! $joinDate) {
                    continue;
                }

                Employee::updateOrCreate(
                    ['employee_id' => $employeeId],
                    [
                        'name' => $row[2],
                        'position' => $row[3],
                        'join_date' => $joinDate,
                    ]
                );
            }

            DB::commit();

            return back()->with('success', 'Data karyawan berhasil diimpor.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error import excel: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengimpor data. Pastikan format Excel sesuai. (' . $e->getMessage() . ')');
        }
    }
}
