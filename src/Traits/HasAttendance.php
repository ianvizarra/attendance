<?php

namespace Ianvizarra\Attendance\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

trait HasAttendance
{
    public function attendance(): HasMany
    {
        return $this->hasMany(\Ianvizarra\Attendance\Models\AttendanceLog::class);
    }

    public function logAttendance($type, Carbon $time = null)
    {
        //TODO: check type
        //TODO: get schedule
        //TODO: check status
        $this->attendance()->create([
            'type'  => $type,
            'created_at' => $time ?? Carbon::now(),
            'status' => 'on-time'
        ]);
    }
}
