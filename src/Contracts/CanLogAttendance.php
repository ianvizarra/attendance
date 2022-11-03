<?php

namespace Ianvizarra\Attendance\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

interface CanLogAttendance
{
    public function attendance(): HasMany;

    public function hasTimeIn(Carbon $time = null): bool;

    public function hasTimeOut(Carbon $time = null): bool;

    public function hasWorked(Carbon $time = null): bool;

    public function getTimeIn(Carbon $time = null): ?Model;

    public function logAttendance($type, $status = 'on-time', Carbon $time = null): void;

    public function isOffDay(Carbon $time = null, $scheduleConfig = null): bool;

    public function isWorkDay(Carbon $time = null, $scheduleConfig = null): bool;
}
