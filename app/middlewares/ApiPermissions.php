<?php
namespace App\Middlewares;
use Core\{Middleware,Auth,Response,Request,JWTAuth};
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Closure;
class ApiPermissions extends Middleware{
  public function handle( $request = null ,Closure $next = null){
    $allHeaders = getallheaders();
    $auth = new JWTAuth($allHeaders);
    $user = $auth->isAuth()['user'];
    if(!$auth->isAuth()){
      return Response::response("Unauthorized",$code = 401);
      exit;
    }else{
      if($request !== null){
        $user_perms = [];
        $user = User::findOrFail($user->id);
        $user_role=  Role::findOrFail($user->role_id);
        $user_perm = $user_role->permis();
        foreach ($user_perm as $key => $value) {
          $user_perms[] = $value->perm_name;
        }
        $diff = array_diff($request,$user_perms);
        if($diff !==[]){
          return Response::response("Forbidden",$code = 403);
          exit;
        }
      }
    }

  }
}
