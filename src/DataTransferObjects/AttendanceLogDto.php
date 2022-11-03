<?php

namespace Ianvizarra\Attendance\DataTransferObjects;

use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\Enums\AttendanceTypeEnum;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Illuminate\Support\Carbon;

final class AttendanceLogDto
{
    public function __construct(
        public CanLogAttendance $user,
        public AttendanceTypeEnum $type,
        public AttendanceStatusEnum $status,
        public ?Carbon $time = null,
    ) {
    }
}
