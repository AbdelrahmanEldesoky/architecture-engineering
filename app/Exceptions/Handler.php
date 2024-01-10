<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($request->is('client') || $request->is('client/*')) {
            return redirect()->guest(route('client.login'));
        }

        return redirect()->guest(route('admin.login'));
    }

    // public function report(Exception $exception)
    // {
    //     if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException && $exception->getCode() == 9) {
    //         return;
    //     }

    //     if ($exception instanceof OAuthServerException || $exception instanceof AuthenticationException) {

    //         if(isset($exception->guards) && isset($exception->guards()[0]) ==='api')
    //         response()->json('Unauthorized', 401) ;
    //         else if ($exception instanceof OAuthServerException)
    //         response()->json('Unauthorized', 401) ;
    //     }



    //     parent::report($exception);
    // }

}
