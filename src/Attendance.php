<?php

namespace Ianvizarra\Attendance;

use Ianvizarra\Attendance\Actions\LogUserAttendanceAction;
use Ianvizarra\Attendance\Actions\TimeInUserAction;
use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Ianvizarra\Attendance\ValueObjects\ScheduleObject;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;

class Attendance
{
    public function __construct(public Application $app)
    {
    }

    /**
     * Get the currently authenticated user or null.
     */
    public function getAuthUser(): null|CanLogAttendance
    {
        return $this->app->auth->user();
    }

    public function getUser(): null|CanLogAttendance
    {
        return $this->user ?? $this->getAuthUser();
    }

    public function setUser(CanLogAttendance $user)
    {
        $this->user = $user;
    }

    public function getUserTimeInToday(): null|Carbon
    {
        return $this->getUser()?->getTimeInToday()?->created_at;
    }

    public function schedule(): ScheduleObject
    {
        return new ScheduleObject(...config('attendance.schedule.hours'));
    }

    public function timeIn(Carbon $time = null): void
    {
        app(TimeInUserAction::class)($this->getUser(), $time);
    }

    public function timeOut()
    {
        //TODO: implement method
    }

    public function log(AttendanceLogDto $attendanceLogDto): void
    {
        app(LogUserAttendanceAction::class)($attendanceLogDto);
    }

    //TODO: check for workday
    public function timeInStatus(Carbon $time = null): string
    {
        $timeInSchedule = now()->setHour($this->schedule()->timeIn)->setMinute($this->schedule()->timeInAllowance);
        $timeIn = $time ?? now();
        if ($timeIn->lte($timeInSchedule)) {
            return AttendanceStatusEnum::onTime();
        }

        return AttendanceStatusEnum::late();
    }

    public function timeOutStatus(Carbon $time = null): string
    {
        $timeOutSchedule = now()->setHour($this->schedule()->timeOut);
        $timeOut = $time ?? now();

        if ($this->getUserTimeInToday() &&
            $this->getUserTimeInToday()->diffInMinutes($timeOut) < 60 * $this->schedule()->requiredDailyHours) {
            return AttendanceStatusEnum::underTime();
        }

        if ($timeOut->gte($timeOutSchedule)) {
            return AttendanceStatusEnum::onTime();
        }

        return AttendanceStatusEnum::underTime();
    }
}
