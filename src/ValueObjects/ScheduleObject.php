<?php

namespace Ianvizarra\Attendance\ValueObjects;

final class ScheduleObject
{
    public function __construct(
        public int $timeIn,
        public int $timeOut,
        public int $requiredDailyHours,
        public int $timeInAllowance,
    ) {
    }
}
