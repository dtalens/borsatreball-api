<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $exception) {
            if (request()->is('api*')) {
                if ($exception instanceof ModelNotFoundException) {
                    return response()->json(
                        ['message' => $exception->getMessage()],
                        404
                    );
                }
                else if ($exception instanceof QueryException) {
                    return response()->json(
                        ['message' => 'BD error.' , 'errors' => $exception->getMessage()],
                        500
                    );
                }
                else if ($exception instanceof AuthenticationException||$exception instanceof RouteNotFoundException)
                {
                    return response()->json(
                        ['message' => $exception->getMessage()],
                        421
                    );
                }
                else if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
                    return response()->json(
                        ['message' => $exception->getMessage()],
                        401
                    );
                }
                else if ($exception instanceof ValidationException) {
                    $exception = $this->convertExceptionToResponse($exception);
                }

                else if ($exception instanceof UnauthorizedException) {
                    return response()->json(
                        ['message' => $exception->getMessage()],
                        405
                    );
                }
                else if (isset($exception)) {
                    return response()->json(['message' => 'Error de servidor:' . $exception->getMessage()], 500);
                }
            }
        });
    }
}
