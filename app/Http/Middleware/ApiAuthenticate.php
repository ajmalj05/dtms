<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiAuthenticate extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            throw new UnauthorizedHttpException('Unauthorized');
        } else {
            parent::unauthenticated($request, $guards);
        }
    }
}
