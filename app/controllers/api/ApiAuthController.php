<?php
namespace App\Controllers\Api;
use Core\{Validator,DB,Request,Response,JWTHandler,Auth,JWTAuth};
use App\Models\User;
use App\Models\UserToken;

use Lib\JWT\JWT;
class ApiAuthController extends BaseController{

  public function login(Request $req){
    $errors = Validator::getValidate($req->get(),[
    'username'=>['required','string','max:100','min:3'] ,
    'password'=>['required','string','max:2000','min:3']
    ]);
    if(count($errors) > 0){
      return $this->ReturnError($errors,"400");
    }
     $res = [null,null];

    $auth = new Auth;
    $now = strtotime("now");
    $checkIfMail = filter_var($req->username, FILTER_SANITIZE_EMAIL);
    if($checkIfMail){
      $auth->setUsername('email');
    }
    $credentials = [
      $auth->getUsername()=>$req->username,
      $auth->getPassword()=>$req->password,
    ];
    $guard = $auth->getGuard();
    $user = $auth->getUserByUsername($credentials[$auth->getUsername()],$guard['table']);
    if(!$user){
      $res[0] = false;
      $res[1] = 'username';
    }
    $password = $auth->getPassword();
    $check = password_verify($credentials[$password], $user->$password);
    if(!$check){
      $res[0] = false;
      $res[1] = 'password';
    }
    if($res[0]==false && $res[1]=="username"){
      return $this->ReturnError("Invalid username!",422);
    }else if($res[0]==false && $res[1]=="password"){
      return $this->ReturnError("Invalid Password !",422);
    }else{
      $data = ['username'=>$user->username,'role_id'=>$user->role_id,'email'=>$user->email,'user_id'=>$user->id];
      $jwt = new JWTHandler();
      $token = $jwt->_jwt_encode_data($data);
      return $this->ReturnData("token",$token,$msg="ok");
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function register(Request $req){
    $errors = Validator::getValidate($req->get(),[
    'username'=>['required','string','unique:users','max:30','min:3'] ,
    'email'=>['required','email','unique:users','max:50','min:3'] ,
    'password'=>['required','password','max:20','min:8'],
    'confirm_pass'=>['required','confirm:password']
    ]);
    if(count($errors) > 0){
      return $this->ReturnError($errors,"400");
    }
    $user = new User;
    $user->username  = $req->username;
    $user->email = $req->email;
    $user->password = password_hash($req->password, PASSWORD_BCRYPT) ;
    $user->role_id = 1;
    $user->save();
    return $this->ReturnSuccess($msg="you have registered",$errorNum="0000");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function logout(){
    $allHeaders = getallheaders();
    $jwt_auth = new JWTAuth($allHeaders);
    $logout = $jwt_auth->logout();
    if($logout){
      return $this->ReturnSuccess($msg="you successfully logout",$errorNum="0000");
    }
    else{
      return $this->ReturnError("Unauthorized! you must login is system before. ","401");
    }
  }

}
