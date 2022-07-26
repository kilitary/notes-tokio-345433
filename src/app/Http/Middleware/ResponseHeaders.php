<?php

namespace App\Http\Middleware;

use Closure;

class ResponseHeaders
{

    /**
     *
     * Add extra headers for versioning incase versioning is needed for this super simple app :)
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('x-app-version', env('APP_VERSION'));

        return $response;
    }

}
