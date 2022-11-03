<?php

namespace Ianvizarra\Attendance\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self late()
 * @method static self onTime()
 * @method static self overTime()
 * @method static self underTime()
 */
class AttendanceStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'late' => 'late',
            'onTime' => 'on-time',
            'overTime' => 'overtime',
            'underTime' => 'under-time',
        ];
    }
}
