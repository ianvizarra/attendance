<?php

namespace Ianvizarra\Attendance\Facades;

use Illuminate\Support\Facades\Facade;

class Attendance extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Ianvizarra\Attendance\Attendance::class;
    }
}
