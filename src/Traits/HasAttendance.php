<?php

namespace Ianvizarra\Attendance\Traits;

use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Ianvizarra\Attendance\Actions\LogUserAttendanceAction;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Ianvizarra\Attendance\Enums\AttendanceTypeEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Facades\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

trait HasAttendance
{
    public function attendance(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function hasTimeIn(Carbon $time = null): bool
    {
        return $this->attendance()
            ->where('date', $time ? $time->toDateString() : now()->toDateString())
            ->where('type', AttendanceTypeEnum::in())
            ->exists();
    }

    public function hasTimeOut(Carbon $time = null): bool
    {
        return $this->attendance()
            ->where('date', $time ? $time->toDateString() : now()->toDateString())
            ->where('type', AttendanceTypeEnum::out())
            ->exists();
    }

    public function hasWorked(Carbon $time = null): bool
    {
        return $this->hasTimeIn($time) && $this->hasTimeOut($time);
    }

    public function getTimeIn(Carbon $time = null): ?Model
    {
        return $this->attendance()
            ->where('date', $time ? $time->toDateString() : now()->toDateString())
            ->where('type', AttendanceTypeEnum::in())
            ->first();
    }

    public function logAttendance($type, $status = 'on-time', Carbon $time = null): void
    {
        app(LogUserAttendanceAction::class)(new AttendanceLogDto(
            user: $this,
            type: new AttendanceTypeEnum($type),
            status: new AttendanceStatusEnum($status),
            time: $time ?? new Carbon()
        ));
    }

    public function isOffDay(Carbon $time = null, $scheduleConfig = null): bool
    {
        return Attendance::isOffDay($time, $scheduleConfig);
    }

    public function isWorkDay(Carbon $time = null, $scheduleConfig = null): bool
    {
        return Attendance::isWorkDay($time, $scheduleConfig);
    }
}
