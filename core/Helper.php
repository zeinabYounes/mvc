<?php
use Core\Auth;

  /*
  * get full url from specific path
  * @return string
  */
 function url($path){
   $arr_path = explode("/",$path);
   if($arr_path[0]=="" ||$arr_path[0]==null){
     $url = URL .$path ;
   }
   else{
     $url = URL ."/".$path ;
   }
    echo $url;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////
  function get_url($path){
    $arr_path = explode("/",$path);
    if($arr_path[0]=="" ||$arr_path[0]==null){
      $url = URL .$path ;
    }
    else{
      $url = URL ."/".$path ;
    }
     return $url;
   }
  //////////////////////////////////////////////////////////////////////////////////////////////////
   function url_error(){
    header("HTTP/1.1 404 Not Found");
    exit;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
 function dd($data = [],$die = true){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    if($die){
      die();
    }
  }
  /////////////////////////////////////////////////////////////////////////////////////////
  function get_public( $path ){
    $arr_path = explode("/",$path);
    if($arr_path[0]=="" ||$arr_path[0]==null){
      $path = URL ."/app/public".$path ;
    }
    else{
      $path = URL ."/app/public/".$path ;
    }
     return $path;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  function check_permissions($permName){
    $user = Auth::user();
    $check = false;
    $user_role=  \App\Models\Role::findOrFail($user->role_id);
    $user_perm = $user_role->permis();
    foreach ($user_perm as $key => $value) {
      $user_perms[] = $value->perm_name;
    }
    $res = array_search($permName,$user_perms);
    if($res !== false){
      $check = true;
    }
    return $check;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  function csrfToken(){

    $app_key = \Core\Config::get('app_key');
    $input = "<input type='hidden' name='csrf_token' value='{$token}'>";
    return $input;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  function checkCsrfToken($token){
    $app_key = \Core\Config::get('app_key');
    $len = strlen($app_key);
    $key = substr($token,0,$len);
    if($app_key == $key  )
      return true;
    else
      return false;

  }
  ?>
