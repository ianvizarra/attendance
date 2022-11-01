<?php

namespace Ianvizarra\Attendance\Tests\Support;

use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\Traits\HasAttendance;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements CanLogAttendance
{
    use HasAttendance;
}
