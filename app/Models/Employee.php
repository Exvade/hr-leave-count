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

        $yearsOfService = $this->join_date->diffInYears(Carbon::now());

        return $yearsOfService >= 1 ? 12 : 0;
    }

    public function getRemainingLeaveAttribute(): int
    {
        return $this->leave_quota - $this->leave_taken;
    }
}
