<?php

namespace Ianvizarra\Attendance;

use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;

class Attendance
{
    public const LATE = 'late';
    public const ON_TIME = 'on-time';

    public function __construct(public Application $app)
    {
    }

    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return $this->app->auth->user();
    }

    public function timeIn()
    {
        //TODO: implement method
    }

    public function timeOut()
    {
        //TODO: implement method
    }

    public function log($type, $data)
    {
    }

    //TODO: check for workday
    public function timeInStatus(Carbon $time): string
    {
        $schedule = config('attendance.schedule');

        $timeIn = now()->setHour($schedule['time_in'])->setMinute($schedule['time_in_allowance']);

        if ($time->lte($timeIn)) {
            return self::ON_TIME;
        }

        return self::LATE;
    }

    public function timeInStatusNow(): String
    {
        return $this->timeInStatus(now());
    }
}
