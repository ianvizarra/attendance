<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Tests\Support\User;
use Ianvizarra\Attendance\Tests\TestCase;

class HasAttendanceTraitsTest extends TestCase
{
    public function test_user_can_time_in()
    {
        $this->user->logAttendance('in');

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $this->user->id,
            'type' => 'in'
        ]);
    }
}
