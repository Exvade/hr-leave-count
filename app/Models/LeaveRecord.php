<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRecord extends BaseModel
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'duration',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
