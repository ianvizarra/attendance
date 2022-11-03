<?php

namespace Ianvizarra\Attendance\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

interface CanLogAttendance
{
    public function attendance(): HasMany;

    public function hasTimeInToday(): bool;

    public function hasTimeOutToday(): bool;

    public function hasWorkedToday(): bool;

    public function getTimeInToday(): ?Model;

    public function logAttendance($type, $status = 'on-time', Carbon $time = null): void;
}
