<?php

namespace App\Exceptions;

use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    use Helpers;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        $env = env('APP_ENV');

        $errorsObj = [
            ModelNotFoundException::class,
            NotFoundHttpException::class,
            MethodNotAllowedHttpException::class,
        ];

        if (in_array(get_class($e), $errorsObj)) {
            if ('production' == $env) {
                $code = $e->getCode() ? $e->getCode() : 404;
                return $this->response->error(trans('Oops, This item not found or may be it\'s not avaiable any more!'), $code);
            }
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpException && \Auth::guest()) {

            Session::put('redirect_url', $request->fullUrl());
            Session::save();

            return redirect(url('/'));
        }

        return parent::render($request, $e);
    }
}
