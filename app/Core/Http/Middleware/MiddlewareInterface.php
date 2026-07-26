<?php
/**
 * Middleware Interface
 */

namespace OmniCMS\Core\Http\Middleware;

interface MiddlewareInterface
{
    /**
     * Handle the request
     * 
     * @param object $request
     * @param callable $next
     * @return mixed
     */
    public function handle($request, callable $next);
}
