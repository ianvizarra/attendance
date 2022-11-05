<?php

namespace Unit\Actions;

use Ianvizarra\Attendance\Actions\TimeInUserAction;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class TimeInUserActionTest extends TestCase
{
    public function test_it_should_time_in_on_time()
    {
        $user = $this->newUser();
        $this->travelToWeekday();
        app(TimeInUserAction::class)($user);
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status' => 'on-time',
            'type' => 'in',
        ]);
    }

    public function test_it_should_not_time_in_twice_on_the_same_day()
    {
        $this->travelToWeekday();
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user->id, 'created_at' => now()]);
        $this->expectExceptionMessage('You have already time-in today');
        app(TimeInUserAction::class)($user);
    }

    public function test_it_should_time_in_late()
    {
        $user = $this->newUser();
        $this->travelToWeekday(9, 31);
        app(TimeInUserAction::class)($user);
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status' => 'late',
            'type' => 'in',
        ]);
    }

    public function test_should_allow_time_in_the_next_day()
    {
        $user = $this->newUser();
        $this->travelToWeekday();
        AttendanceLog::factory()->timeIn()->yesterdayMorning()->create(['user_id' => $user->id]);
        AttendanceLog::factory()->timeOut()->yesterdayEvening()->create(['user_id' => $user->id]);

        $timeNow = now();
        app(TimeInUserAction::class)($user);

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status' => 'on-time',
            'type' => 'in',
            'date' => $timeNow->toDateString(),
            'time' => $timeNow->toTimeString(),
        ]);
    }
}
