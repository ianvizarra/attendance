<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Tests\Support\User;
use Ianvizarra\Attendance\Tests\TestCase;

class AttendanceTest extends TestCase
{
    protected $user;
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('attendance.user_model', 'User');

        \Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        \Schema::create('tasks', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('team_id');
            $table->timestamps();
        });
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->name = 'Ian';
        $this->user->save();
    }

    public function test_it_can_check_time_in_status()
    {
        auth()->login($this->user);
        $this->travelTo(now()->setHour(9));
        $status = Attendance::timeInStatusNow();
        $this->assertEquals('on-time', $status);
    }

    public function test_it_should_return_late_time_in_after_9_am()
    {
        auth()->login($this->user);
        $this->travelTo(now()->setHour(9)->setMinute(32));
        $status = Attendance::timeInStatusNow();
        $this->assertEquals('late', $status);
    }
}
