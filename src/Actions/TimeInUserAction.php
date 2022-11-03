<?php

namespace Ianvizarra\Attendance\Actions;

use Ianvizarra\Attendance\Contracts\CanLogAttendance;
use Ianvizarra\Attendance\DataTransferObjects\AttendanceLogDto;
use Ianvizarra\Attendance\Enums\AttendanceStatusEnum;
use Ianvizarra\Attendance\Exceptions\AlreadyTimeInException;
use Ianvizarra\Attendance\Enums\AttendanceTypeEnum;
use Ianvizarra\Attendance\Exceptions\NotAllowedToTimeInException;
use Ianvizarra\Attendance\Facades\Attendance;
use Illuminate\Support\Carbon;

class TimeInUserAction
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
        if ($user->hasTimeIn()) {
            throw new AlreadyTimeInException();
        }

        if ($user->hasTimeIn()) {
            throw new AlreadyTimeInException();
        }

        if ($user->isOffDay($time)) {
            throw new NotAllowedToTimeInException("It's your day-off");
        }

        if (!$user->isWorkDay($time)) {
            throw new NotAllowedToTimeInException();
        }

        $status = Attendance::timeInStatus($time, $scheduleConfig);

        ($this->logUserAttendanceAction)(new AttendanceLogDto(
            user: $user,
            type: new AttendanceTypeEnum('in'),
            status: new AttendanceStatusEnum($status),
            time: $time ?? new Carbon()
        ));
    }
}
