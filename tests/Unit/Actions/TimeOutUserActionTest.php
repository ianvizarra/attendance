<?php

namespace Unit\Actions;

use Ianvizarra\Attendance\Actions\TimeOutUserAction;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class TimeOutUserActionTest extends TestCase
{
    public function test_it_should_time_out_on_time()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->thisMorning()->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(17)->setMinute(0));
        app(TimeOutUserAction::class)($user);
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'out'
        ]);
    }

    public function test_it_should_not_time_out_twice_on_the_same_day()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user->id, 'created_at' => now()->setHour(9)->setMinute(30)]);
        $this->travelTo(now()->setHour(9)->setMinute(0));
        AttendanceLog::factory()->timeOut()->create(['user_id' => $user->id, 'created_at' => now()]);
        $this->expectExceptionMessage("You have already time-out today");
        app(TimeOutUserAction::class)($user);
    }

    public function test_it_should_time_out_under_time()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->lateThisMorning()->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(16)->setMinute(59));
        app(TimeOutUserAction::class)($user);
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'under-time',
            'type' => 'out'
        ]);
    }

    public function test_should_allow_time_out_the_next_day()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->yesterdayMorning()->create(['user_id' => $user->id]);
        AttendanceLog::factory()->timeOut()->yesterdayEvening()->create(['user_id' => $user->id]);

        $timeNow = now();
        $this->travelTo($timeNow->setHour(9)->setMinute(0));
        AttendanceLog::factory()->timeIn()->thisMorning()->create(['user_id' => $user->id]);
        app(TimeOutUserAction::class)($user);

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'in',
            'date' =>$timeNow->toDateString(),
            'time' => $timeNow->toTimeString()
        ]);
    }
}
