<?php

namespace Ianvizarra\Attendance\Actions;

use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Ianvizarra\Attendance\Exceptions\AlreadyTimeInException;
use Ianvizarra\Attendance\Enums\AttendanceTypeEnum;
use Ianvizarra\Attendance\Exceptions\AlreadyTimeOutException;
use Ianvizarra\Attendance\Exceptions\NoTimeInException;
use Ianvizarra\Attendance\Facades\Attendance;
use Illuminate\Support\Carbon;

class TimeOutUserAction
{
    public function __construct(public LogUserAttendanceAction $logUserAttendanceAction)
    {
    }

    /**
     * @param CanLogAttendance $user
     * @param ?Carbon $time
     * @throws AlreadyTimeInException
     */
    public function __invoke(CanLogAttendance $user, Carbon $time = null, array $scheduleConfig = null): void
    {
        if (! $user->hasTimeIn()) {
            throw new NoTimeInException();
        }

        if ($user->hasTimeOut()) {
            throw new AlreadyTimeOutException();
        }

        $status = Attendance::timeOutStatus($time, $scheduleConfig);

        ($this->logUserAttendanceAction)(new AttendanceLogDto(
            user: $user,
            type: new AttendanceTypeEnum('out'),
            status: new AttendanceStatusEnum($status),
            time: $time ?? new Carbon()
        ));
    }
}
