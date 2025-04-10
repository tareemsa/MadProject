<?php

namespace App\Exceptions\Exceptions\Auth;

use Exception;

class PasswordResetTokenInvalidException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid or expired password reset token.', 410);
    }
}
