<?php

namespace Ianvizarra\Attendance\Tests;

use Carbon\Carbon;
use Dotenv\Dotenv;
use Ianvizarra\Attendance\AttendanceServiceProvider;
use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Tests\Support\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use DatabaseTransactions;
    use WithFaker;

    protected CanLogAttendance $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->setupUser();
    }

    public function setupUser()
    {
        $this->user = new User();
        $this->user->name = 'Ian';
        $this->user->save();
    }

    public function newUser(): User
    {
        $user = new User();
        $user->name = $this->faker()->name();
        $user->save();

        return $user;
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
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
        
    }

    protected function loadEnvironmentVariables()
    {
        if (! file_exists(__DIR__.'/../.env')) {
            return;
        }

        $dotEnv = Dotenv::createImmutable(__DIR__.'/..');

        $dotEnv->load();
    }

    protected function getPackageProviders($app)
    {
        return [AttendanceServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Attendance' => Attendance::class,
        ];
    }

    public function travelToWeekday($hour = 9, $minute = 0): void
    {
        $this->travelTo(Carbon::create(2022, 1, 3)->setHour($hour)->setMinute($minute));
    }

    public function travelToWeekend($hour = 9, $minute = 0): void
    {
        $this->travelTo(Carbon::create(2022, 1, 1)->setHour($hour)->setMinute($minute));
    }
}
