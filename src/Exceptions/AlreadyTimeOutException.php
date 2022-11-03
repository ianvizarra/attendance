<?php

namespace Ianvizarra\Attendance\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class AlreadyTimeOutException extends RuntimeException
{
    #[Pure]
    public function __construct($message = "You have already time-out today.")
    {
        parent::__construct();
        $this->message = $message;
    }
}
