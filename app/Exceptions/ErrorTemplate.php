<?php

namespace App\Exceptions;

use Carbon\Carbon;

class ErrorTemplate
{
    public $timestamp;
    public $status;
    public $message;
    public $path;

    function __construct($message, $status, $path)
    {
        $this->message = $message;
        $this->status = $status;
        $this->path = $path;
        $this->timestamp = Carbon::now()->format('Y-m-d H:i:s');
    }

}
