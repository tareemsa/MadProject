<?php

namespace App\Exceptions\Exceptions\Auth;

use Exception;

class TwoFactorFailedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid 2FA code.', 422);
    }
}
