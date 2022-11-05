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
        $this->travelToWeekday(9, 30);
        $status = Attendance::timeInStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_late_time_in_after_9_am()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelToWeekday(9, 32);
        $status = Attendance::timeInStatus();
        $this->assertEquals('late', $status);
    }

    public function test_it_should_return_time_out_status()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelToWeekday(17, 0);
        AttendanceLog::factory()->timeIn()->yesterdayMorning()->create(['user_id' => $user->id]);
        $status = Attendance::timeOutStatus();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status()
    {
        $user = $this->newUser();
        auth()->login($user);
        AttendanceLog::factory()->timeIn()->lateThisMorning()->create(['user_id' => $user->id]);
        $this->travelToWeekday(16, 59);
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_return_time_out_under_time_status_when_allowance_is_used()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelToWeekday(17, 0);
        AttendanceLog::factory()->timeIn()->lateThisMorning()->create(['user_id' => $user->id]);
        $status = Attendance::timeOutStatus();
        $this->assertEquals('under-time', $status);
    }

    public function test_it_should_time_in_on_time()
    {
        $this->travelToWeekday();
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
        $this->travelToWeekday(8, 0);

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
        $this->travelToWeekday();

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

        $this->travelToWeekday(16);
        AttendanceLog::factory()->timeIn()->thisMorning(8)->create(['user_id' => $user->id]);

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

    public function test_it_should_set_user()
    {
        $user = $this->newUser();
        $this->travelToWeekday();
        Attendance::setUser($user)->timeIn();
        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'status'=> 'on-time',
            'type' => 'in'
        ]);
    }

    public function test_it_should_check_work_day()
    {
        $this->travelToWeekday();
        $this->assertTrue(Attendance::isWorkDay());
    }

    public function test_it_should_check_work_day_with_custom_config()
    {
        $this->travelToWeekend();
        $this->assertTrue(Attendance::isWorkDay(now(), [
            'timeIn' => 8,
            'timeOut' => 16,
            'requiredDailyHours' => 8,
            'workDays' => [
                'Saturday',
                'Sunday',
            ]
        ]));
    }

    public function test_it_should_not_time_in_if_off_day()
    {
        $user = $this->newUser();
        auth()->login($user);
        $this->travelToWeekend();
        $this->expectExceptionMessage("It's your day-off");
        Attendance::timeIn(now());
    }

    public function test_it_should_check_off_day()
    {
        $this->travelToWeekend();
        $this->assertTrue(Attendance::isOffDay());
    }
}
