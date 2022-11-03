<?php

// config for Ianvizarra/Attendance
return [
    'logs_table' => 'attendance_logs',
    'schedule' => [
        'statuses' => [
            'timeIn' => 9,
            'timeOut' => 5,
            'requiredDailyHours' => 8,
            'timeInAllowance' => 30, // minutes
        ],
        'work_days' =>[
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday'
        ],
        'off_days' => [
            'Saturday',
            'Sunday'
        ]
    ],
    'user_model' => config('auth.providers.users.model', \App\Models\User::class),
    'users_table' => 'users'
];
