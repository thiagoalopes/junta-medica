<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\ErrorTemplate;
use Illuminate\Http\Request;

class EntityNotFoundExpecion extends Exception {

    protected $message;
    private $exception;
    private $status;
    private $path;

    function __construct($message, $exception, $status, $path)
    {
        $this->message = $message;
        $this->exception = $exception;
        $this->status = $status;
        $this->path = $path;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json((array) new ErrorTemplate($this->message, $this->status, $this->path), $this->status);
    }

}
