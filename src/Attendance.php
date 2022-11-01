<?php

namespace Ianvizarra\Attendance;

use Illuminate\Foundation\Application;

class Attendance
{
    public function __construct(public Application $app)
    {
    }

    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return $this->app->auth->user();
    }

    public function timeIn()
    {
        //TODO: implement method
    }

    public function timeOut()
    {
        //TODO: implement method
    }

    public function log($type, $data)
    {
        //TODO: implement method
    }
}
