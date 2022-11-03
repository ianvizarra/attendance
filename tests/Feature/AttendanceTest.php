<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Tests\Support\User;
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
}
