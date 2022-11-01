<?php

// config for Ianvizarra/Attendance
return [
    'logs_table' => 'attendance_logs',
    'schedule' => [
        'time_in' => 9,
        'time_out' => 5,
        'daily_hours_required' => 8,
        'time_in_allowance' => 30, // minutes
    ],
    'user_model' => config('auth.providers.users.model', \App\Models\User::class),
    'users_table' => 'users'
];
