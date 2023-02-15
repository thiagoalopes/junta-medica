<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\ErrorTemplate;
use Illuminate\Http\Request;

class StoreExpecion extends Exception {

    protected $message;
    private $exception;

    function __construct($message, $exception, Request $request)
    {
        $this->message = $message;
        $this->exception = $exception;
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
        return response()->json((array) new ErrorTemplate($this->message, 500, $request->url()), 500);
    }

}
