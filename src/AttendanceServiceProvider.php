<?php

namespace Ianvizarra\Attendance;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ianvizarra\Attendance\Commands\AttendanceCommand;

class AttendanceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('attendance')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_attendance_table')
            ->hasCommand(AttendanceCommand::class);
    }
}
