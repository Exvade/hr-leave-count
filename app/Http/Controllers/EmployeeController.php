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

        $employees = Employee::with('leaveRecords')->when($search, function ($query) use ($search) {
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

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();
            Employee::create([
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'position' => $request->position,
                'join_date' => $request->join_date,
            ]);
            DB::commit();

            return back()->with('success', 'Karyawan baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store employee: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menambahkan karyawan baru.');
        }
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $employee->update([
                'name' => $request->name,
                'position' => $request->position,
            ]);
            DB::commit();

            return back()->with('success', 'Data karyawan berhasil diperbarui.');
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

    public function destroy(Employee $employee)
    {
        try {
            DB::beginTransaction();
            $employee->delete();
            DB::commit();

            return back()->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete employee: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus data karyawan.');
        }
    }

    public function importLeaves(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();

            $collection = (new FastExcel)->withoutHeaders()->import($request->file('file'));

            foreach ($collection as $index => $row) {
                // NIK index 1, Dari Tanggal index 3, Sampai Tanggal index 4, Durasi index 5
                if (! isset($row[1]) || ! isset($row[3]) || ! isset($row[4]) || ! isset($row[5])) {
                    continue;
                }

                $nik = trim($row[1]);
                $durasi = trim($row[5]);

                if (strtolower($nik) === 'nik' || (stripos((string) $row[0], 'N') !== false && $index === 0)) {
                    continue;
                }

                if (! is_numeric($durasi)) {
                    continue;
                }

                $employee = Employee::where('employee_id', $nik)->first();
                if (! $employee) {
                    continue;
                }

                $parseDate = function ($rawDate) {
                    if ($rawDate instanceof \DateTimeInterface) {
                        return $rawDate->format('Y-m-d');
                    } else {
                        try {
                            return Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            try {
                                return Carbon::parse($rawDate)->format('Y-m-d');
                            } catch (\Exception $e) {
                                return null;
                            }
                        }
                    }
                };

                $startDate = $parseDate($row[3]);
                $endDate = $parseDate($row[4]);

                if ($startDate && $endDate) {
                    $employee->leaveRecords()->create([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'duration' => (int) $durasi,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Riwayat cuti berhasil diimpor.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error import rekap cuti: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengimpor rekap cuti. Pastikan format Excel sesuai. (' . $e->getMessage() . ')');
        }
    }

    public function storeLeave(Request $request, Employee $employee)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $employee->leaveRecords()->create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => $request->duration,
            ]);
            DB::commit();

            return back()->with('success', 'Riwayat cuti baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store leave: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menambahkan riwayat cuti.');
        }
    }
}
