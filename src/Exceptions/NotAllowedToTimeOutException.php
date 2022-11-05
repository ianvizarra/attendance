<?php

namespace Ianvizarra\Attendance\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class NotAllowedToTimeOutException extends RuntimeException
{
    #[Pure]
    public function __construct($message = 'You are not allowed to time-out yet.')
    {
        parent::__construct();
        $this->message = $message;
    }
}
