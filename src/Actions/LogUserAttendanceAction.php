<?php

namespace Ianvizarra\Attendance\Actions;

use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Illuminate\Support\Carbon;

class LogUserAttendanceAction
{
    public function __invoke(AttendanceLogDto $attendanceLogDto): void
    {
        $time = $attendanceLogDto->time ?? Carbon::now();
        $attendanceLogDto->user->attendance()->create([
            'type'  => $attendanceLogDto->type->value,
            'status' => $attendanceLogDto->status->value,
            'date' => $time->toDateString(),
            'time' => $time->toTimeString(),
            'created_at' => $attendanceLogDto->time ?? Carbon::now(),
        ]);
    }
}
