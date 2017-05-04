<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\SessionManager;

class InjectJwtToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function handle($request, \Closure $next)
    {
        if ($request instanceof \Dingo\Api\Http\InternalRequest) {
            if ($this->session->has('jwt_token')) {
                $request->headers->set('authorization', sprintf('Bearer %s', $session->get('jwt_token')));
            }
        }

        return $next($request);
    }
}
