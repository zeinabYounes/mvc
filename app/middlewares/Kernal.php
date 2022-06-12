<?php
namespace App\Middlewares;
use Core\Request;
use Closure;
class Kernal {
  public $middleware =[
    'auth'=> "\App\Middlewares\Authenticate",
    'perm'=> "\App\Middlewares\Permissions",
    'api_auth'=>"\App\Middlewares\ApiAuth",
    'api_perm'=>"\App\Middlewares\ApiPermissions",
  ];
////////////////////////////////////////////////////////////////////////////////////////////////
  public function middleware($middlewares , $request = null, Closure $next = null){
    $middlewareClass = $this->middleware;
    $perms = [];
    foreach ($middlewares as $key1 => $middle) {
      $perm = strtok($middle, ":");
      $classfromtoken = $perm;

      while ($perm != false)
      {
        $perm = strtok(",");
        if($perm != false)
         $perms[] = $perm;
      }
      $request=$perms;
      foreach ($middlewareClass as $key => $class) {
        if($key == $classfromtoken){
          $midClass = new $class();
          $midClass->handle($request,$next);
        }
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////
}
