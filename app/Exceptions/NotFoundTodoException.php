<?php

namespace App\Exceptions;

use Exception;

class NotFoundTodoException extends Exception
{
    public $status_code = 404;
}