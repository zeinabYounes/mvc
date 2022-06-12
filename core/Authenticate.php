<?php
namespace Core;
use App\Models\User;
use Core\{DB,Response,Session,Cookie,Config};

class Authenticate{
  public $redirectTo = "/",$cookie_expiration_time ;
  public $login_url = "/login";
  public $register_url = "/register";
  private $userID = "id";
  private $username = "username" , $password = "password", $rememper_token = "remember_token";
  protected $isAuthenticated = false;
  protected $guard ;
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public  function __construct(){
    $this->cookie_expiration_time = time() + (5*365*30 * 24*60 * 60);//for 5 years
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function login($credentials,$guard){
      $user = $this->getUserByUsername($credentials[$this->username],$guard['table']);
      if(!$user){
        return [false,'username'];
      }
      $password = $this->password;
      $check = password_verify($credentials[$password], $user->$password);
      if(!$check){
        return [false,'password'];
      }
      $checkCookie = Cookie::exists("user_login");
    //  dd($credentials);
      if($credentials[$this->rememper_token]){

        $this->insertRememberCookie($credentials,$guard,$user);
      }
      if(!$credentials[$this->rememper_token] && $checkCookie){///false &&true
        $this->deleteRememberCookie($guard,$user);
      }
      $this->isAuthenticated = true;
      return [true,$user];
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function setAuthStatus($status){
    $this->isAuthenticated = $status;
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////
  public function getAuthStatus(){
    return $this->isAuthenticated;
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////
  public function getGuard(){
    $this->guard  = Config::get('guard') ;
    return $this->guard;
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////
  public function getPrimary(){
    return $this->userID;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function setPrimary($id){
    $this->userID = $id ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getUsername(){
    return $this->username;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function setUsername($name){
    $this->username = $name ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getPassword(){
    return $this->password;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function setPassword($password){
    $this->password = $password ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getToken(){
    return $this->remember_token;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function setToken($token){
    $this->remember_token = $token ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getUserByUsername($username,$table,$json=null) {
    $db = DB::getInstance($json);
    $res = $db->selectFirst($table)->where($this->username,$username)->get();
    return $res;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getTokenByUsername($username,$json=null){
    $db = DB::getInstance($json);
    $column[] = $this->getToken();
    $res = $db->selectFirst($table,[$column])->where($this->username,$username)->get();
    return $res;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  protected function makeToken(){
    $token = md5(time(),true);
    return $token;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  protected function insertRememberCookie($credentials,$guard,$user){
    $expiry = 5*365*30 * 24*60 * 60;
    Cookie::set("user_login",$user->email, $expiry);
    $token_value = $this->makeToken();
    Cookie::set("user_token",$token_value , $expiry);
    $remember_value = password_hash($token_value, PASSWORD_DEFAULT);
    $db = DB::getInstance();
    $id = $this->userID;
    $res = $db->update($guard['table'], [$this->rememper_token =>$remember_value],[$id,$user->$id]);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  protected function deleteRememberCookie($guard,$user){
    Cookie::delete("user_login");
    Cookie::delete("user_token");
    $id = $this->userID;
    $db = DB::getInstance();
    $res = $db->update($guard['table'], [$this->rememper_token =>null],[$id,$user->$id]);

  }
  //////////////////////////////////////////////////////////////////////////////////////////////////



}
