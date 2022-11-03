<?php

namespace Ianvizarra\Attendance\Actions;

use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Illuminate\Support\Carbon;

class LogUserAttendanceAction
{
    public function __invoke(AttendanceLogDto $attendanceLogDto): void
    {
        $attendanceLogDto->user->attendance()->create([
            'type'  => $attendanceLogDto->type->value,
            'created_at' => $attendanceLogDto->time ?? Carbon::now(),
            'status' => $attendanceLogDto->status->value
        ]);
    }
}
