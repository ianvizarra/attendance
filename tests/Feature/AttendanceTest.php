<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class AttendanceTest extends TestCase
{
    public function test_it_can_check_time_in_status()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelTo(now()->setHour(9)->setMinute(0));
        $status = Attendance::timeInStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_late_time_in_after_9_am()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelTo(now()->setHour(9)->setMinute(32));
        $status = Attendance::timeInStatus();
        $this->assertEquals('late', $status);
    }

    public function test_it_should_return_time_out_status()
    {
        $user = $this->newUser();
        auth()->login($user);
        AttendanceLog::factory()->timeIn()->yesterdayMorning()->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(17)->setMinute(0));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status()
    {
        $user = $this->newUser();
        auth()->login($user);
        AttendanceLog::factory()->timeIn()->lateThisMorning()->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(16)->setMinute(59));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status_when_allowance_is_used()
    {
        $user = $this->newUser();
        auth()->login($user);
        AttendanceLog::factory()->timeIn()->lateThisMorning()->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(17)->setMinute(0));
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_time_in_on_time()
    {
        $this->travelTo(now()->setHour(9)->setMinute(0));
        $user = $this->newUser();
        auth()->login($user);
        Attendance::timeIn();
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'in'
        ]);
    }

    public function test_it_should_overwrite_schedule_config()
    {
        $this->travelTo(now()->setHour(8)->setMinute(0));

        $user = $this->newUser();
        auth()->login($user);
        Attendance::timeIn(now(), [
            'timeIn' => 8,
            'timeOut' => 16,
            'requiredDailyHours' => 8
        ]);

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'in'
        ]);
    }

    public function test_it_should_time_in_late_with_overwriten_schedule_config()
    {
        $this->travelTo(now()->setHour(9)->setMinute(0));

        $user = $this->newUser();
        auth()->login($user);
        Attendance::timeIn(now(), [
            'timeIn' => 8,
            'timeOut' => 16,
            'requiredDailyHours' => 8
        ]);

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'late',
            'type' => 'in'
        ]);
    }

    public function test_it_should_time_out_with_overwriten_schedule_config()
    {
        $user = $this->newUser();
        auth()->login($user);

        AttendanceLog::factory()->timeIn()->thisMorning(8)->create(['user_id' => $user->id]);
        $this->travelTo(now()->setHour(16)->setMinute(0));

        Attendance::timeOut(now(), [
            'timeIn' => 8,
            'timeOut' => 16,
            'requiredDailyHours' => 8
        ]);

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'out'
        ]);
    }
}
