<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Cuti Karyawan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 7.5px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            word-wrap: break-word;
        }
        table.data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        table.data-table td.text-left { text-align: left; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        /* Signature Box */
        .signature-wrapper {
            width: 100%;
            margin-top: 20px;
        }
        table.signature-table {
            width: 40%;
            margin-left: auto; /* Push to right */
            border-collapse: collapse;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
        }
        table.signature-table th, table.signature-table td {
            border: 1px solid #000;
            padding: 5px;
        }
        table.signature-table th {
            font-weight: normal;
        }
        table.signature-table .empty-space {
            height: 50px; /* Space for signature */
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
                <th width="2%">NO</th>
                <th width="6%">DEPT</th>
                <th width="6%">NIK</th>
                <th width="10%">NAMA</th>
                <th width="6%">TGL MASUK</th>
                <th width="6%">ANNIV SAAT INI</th>
                <th width="6%">ANNIV SEBELUMNYA</th>
                <th width="5%">HAK LAMA</th>
                <th width="5%">DIPAKAI LAMA</th>
                <th width="5%">SISA LAMA</th>
                <th width="6%">BATAS PENGAMBILAN</th>
                <th width="5%">STATUS</th>
                <th width="5%">HAK BARU</th>
                <th width="5%">DIPAKAI BARU</th>
                <th width="5%">SISA BARU</th>
                <th width="5%">TOTAL SALDO</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                @php $details = $emp->leave_details; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emp->position }}</td>
                    <td>{{ $emp->employee_id }}</td>
                    <td class="text-left">{{ $emp->name }}</td>
                    <td>{{ $emp->join_date->format('d M Y') }}</td>
                    <td>{{ $details['anniv_saat_ini'] }}</td>
                    <td>{{ $details['anniv_sebelumnya'] }}</td>
                    <td>{{ $details['hak_periode_sebelumnya'] }}</td>
                    <td>{{ $details['dipakai_periode_sebelumnya'] }}</td>
                    <td>{{ $details['sisa_periode_sebelumnya'] }}</td>
                    <td>{{ $details['batas_pengambilan'] }}</td>
                    <td>{{ $details['status_hangus'] }}</td>
                    <td>{{ $details['hak_periode_berjalan'] }}</td>
                    <td>{{ $details['dipakai_periode_berjalan'] }}</td>
                    <td>{{ $details['sisa_periode_berjalan'] }}</td>
                    <td style="font-weight: bold;">{{ $details['total_saldo'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16">Tidak ada data karyawan ditemukan.</td>
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
