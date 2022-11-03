# Attendance package for Laravel applications.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ianvizarra/attendance.svg?style=flat-square)](https://packagist.org/packages/ianvizarra/attendance)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ianvizarra/attendance/run-tests?label=tests)](https://github.com/ianvizarra/attendance/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ianvizarra/attendance/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/ianvizarra/attendance/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ianvizarra/attendance.svg?style=flat-square)](https://packagist.org/packages/ianvizarra/attendance)

Add Attendance feature with ease to your laravel application.

## Installation

You can install the package via composer:

```bash
composer require ianvizarra/attendance
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="attendance-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="attendance-config"
```

This is the contents of the published config file:

```php
return [
  'logs_table' => 'attendance_logs',
    'schedule' => [
        'hours' => [
            'timeIn' => 9,
            'timeOut' => 17,
            'requiredDailyHours' => 8,
            'timeInAllowance' => 30, // minutes
        ],
        'work_days' => [ //Soon
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday'
        ],
        'off_days' => [ //Soon
            'Saturday',
            'Sunday'
        ]
    ],
    'user_model' => config('auth.providers.users.model', \App\Models\User::class),
    'users_table' => 'users'
];
```

Add `CanLogAttendance` Interface and `HasAttendance` Trait to your User model.

```php
class User extends Authenticatable implements CanLogAttendance
{
    use HasAttendance;
}
```

## Usage

Using User Model
```php
$user->attendance(); // HasMany relationship to attendance log model

$user->timeIn(); // create an time-in attendance log entry

$user->timeOut(); // create an time-out attendance log entry

$user->hasTimeInToday(); // return true if user already time-in for today
$user->hasTimeOutToday(); // return true if user already time-out for today
$user->hasWorkedToday(); // return true if user already time-in and time-out for today
$user->getTimeInToday(); // return the time-in attendance log for today

$user->logAttendance('in', 'on-time', now()); // manually log an attendance by type, status and time
```

Using Facade
```php
use Ianvizarra\Attendance\Facades\Attendance;

 // create an attendance log entry for the currently logged-in user
 // by default it will the current time
 Attendance::timeIn(); 
 Attendance::timeOut();
 
 // manually set the time logged instead of the current time
 Attendance::timeIn(now()->subMinutes(30));
 
 // manually set the schedule configuration
 // the default config values can be found in config/attendance.php `config('attendance.schedule.hours')`
 Attendance::timeIn(now(), [
    'timeIn' => 8,
    'timeOut' => 16,
    'requiredDailyHours' => 8
]);
 
 // manually set the user other than the logged-in user
 Attendance::setUser($user)->timeIn())->timeIn();
 
 // get the time in status
 Attendance::timeInStatus(); // on-time, late
 // manually set the time using Carbon instance
 Attendance::timeInStatus(now()->subMinutes(30)); // on-time, late
  // manually set the schedule configuration
 Attendance::timeInStatus(now(), [
    'timeIn' => 8,
    'timeOut' => 16,
    'requiredDailyHours' => 8
]); // on-time, late
 
 // get the time out status
 Attendance::timeOutStatus(); // on-time, under-time
 // manually set the time using Carbon instance
 Attendance::timeOutStatus(now()->subMinutes(30)); // on-time, under-time
 // manually set the schedule configuration
 Attendance::timeOutStatus(now(), [
    'timeIn' => 8,
    'timeOut' => 16,
    'requiredDailyHours' => 8
]); // on-time, under-time
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ian Vizarra](https://github.com/ianvizarra)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
