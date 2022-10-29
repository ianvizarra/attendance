<?php

namespace Ianvizarra\Attendance\Commands;

use Illuminate\Console\Command;

class AttendanceCommand extends Command
{
    public $signature = 'attendance';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
