<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Exception;

class MessageCountMiddleware
{
    public function handle($request ,Closure $next)
    {
        $messageCount=0;

        $messageCount = Message::where('to_user',Auth::user()->id)->where('is_read',0)->count();


        \View::share([
            'messageCount'=>$messageCount ,
        ]);
        return $next($request);
    }
}
