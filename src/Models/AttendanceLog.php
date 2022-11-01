<?php

namespace Ianvizarra\Attendance\Models;

use Ianvizarra\Attendance\Database\Factories\AttendanceLogFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AttendanceLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $fillable = ['status', 'type', 'user_id', 'minutes_rendered'];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('attendance.log_table');
    }


    protected static function newFactory()
    {
        return AttendanceLogFactory::new();
    }
}
