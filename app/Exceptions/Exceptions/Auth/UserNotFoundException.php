<?php

namespace App\Exceptions\Exceptions\Auth;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('User not found.', 404);
    }
}
