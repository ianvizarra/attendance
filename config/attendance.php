<?php

// config for Ianvizarra/Attendance
return [
    'logs_table' => 'attendance_logs',
    'schedule' => [
        'timeIn' => 9,
        'timeOut' => 17,
        'requiredDailyHours' => 8,
        'timeInAllowance' => 30, // minutes
        'workDays' => [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
        ],
        'offDays' => [
            'Saturday',
            'Sunday',
        ],
    ],
    'user_model' => config('auth.providers.users.model', \App\Models\User::class),
    'users_table' => 'users',
];
