<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ApiLogs;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $request->start = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response)
    {
        $request->end = microtime(true);
        $this->log($request,$response);
    }
    protected function log($request,$response)
    {
        $duration = $request->end - $request->start;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
       
        $ins_menu=array(
            'ip'=>$ip, 'method'=>$method, 'url'=>$url,'duration'=>$duration,'request_body'=>$request->getContent(),'response'=>$response->getContent(),'created_at'=>date('Y-m-d H:i:s')
        );
    
        $insert = ApiLogs::insert($ins_menu);
    }
}

