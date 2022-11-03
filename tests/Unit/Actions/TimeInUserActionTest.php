<?php

namespace Unit\Actions;

use Ianvizarra\Attendance\Actions\TimeInUserAction;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class TimeInUserActionTest extends TestCase
{
    public function test_it_should_time_in_on_time()
    {
        $this->travelTo(now()->setHour(9)->setMinute(0));
        app(TimeInUserAction::class)($this->user);
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $this->user->id,
            'status'=> 'on-time',
            'type' => 'in'
        ]);
    }

    public function test_it_should_time_in_twice_on_the_same_day()
    {
        $this->travelTo(now()->setHour(9)->setMinute(0));
        AttendanceLog::factory()->in()->create(['user_id' => $this->user->id, 'created_at' => now()]);
        $this->expectExceptionMessage("You have already time-in today");
        app(TimeInUserAction::class)($this->user);
    }
}
