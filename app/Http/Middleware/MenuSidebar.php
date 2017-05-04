<?php

namespace App\Http\Middleware;

use App\Http\Helpers\MenuTrait;
use Closure;

class MenuSidebar
{
    use MenuTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Load Super Admin Menu
         */
        $this->loadMenu();

        return $next($request);
    }
}
