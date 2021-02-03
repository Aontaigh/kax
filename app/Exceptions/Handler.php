<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        /**
         * Handle ModelNotFoundException
         *
         * This occurs when a findOrFail() method is used on a model
         * anywhere within the application.
         */
        if ($exception instanceof ModelNotFoundException) {
            $modelArray       = explode("\\", $exception->getModel()); /* i.e App\\User -> [App, User] */
            $exceptionArrayID = $exception->getIds();

            $message = 'Resource Not Found';
            if (isset($exceptionArrayID[0]) && isset($modelArray[1])) {
                $modelFormattedName = preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $modelArray[1]); /* i.e FootballPlayer -> Football Player */
                $message            = $modelFormattedName . ' (ID: ' . $exceptionArrayID[0] . ') Resource Not Found';
            }

            return response()->json([
                'message' => $message
            ], 404);
        }

        /**
         * Handle AuthorizationException
         *
         * This occurs when an endpoint guarded by a custom FormRequest
         * class (i.e. IndexRequest) and this exception is hit when the
         * autohorize method returns false.
         */
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        /**
         * Handle ValidationException
         *
         * This occurs when an endpoint is validated by a custom FormRequest
         * class (i.e. IndexRequest) and validation fails (i.e. enabled must
         * be a boolean but the request passes ?enabled=4234).
         */
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message'          => 'Validation Error',
                'validation_error' => $exception->errors()
            ], 422);
        }

        /**
         * Handle MethodNotAllowedHttpException
         *
         * This occurs when a request is made to an endpoint that does not
         * support the request type (i.e. a POST request is made to a GET
         * endpoint on the API).
         */
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 404);
        }

        /**
         * Handle other HTTP Exceptions
         */
        if ($this->isHttpException($exception)) {
            $exceptionMessage = $exception->getMessage();

            /**
             * This is when a user hits an endpoint too many times resulting in
             * a cooldown.
             */
            if ($exceptionMessage === 'Too Many Attempts.') {
                return response()->json([
                    'message' => 'Too Many Request Attempts (One Minute Cooldown)'
                ], 429);
            }

            /**
             * Generic default reponse use default exception message and default
             * status code (some exception classes don't have the method getStatusCode
             * hence the check for this as you can see).
             */
            return response()->json([
                'message' => $exceptionMessage
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400);
        }

        return parent::render($request, $exception);
    }
}
