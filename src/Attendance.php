<?php

namespace Ianvizarra\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Attendance extends Model
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
    protected $fillable = ['status', 'type', 'user_id'];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('attendance.table');
    }
}
