<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends BaseModel
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'join_date' => 'date',
    ];

    protected $appends = ['leave_quota', 'remaining_leave', 'leave_details'];

    public function leaveRecords()
    {
        return $this->hasMany(LeaveRecord::class);
    }

    public function getLeaveTakenAttribute(): int
    {
        return $this->leaveRecords()->sum('duration');
    }

    public function getLeaveDetailsAttribute(): array
    {
        if (! $this->join_date) {
            return $this->emptyLeaveDetails();
        }

        $now = Carbon::now();
        $joinDate = Carbon::parse($this->join_date);
        $totalMonths = (int) $joinDate->diffInMonths($now);
        $yearsCompleted = intdiv($totalMonths, 12);
        $monthsInCurrentYear = $totalMonths % 12;

        $annivSaatIni = $yearsCompleted >= 0 ? $joinDate->copy()->addYears($yearsCompleted) : null;
        $annivSebelumnya = $yearsCompleted >= 1 ? $joinDate->copy()->addYears($yearsCompleted - 1) : null;
        $batasPengambilan = $annivSaatIni ? $annivSaatIni->copy()->addMonths(6) : null;
        $isHangus = $batasPengambilan ? $now->greaterThanOrEqualTo($batasPengambilan) : false;

        $hakPeriodeSebelumnya = 0;
        if ($yearsCompleted >= 2) {
            $hakPeriodeSebelumnya = 12;
        }

        // Cuti yang diambil di masa lalu (sebelum anniv saat ini)
        $dipakaiLama = 0;
        if ($annivSebelumnya && $annivSaatIni) {
            $dipakaiLama = $this->leaveRecords()
                ->where('start_date', '>=', $annivSebelumnya)
                ->where('start_date', '<', $annivSaatIni)
                ->sum('duration');
        }

        $sisaLamaAwal = max(0, $hakPeriodeSebelumnya - $dipakaiLama);

        // Cuti yang diambil setelah anniv saat ini
        $leavesBerjalan = $this->leaveRecords()
            ->where('start_date', '>=', $annivSaatIni)
            ->orderBy('start_date')
            ->get();

        $dipakaiDariLama = 0;
        $dipakaiDariBaru = 0;

        foreach ($leavesBerjalan as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $duration = $leave->duration;

            if ($leaveStart->lessThan($batasPengambilan)) {
                $sisaLamaAktif = $sisaLamaAwal - $dipakaiDariLama;
                if ($sisaLamaAktif > 0) {
                    if ($duration <= $sisaLamaAktif) {
                        $dipakaiDariLama += $duration;
                    } else {
                        $dipakaiDariLama += $sisaLamaAktif;
                        $dipakaiDariBaru += ($duration - $sisaLamaAktif);
                    }
                } else {
                    $dipakaiDariBaru += $duration;
                }
            } else {
                $dipakaiDariBaru += $duration;
            }
        }

        $dipakaiPeriodeSebelumnya = $dipakaiLama + $dipakaiDariLama;
        $sisaPeriodeSebelumnya = $hakPeriodeSebelumnya - $dipakaiPeriodeSebelumnya;
        $sisaAktifPeriodeSebelumnya = $isHangus ? 0 : max(0, $sisaPeriodeSebelumnya);

        $hakPeriodeBerjalan = $yearsCompleted == 0 ? 0 : $monthsInCurrentYear;
        $dipakaiPeriodeBerjalan = $dipakaiDariBaru;
        $sisaPeriodeBerjalan = $hakPeriodeBerjalan - $dipakaiPeriodeBerjalan;
        $totalSaldo = $sisaAktifPeriodeSebelumnya + $sisaPeriodeBerjalan;

        return [
            'anniv_saat_ini' => $annivSaatIni ? $annivSaatIni->format('d M Y') : '-',
            'anniv_sebelumnya' => $annivSebelumnya ? $annivSebelumnya->format('d M Y') : '-',
            'hak_periode_sebelumnya' => $hakPeriodeSebelumnya,
            'dipakai_periode_sebelumnya' => $dipakaiPeriodeSebelumnya,
            'sisa_periode_sebelumnya' => $sisaPeriodeSebelumnya,
            'batas_pengambilan' => $batasPengambilan ? $batasPengambilan->format('d M Y') : '-',
            'status_hangus' => $isHangus ? 'HANGUS' : 'AKTIF',
            'hak_periode_berjalan' => $hakPeriodeBerjalan,
            'dipakai_periode_berjalan' => $dipakaiPeriodeBerjalan,
            'sisa_periode_berjalan' => $sisaPeriodeBerjalan,
            'total_saldo' => $totalSaldo,
        ];
    }

    private function emptyLeaveDetails(): array
    {
        return [
            'anniv_saat_ini' => '-', 'anniv_sebelumnya' => '-', 'hak_periode_sebelumnya' => 0,
            'dipakai_periode_sebelumnya' => 0, 'sisa_periode_sebelumnya' => 0,
            'batas_pengambilan' => '-', 'status_hangus' => '-', 'hak_periode_berjalan' => 0,
            'dipakai_periode_berjalan' => 0, 'sisa_periode_berjalan' => 0, 'total_saldo' => 0,
        ];
    }

    public function getLeaveQuotaAttribute(): int
    {
        $details = $this->leave_details;
        $hakLama = $details['status_hangus'] === 'HANGUS' ? 0 : $details['hak_periode_sebelumnya'];

        return $hakLama + $details['hak_periode_berjalan'];
    }

    public function getRemainingLeaveAttribute(): int
    {
        return $this->leave_details['total_saldo'];
    }
}
