<?php

namespace Ianvizarra\Attendance\Database\Factories;

use Ianvizarra\Attendance\Models\AttendanceLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceLogFactory extends Factory
{
    protected $model = AttendanceLog::class;

    public function definition()
    {
        return [
            'type' => 'in',
            'status' => 'on-time',
            'created_at' => now()
        ];
    }

    public function in()
    {
        return $this->state(function() {
            return [
                'type' => 'time'
            ];
        });
    }

    public function ontime()
    {
        return $this->state(function() {
            return [
                'status' => 'on-time'
            ];
        });
    }

    public function out()
    {
        return $this->state(function() {
            return [
                'type' => 'out',
                'minutes_rendered' => 480
            ];
        });
    }
}
