<?php
namespace Core;
use \Core\{DB,Config,JWTHandler};
class JWTAuth extends JWTHandler{

  protected $db;
  protected $headers;
  protected $token;
  public function __construct($headers) {
    parent::__construct();
    $this->headers = $headers;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function isAuth(){
    if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))){
      $this->token = explode(" ", trim($this->headers['Authorization']));
      if(isset($this->token[1]) && !empty(trim($this->token[1]))){
        $data = $this->_jwt_decode_data($this->token[1]);

          if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']){
            $db = DB::getInstance("json");

            $res = $db->selectFirst("user_token")->where("user_id",$data['data']->user_id)->get();

            if($res && $res->token == $this->token[1]){

              $user = $this->fetchUser($data['data']->user_id);
              return $user;
            }
            else{
              return null;
            }
          }
          else{
            return null;
          }
      }
      else{
        return null;
      }
    }
    else{
      return null;
    }
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  protected function fetchUser($user_id){
    try{
      $db = DB::getInstance("json");
      $table = Config::get('guard')['table'];
      $id_name =  Config::get('guard')['table_primary'];
      $res = $db->selectFirst($table)->where($id_name,$user_id)->get();
      if($res){
        return [
          'success' => 1,
          'status' => 200,
          'user' => $res
        ];
      }else{
        return null;
      }
    }
    catch(PDOException $e){
        return null;
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function logout(){
    $user = $this->isAuth();
    $db = DB::getInstance("json");
    $id_name =  Config::get('guard')['table_primary'];
    $res = $db->selectFirst("user_token")->where("user_id",$user['user']->$id_name)->get();
    if($res){
      $db->delete("user_token",['id',$res->id]);
      return true;
    }
    return false;
  }
}
