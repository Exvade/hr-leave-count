<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Cuti Karyawan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        /* Signature Box */
        .signature-wrapper {
            width: 100%;
            margin-top: 40px;
        }
        table.signature-table {
            width: 50%;
            margin-left: auto; /* Push to right */
            border-collapse: collapse;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
        }
        table.signature-table th, table.signature-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        table.signature-table th {
            font-weight: normal;
        }
        table.signature-table .empty-space {
            height: 70px; /* Space for signature */
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekapitulasi Cuti Karyawan</h2>
        @if($department)
            <p>Departemen / Jabatan: <strong>{{ $department }}</strong></p>
        @else
            <p>Semua Departemen</p>
        @endif
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">NIK</th>
                <th width="20%">Nama Lengkap</th>
                <th width="15%">Departemen</th>
                <th width="15%">Tgl Bergabung</th>
                <th width="10%">Hak Cuti</th>
                <th width="10%">Terpakai</th>
                <th width="15%">Sisa Cuti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $emp->employee_id }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->position }}</td>
                    <td class="text-center">{{ $emp->join_date->format('d M Y') }}</td>
                    <td class="text-center">{{ $emp->leave_quota }}</td>
                    <td class="text-center">{{ $emp->leave_taken }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $emp->remaining_leave }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data karyawan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <th>Direkap,</th>
                <th>Diperiksa,</th>
                <th>Diketahui,</th>
            </tr>
            <tr>
                <td class="empty-space"></td>
                <td class="empty-space"></td>
                <td class="empty-space"></td>
            </tr>
            <tr>
                <td>Wisnu Aryo Novanto</td>
                <td>Anto Permana Sidik</td>
                <td>Supriyanto</td>
            </tr>
        </table>
    </div>

</body>
</html>
