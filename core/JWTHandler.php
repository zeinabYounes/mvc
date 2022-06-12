<?php
namespace Core;

include_once(PROOT.DS.'lib/jwt/BeforeValidException.php');
include_once(PROOT.DS.'lib/jwt/ExpiredException.php');
include_once(PROOT.DS.'lib/jwt/SignatureInvalidException.php');
include_once(PROOT.DS.'lib/jwt/JWT.php');

use Lib\JWT\JWT;
use \Core\Config;
class JWTHandler {
    protected $jwt_secrect;
    protected $token;
    protected $token_id;
    protected $issuedAt;//TIME WHEN TOKEN IS GENERATED
    protected $expire;
    protected $issued;
    protected $audience;
    protected $jwt;

  public function __construct()
  {
    // set your default time-zone
    date_default_timezone_set('Africa/Cairo');
    $this->issuedAt = time();
    $this->token_id = base64_encode(random_bytes(16));
    $this->expire = $this->issuedAt + 3600;
    $this->issued = Config::get('app_name');
    $this->audience = Config::get('api_login_url');
    $this->jwt_secrect = Config::get('jwt_secrect');
  }
  /////////////////////////////////////////////////////// ENCODING THE TOKEN////////////////////////
  public function _jwt_encode_data($data){
    $this->token = array(
      "iss" => $this->issued,
      "aud" => $this->audience,
      "iat" => $this->issuedAt,
      "exp" => $this->expire,
      // Payload
      "data"=> $data
    );
    $this->jwt = JWT::encode($this->token, $this->jwt_secrect);
    $db = DB::getInstance("json");
    $res = $db->selectFirst("user_token")->where("user_id",$data['user_id'])->get();
    if($res){
      $db->update("user_token", ['token'=>$this->jwt], $res->id);
    }else{
      $res = $db->insert("user_token",['user_id'=>$data['user_id'],'token'=>$this->jwt]);
    }
    return $this->jwt;
  }

  //////////////////////////////////////////////////////////////////////////////////////////////////
  protected function _errMsg($msg){
    return [
      "auth" => 0,
      "message" => $msg
    ];
  }
  //////////DECODING THE TOKEN//////////////////////////////////////////////////////////////////////
  public function _jwt_decode_data($jwt_token){
    try{
      $decode = JWT::decode($jwt_token, $this->jwt_secrect, array('HS256'));
      return [
        "auth" => 1,
        "data" => $decode->data
      ];
    }
    catch(\Lib\JWT\ExpiredException $e){
        return $this->_errMsg($e->getMessage());
    }
    catch(\Lib\JWT\SignatureInvalidException $e){
        return $this->_errMsg($e->getMessage());
    }
    catch(\Lib\JWT\BeforeValidException $e){
        return $this->_errMsg($e->getMessage());
    }
    catch(\DomainException $e){
        return $this->_errMsg($e->getMessage());
    }
    catch(\InvalidArgumentException $e){
        return $this->_errMsg($e->getMessage());
    }
    catch(\UnexpectedValueException $e){
        return $this->_errMsg($e->getMessage());
    }
  }
}
