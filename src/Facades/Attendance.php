<?php

namespace Ianvizarra\Attendance\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ianvizarra\Attendance\Attendance
 */
class Attendance extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ianvizarra\Attendance\Attendance::class;
    }
}
