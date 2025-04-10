<?php

namespace App\Exceptions;

use Exception;

 class MediaUploadException extends \Exception
    {
        protected $message = 'Failed to upload media.';
    }
    
