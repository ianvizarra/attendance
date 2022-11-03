<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class AttendanceTest extends TestCase
{
    public function test_it_can_check_time_in_status()
    {
        auth()->login($this->user);
        $this->travelTo(now()->setHour(9)->setMinute(0));
        $status = Attendance::timeInStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_late_time_in_after_9_am()
    {
        auth()->login($this->user);
        $this->travelTo(now()->setHour(9)->setMinute(32));
        $status = Attendance::timeInStatus();
        $this->assertEquals('late', $status);
    }

    public function test_it_should_return_time_out_status()
    {
        AttendanceLog::factory()->timeIn()->create(['user_id' => $this->user->id, 'created_at' => now()->setHour(9)->setMinute(0)->subDay()]);
        auth()->login($this->user);
        $this->travelTo(now()->setHour(17)->setMinute(0));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status()
    {
        AttendanceLog::factory()->timeIn()->create(['user_id' => $this->user->id, 'created_at' => now()->setHour(9)->setMinute(0)]);
        auth()->login($this->user);
        $this->travelTo(now()->setHour(16)->setMinute(59));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status_when_allowance_is_used()
    {
        AttendanceLog::factory()->timeIn()->create(['user_id' => $this->user->id, 'created_at' => now()->setHour(9)->setMinute(30)]);
        auth()->login($this->user);
        $this->travelTo(now()->setHour(17)->setMinute(0));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_time_in_on_time()
    {
        $this->travelTo(now()->setHour(9)->setMinute(0));

        auth()->login($this->user);
        Attendance::timeIn();
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $this->user->id,
            'status'=> 'on-time',
            'type' => 'in'
        ]);
    }
}
