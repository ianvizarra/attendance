<?php

namespace Ianvizarra\Attendance\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CanLogAttendance
{
    public function attendance(): HasMany;

    public function hasTimeInToday(): bool;
}
