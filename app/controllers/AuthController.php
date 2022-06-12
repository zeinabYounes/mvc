<?php
namespace App\Controllers;
use Core\{Controller,Validator,DB,Request,Response,Auth};
use App\Models\User;
class AuthController extends Controller{
  public $redirectTo = "blogs/index",$cookie_expiration_time;
  public $login_url = "/login";
  public $register_url = "/register";

  public function get_login(){
    $this->view->setSiteTitle("login");
    $this->view->setStatus("guest");
    $this->view->render("auth.login");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function login(Request $req){
    Validator::validate($req->get(),[
    'username'=>['required','string','max:100','min:3'] ,
    'password'=>['required','string','max:2000','min:3']
    ]);
    $auth = new Auth;
    $checkIfMail = filter_var($req->username, FILTER_SANITIZE_EMAIL);
    if($checkIfMail){
      $auth->setUsername('email');
    }
    if($req->token =="on"){
      $token = true;
    }
    else{
      $token = false;
    }
    $credentials = [
      $auth->getUsername()=>$req->username,
      $auth->getPassword()=>$req->password,
      $auth->getToken()=>$token
    ];

    $res = $auth->login($credentials,$auth->getGuard());
    if($res[0]==false && $res[1]=="username"){
      Session::set('errors',['username'=>"This username not exist !"]) ;
      Response::back();
    }else if($res[0]==false && $res[1]=="password"){
      Session::set('errors',['password'=>"This password is Error !"]) ;
      Response::back();
    }else{
      $pass = $auth->getPassword();
      unset($res[1]->$pass);
      Auth::addAuth($res[1],$auth->getGuard());
    //  dd($res);

      Response::redirect($this->redirectTo);
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function getRegister(){
    $this->view->setSiteTitle("register");
    $this->view->setStatus("guest");
    $this->view->render("auth.register");
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function register(Request $req){
    Validator::validate($req->get(),[
    'username'=>['required','string','unique:users','max:30','min:3'] ,
    'email'=>['required','email','unique:users','max:50','min:3'] ,
    'password'=>['required','password','max:20','min:8'],
    'confirm_pass'=>['required','confirm:password']
    ]);
    $user = new User;
    $user->username  = $req->username;
    $user->email = $req->email;
    $user->password = password_hash($req->password, PASSWORD_BCRYPT) ;
    $user->role_id = 1;
    $user->save();
    Response::redirect($this->login_url);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function logout(){
    $auth = new Auth;
    $auth->logout($auth->getGuard());
    Response::redirect($this->login_url);
  }

}
