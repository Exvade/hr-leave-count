<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportController extends Controller
{
    private function getFilteredData(Request $request)
    {
        $search = $request->input('search');
        $department = $request->input('department');

        return Employee::with('leaveRecords')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('employee_id', 'like', '%' . $search . '%');
                });
            })
            ->when($department, function ($query) use ($department) {
                $query->where('position', $department);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function excel(Request $request)
    {
        $employees = $this->getFilteredData($request);

        $exportData = collect();

        foreach ($employees as $index => $emp) {
            $exportData->push([
                'No' => $index + 1,
                'NIK' => $emp->employee_id,
                'Nama Lengkap' => $emp->name,
                'Departemen / Jabatan' => $emp->position,
                'Tgl Bergabung' => $emp->join_date->format('d M Y'),
                'Hak Cuti' => $emp->leave_quota,
                'Terpakai' => $emp->leave_taken,
                'Sisa Cuti' => $emp->remaining_leave,
            ]);
        }

        // Tambahkan baris kosong untuk jarak
        $exportData->push(['No' => '', 'NIK' => '', 'Nama Lengkap' => '', 'Departemen / Jabatan' => '', 'Tgl Bergabung' => '', 'Hak Cuti' => '', 'Terpakai' => '', 'Sisa Cuti' => '']);
        $exportData->push(['No' => '', 'NIK' => '', 'Nama Lengkap' => '', 'Departemen / Jabatan' => '', 'Tgl Bergabung' => '', 'Hak Cuti' => '', 'Terpakai' => '', 'Sisa Cuti' => '']);

        // Tambahkan baris penandatangan
        $exportData->push([
            'No' => '',
            'NIK' => 'Direkap,',
            'Nama Lengkap' => '',
            'Departemen / Jabatan' => 'Diperiksa,',
            'Tgl Bergabung' => '',
            'Hak Cuti' => '',
            'Terpakai' => 'Diketahui,',
            'Sisa Cuti' => '',
        ]);

        $exportData->push(['No' => '', 'NIK' => '', 'Nama Lengkap' => '', 'Departemen / Jabatan' => '', 'Tgl Bergabung' => '', 'Hak Cuti' => '', 'Terpakai' => '', 'Sisa Cuti' => '']);
        $exportData->push(['No' => '', 'NIK' => '', 'Nama Lengkap' => '', 'Departemen / Jabatan' => '', 'Tgl Bergabung' => '', 'Hak Cuti' => '', 'Terpakai' => '', 'Sisa Cuti' => '']);
        $exportData->push(['No' => '', 'NIK' => '', 'Nama Lengkap' => '', 'Departemen / Jabatan' => '', 'Tgl Bergabung' => '', 'Hak Cuti' => '', 'Terpakai' => '', 'Sisa Cuti' => '']);

        $exportData->push([
            'No' => '',
            'NIK' => 'Wisnu Aryo Novanto',
            'Nama Lengkap' => '',
            'Departemen / Jabatan' => 'Anto Permana Sidik',
            'Tgl Bergabung' => '',
            'Hak Cuti' => '',
            'Terpakai' => 'Supriyanto',
            'Sisa Cuti' => '',
        ]);

        return (new FastExcel($exportData))->download('Data_Karyawan_Cuti.xlsx');
    }

    public function pdf(Request $request)
    {
        $employees = $this->getFilteredData($request);
        $department = $request->input('department');

        $pdf = Pdf::loadView('exports.employees-pdf', compact('employees', 'department'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Data_Karyawan_Cuti.pdf');
    }
}
