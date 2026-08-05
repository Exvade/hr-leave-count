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
            $details = $emp->leave_details;
            $exportData->push([
                'NO' => $index + 1,
                'DEPARTEMEN' => $emp->position,
                'NIK' => $emp->employee_id,
                'NAMA' => $emp->name,
                'TGL MASUK' => $emp->join_date->format('d M Y'),
                'ANNIV SAAT INI' => $details['anniv_saat_ini'],
                'ANNIV SEBELUMNYA' => $details['anniv_sebelumnya'],
                'HAK PERIODE SEBELUMNYA' => $details['hak_periode_sebelumnya'],
                'DIPAKAI PERIODE SEBELUMNYA' => $details['dipakai_periode_sebelumnya'],
                'SISA SALDO PERIODE SEBELUMNYA' => $details['sisa_periode_sebelumnya'],
                'BATAS PENGAMBILAN' => $details['batas_pengambilan'],
                'STATUS' => $details['status_hangus'],
                'HAK PERIODE BERJALAN' => $details['hak_periode_berjalan'],
                'DIPAKAI PERIODE BERJALAN' => $details['dipakai_periode_berjalan'],
                'SALDO PERIODE BERJALAN' => $details['sisa_periode_berjalan'],
                'TOTAL SALDO' => $details['total_saldo'],
            ]);
        }

        // Template Kosong untuk Signature Spacing
        $emptyRow = [
            'NO' => '', 'DEPARTEMEN' => '', 'NIK' => '', 'NAMA' => '', 'TGL MASUK' => '',
            'ANNIV SAAT INI' => '', 'ANNIV SEBELUMNYA' => '', 'HAK PERIODE SEBELUMNYA' => '',
            'DIPAKAI PERIODE SEBELUMNYA' => '', 'SISA SALDO PERIODE SEBELUMNYA' => '',
            'BATAS PENGAMBILAN' => '', 'STATUS' => '', 'HAK PERIODE BERJALAN' => '',
            'DIPAKAI PERIODE BERJALAN' => '', 'SALDO PERIODE BERJALAN' => '', 'TOTAL SALDO' => '',
        ];

        // Tambahkan baris kosong untuk jarak
        $exportData->push($emptyRow);
        $exportData->push($emptyRow);

        // Tambahkan baris penandatangan
        $sigRow1 = $emptyRow;
        $sigRow1['NIK'] = 'Direkap,';
        $sigRow1['TGL MASUK'] = 'Diperiksa,';
        $sigRow1['BATAS PENGAMBILAN'] = 'Diketahui,';
        $exportData->push($sigRow1);

        $exportData->push($emptyRow);
        $exportData->push($emptyRow);
        $exportData->push($emptyRow);

        $sigRow2 = $emptyRow;
        $sigRow2['NIK'] = 'Wisnu Aryo Novanto';
        $sigRow2['TGL MASUK'] = 'Anto Permana Sidik';
        $sigRow2['BATAS PENGAMBILAN'] = 'Supriyanto';
        $exportData->push($sigRow2);

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
