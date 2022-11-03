<?php

namespace Ianvizarra\Attendance\Traits;

use Ianvizarra\Attendance\Actions\LogUserAttendanceAction;
use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Ianvizarra\Attendance\Enums\AttendanceTypeEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

trait HasAttendance
{
    public function attendance(): HasMany
    {
        return $this->hasMany(\Ianvizarra\Attendance\Models\AttendanceLog::class);
    }

    public function hasTimeInToday(): bool
    {
        return $this->attendance()
            ->whereDate('created_at', now()->toDateString())
            ->where('type', AttendanceTypeEnum::in())
            ->exists();
    }

    public function logAttendance($type, $status = 'on-time', Carbon $time = null)
    {
        app(LogUserAttendanceAction::class)(new AttendanceLogDto(
            user: $this,
            type: new AttendanceTypeEnum($type),
            status: new AttendanceStatusEnum($status),
            time: $time ?? new Carbon()
        ));
    }
}
