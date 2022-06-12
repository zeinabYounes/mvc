<?php
namespace App\Middlewares;
use Core\{Middleware,Response,Request,JWTAuth};
use Closure;
class ApiAuth extends Middleware{
  public function handle($request = null ,Closure $next = null){
    $allHeaders = getallheaders();
    $auth = new JWTAuth($allHeaders);
    if(!$auth->isAuth()){
      return Response::response("Unauthorized",$code = "401");
    }else{
      return true;
    }
  }
}
