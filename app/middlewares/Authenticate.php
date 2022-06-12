<?php
namespace App\Middlewares;
use Core\{Middleware,Response,Request,Cookie,DB,Config,Auth};
use App\Models\User;
use Closure;
class Authenticate extends Middleware{
  public function handle($request = null ,Closure $next = null){
    $cookie = Cookie::exists("user_token");
    $guard = Config::get('guard');
    $table = $guard['table'];
    if(!Auth::check() && $cookie == false){
      Response::redirect("/");
    }
    if(!Auth::check()){
      $db = DB::getInstance();
      $user = $db->selectFirst($table)->where('email',Cookie::get("user_login"))->get();
      Auth::addAuth($user,$table);
    }
    return true;
  }
}
