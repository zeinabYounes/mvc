<?php
namespace Core;
use Core\{Authenticate,Session};
class Auth extends Authenticate{
  public  function __construct(){
    $this->setPrimary('id');
    $this->setUsername('username');
    $this->setPassword('password');
    $this->setToken('remember_token');
  }
  public static function user(){
    return Session::get('user');
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function id(){
    $user = Session::get('user');
    return $user->id;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function guard($guard){
    return Session::set('guard',$guard);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function check(){
    $check = Session::exists('user');
    if($check){
      return true;
    }
    return false;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function addAuth($user,$guard){

    $check = Session::exists('user');
    if($check){
      Session::delete('user');
      Session::delete('guard');
    }
    Session::set('user',$user);
    Session::set('guard',$guard);
  }

  public function logout($guard){
    $user = static::user();
    $this->setAuthStatus(false);
    $this->deleteRememberCookie($guard,$user);
    Session::delete('user');
    Session::delete('guard');
  }
}
