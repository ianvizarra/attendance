<?php

// config for Ianvizarra/Attendance
return [
    'logs_table' => 'attendance_logs',
    'shift' => [
        'time_in' => 9,
        'time_out' => 5,
        'daily_hours_required' => 8,
        'time_in_buffer' => 30, // minutes
    ]
];
