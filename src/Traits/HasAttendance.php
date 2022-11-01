<?php
namespace Ianvizarra\Attendance\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAttendance
{
    public function attendance(): HasMany
    {
        return $this->hasMany(\Ianvizarra\Attendance\Models\AttendanceLog::class);
    }
}
