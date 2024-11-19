<?php

namespace App\Exceptions;

use Error;
use Exception;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException)
        {
            return response()->json([
                'success' => false,
                'error' => $exception->validator->messages(),
            ], 400);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'error' => 'API not found.',
            ], 404);
        }

        if ($exception instanceof Exception || $exception instanceof Error)
        {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
