<?php

namespace Ianvizarra\Attendance\Tests;

use Dotenv\Dotenv;
use Ianvizarra\Attendance\AttendanceServiceProvider;
use Ianvizarra\Attendance\Facades\Attendance;
use Ianvizarra\Attendance\Tests\Support\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use DatabaseTransactions;

    protected $user;

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
}
