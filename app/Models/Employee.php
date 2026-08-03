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
        'leave_taken' => 'integer',
    ];

    protected $appends = ['leave_quota', 'remaining_leave'];

    public function getLeaveQuotaAttribute(): int
    {
        if (! $this->join_date) {
            return 0;
        }

        $now = Carbon::now();
        $totalMonths = (int) $this->join_date->diffInMonths($now);

        // Belum 1 tahun masa kerja = 0 cuti
        if ($totalMonths < 12) {
            return 0;
        }

        $yearsCompleted = intdiv($totalMonths, 12);
        $monthsInCurrentYear = $totalMonths % 12;

        // Tahun kedua masa kerja (karena tahun 1 = 0 cuti)
        if ($yearsCompleted == 1) {
            return $monthsInCurrentYear;
        }

        // Tahun ketiga dan seterusnya
        // Jika belum lewat bulan ke-6, sisa cuti tahun lalu (12 hari) masih bisa dipakai
        if ($monthsInCurrentYear <= 6) {
            return 12 + $monthsInCurrentYear;
        }

        // Jika lewat bulan ke-6, cuti tahun lalu hangus
        return $monthsInCurrentYear;
    }

    public function getRemainingLeaveAttribute(): int
    {
        return $this->leave_quota - $this->leave_taken;
    }
}
