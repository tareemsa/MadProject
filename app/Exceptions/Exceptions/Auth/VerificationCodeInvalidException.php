<?php

namespace App\Exceptions\Exceptions\Auth;

use Exception;

class VerificationCodeInvalidException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid verification code.', 422);
    }
}
