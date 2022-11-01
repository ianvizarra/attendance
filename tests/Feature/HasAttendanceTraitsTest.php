<?php

namespace Ianvizarra\Attendance\Tests\Feature;

use Ianvizarra\Attendance\Tests\Support\User;
use Ianvizarra\Attendance\Tests\TestCase;

class HasAttendanceTraitsTest extends TestCase
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

    public function test_user_can_time_in()
    {
        $this->user->logAttendance('in');

        $this->assertDatabaseHas('attendance_logs', [
            'user_id' => $this->user->id,
            'type' => 'in'
        ]);
    }
}
