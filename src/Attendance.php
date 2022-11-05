<?php

namespace Ianvizarra\Attendance;

use Ianvizarra\Attendance\Actions\LogUserAttendanceAction;
use Ianvizarra\Attendance\Actions\TimeInUserAction;
use Ianvizarra\Attendance\Actions\TimeOutUserAction;
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

        return $this;
    }

    public function getUserTimeInToday(): null|Carbon
    {
        return $this->getUser()?->getTimeIn()?->created_at;
    }

    public function schedule(array $scheduleConfig = null): ScheduleObject
    {
        $schedule = $scheduleConfig ?? config('attendance.schedule');

        return new ScheduleObject(...$schedule);
    }

    public function timeIn(Carbon $time = null, array $scheduleConfig = null): void
    {
        app(TimeInUserAction::class)($this->getUser(), $time, $scheduleConfig);
    }

    public function timeOut(Carbon $time = null, array $scheduleConfig = null): void
    {
        app(TimeOutUserAction::class)($this->getUser(), $time, $scheduleConfig);
    }

    public function log(AttendanceLogDto $attendanceLogDto): void
    {
        app(LogUserAttendanceAction::class)($attendanceLogDto);
    }

    public function isWorkDay(Carbon $time = null, array $scheduleConfig = null): bool
    {
        $time = $time ?? now();
        $schedule = $this->schedule($scheduleConfig);

        return in_array($time->dayName, $schedule->workDays);
    }

    public function isOffDay(Carbon $time = null, array $scheduleConfig = null): bool
    {
        $time = $time ?? now();
        $schedule = $this->schedule($scheduleConfig);

        return in_array($time->dayName, $schedule->offDays);
    }

    //TODO: check for workday
    public function timeInStatus(Carbon $time = null, array $scheduleConfig = null): string
    {
        $timeInSchedule = now()->setHour($this->schedule($scheduleConfig)->timeIn)->setMinute($this->schedule($scheduleConfig)->timeInAllowance);
        $timeIn = $time ?? now();
        if ($timeIn->lte($timeInSchedule)) {
            return AttendanceStatusEnum::onTime();
        }

        return AttendanceStatusEnum::late();
    }

    public function timeOutStatus(Carbon $time = null, array $scheduleConfig = null): string
    {
        $timeOutSchedule = now()->setHour($this->schedule($scheduleConfig)->timeOut);
        $timeOut = $time ?? now();

        if ($this->getUserTimeInToday() &&
            $this->getUserTimeInToday()->diffInMinutes($timeOut) < 60 * $this->schedule($scheduleConfig)->requiredDailyHours) {
            return AttendanceStatusEnum::underTime();
        }

        if ($timeOut->gte($timeOutSchedule)) {
            return AttendanceStatusEnum::onTime();
        }

        return AttendanceStatusEnum::underTime();
    }
}
