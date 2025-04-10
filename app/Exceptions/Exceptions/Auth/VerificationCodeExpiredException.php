<?php

namespace App\Exceptions\Exceptions\Auth;

use Exception;

class VerificationCodeExpiredException extends Exception
{
    public function __construct()
    {
        parent::__construct('Verification code has expired.', 410);
    }
}
