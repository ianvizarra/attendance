<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Models\AttendanceLog;
use Ianvizarra\Attendance\Tests\TestCase;

class HasAttendanceTraitsTest extends TestCase
{
    public function test_user_has_attendance_logs()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user->id]);
        AttendanceLog::factory()->timeOut()->create(['user_id' => $user->id]);
        $this->assertCount(2, $user->attendance);
    }

    public function test_user_can_time_in()
    {
        $user = $this->newUser();
        $user->logAttendance('in');

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $user->id,
            'type' => 'in'
        ]);
    }

    public function test_user_has_worked_today()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user->id, 'created_at' => now()->setHour(9)->setMinute(0)]);
        AttendanceLog::factory()->timeOut()->create(['user_id' => $user->id, 'created_at' => now()->setHour(17)->setMinute(0)]);
        $this->assertTrue($user->hasWorked());
    }

    public function test_user_has_time_in_only_today()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->thisMorning()->create(['user_id' => $user->id]);
        $this->assertFalse($user->hasWorked());
    }

    public function test_user_has_time_in_today()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user, 'created_at' => now()->setHour(9)->setMinute(0)]);
        $this->assertTrue($user->hasTimeIn());
    }

    public function test_user_has_not_time_in_today()
    {
        $this->assertFalse($this->newUser()->hasTimeIn());
    }

    public function test_user_has_time_out_today()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->create(['user_id' => $user, 'created_at' => now()->setHour(9)->setMinute(0)]);
        AttendanceLog::factory()->timeOut()->create(['user_id' => $user, 'created_at' => now()->setHour(17)->setMinute(0)]);
        $this->assertTrue($user->hasTimeOut());
    }

    public function test_user_has_not_time_out_today()
    {
        $user = $this->newUser();
        AttendanceLog::factory()->timeIn()->thisMorning()->create(['user_id' => $user]);
        $this->assertFalse($user->hasTimeOut());
    }
}
