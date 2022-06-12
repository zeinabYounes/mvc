<?php
namespace App\Middlewares;
use Core\{Middleware,Auth,Response,Request,Cookie,Config};
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Closure;
class Permissions extends Middleware{
  public function handle( $request = null ,Closure $next = null){
    $cookie = Cookie::exists("user_login");
    $guard = Config::get('guard');
    $table = $guard['table'];
    if(!Auth::check() && $cookie == false){
      Response::redirect("/");
    }
    else{
      if(!Auth::check()){
        $db = DB::getInstance();
        $user = $db->selectFirst($table)->where('email',Cookie::get("user_login"))->get();
        Auth::addAuth($user,$table);
      }
      if($request !== null){
        $user_perms = [];
        $user = User::findOrFail(Auth::id());
        $user_role=  Role::findOrFail($user->role_id);
        $user_perm = $user_role->permis();
        foreach ($user_perm as $key => $value) {
          $user_perms[] = $value->perm_name;
        }
        $diff = array_diff($request,$user_perms);
        if($diff !==[]){
          header("HTTP/1.1 403 Forbidden");
          exit;
        }
      }
    }
  }
}
