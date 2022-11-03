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
            'date' => now()->toDateString(),
            'time' => now()->toTimeString(),
            'created_at' => now(),
        ];
    }

    public function timeIn()
    {
        return $this->state(function () {
            return [
                'type' => 'in'
            ];
        });
    }

    public function thisMorning()
    {
        return $this->state(function () {
            $now = now();
            return [
                'type' => 'in',
                'created_at' => $now->setHour(9)->setMinute(0),
                'date' => $now->toDateString(),
                'time' => $now->toTimeString()
            ];
        });
    }

    public function thisEvening()
    {
        return $this->state(function () {
            $now = now();
            return [
                'type' => 'in',
                'created_at' => $now->setHour(17)->setMinute(0),
                'date' => $now->toDateString(),
                'time' => $now->toTimeString()
            ];
        });
    }

    public function lateThisMorning()
    {
        return $this->state(function () {
            $now = now();
            return [
                'type' => 'in',
                'created_at' => $now->setHour(9)->setMinute(31),
                'date' => $now->toDateString(),
                'time' => $now->toTimeString()
            ];
        });
    }

    public function yesterdayMorning()
    {
        return $this->state(function () {
            $yesterday = now()->subDay();
            return [
                'type' => 'in',
                'created_at' => $yesterday->setHour(9)->setMinute(0),
                'date' => $yesterday->toDateString(),
                'time' => $yesterday->toTimeString()
            ];
        });
    }

    public function yesterdayEvening()
    {
        return $this->state(function () {
            $yesterday = now()->subDay();
            return [
                'type' => 'in',
                'created_at' => $yesterday->setHour(17)->setMinute(0),
                'date' => $yesterday->toDateString(),
                'time' => $yesterday->toTimeString()
            ];
        });
    }

    public function ontime()
    {
        return $this->state(function () {
            return [
                'status' => 'on-time'
            ];
        });
    }

    public function late()
    {
        return $this->state(function () {
            return [
                'status' => 'late'
            ];
        });
    }

    public function timeOut()
    {
        return $this->state(function () {
            return [
                'type' => 'out',
                'minutes_rendered' => 480
            ];
        });
    }
}
