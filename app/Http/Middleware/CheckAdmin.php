<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckAdmin
{
 public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // chưa đăng nhập
        }

        if (Auth::user()->role_id != 1) {
            return redirect()->route('no-permission'); // không phải admin
        }

        return $next($request);
    }
}