<?php
namespace Core;
use Core\{Validator,DB,Response,Auth};
use Closure;
class Middleware {
  public function handle( $request = null ,Closure $next = null){
     return $next($request);
  }
}
