<?php
namespace Core;
require_once(PROOT.DS."app".DS."apiRoute.php");
use App\Api;
class Request {
  protected $type;
  public  function __construct(){
    $path = $_SERVER['REQUEST_URI'];
    $arr_path = explode("/",$path);

    foreach ($arr_path as $key => $value) {
      if($value ==" "||$value =="cms"){
        unset($arr_path[$key]);
      }
    }
    $str_path =implode("/",$arr_path);
    $api = Api::$route;
    $get = array_search($str_path,$api,true);
    if(self::getRequestMethod()==='POST' && $get === false){
      $check= array_key_exists('csrf_token',$_REQUEST);
      if(!$check || !checkCsrfToken($_REQUEST['csrf_token'])){
        throw new \Exception("csrf token error !",500);
      }
    }
    foreach($_REQUEST as $field => $value) {
      $this->$field = self::sanitize($value);
    }
  }
  public static function isPost() {
    return self::getRequestMethod() === 'POST';
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function isPut(){
    return self::getRequestMethod() === 'PUT';
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function isGet(){
    return self::getRequestMethod() === 'GET';
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function isDelete(){
    return self::getRequestMethod() === 'DELETE';
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function isPatch(){
    return self::getRequestMethod() === 'PATCH';
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getRequestMethod(){
    return strtoupper($_SERVER['REQUEST_METHOD']);
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public function get($input = false) {
    if(!$input) {
      $data = [];
      foreach($_REQUEST as $field => $value) {
        $data[$field] = self::sanitize($value);
      }
      return $data;
    }
    return array_key_exists($input, $_REQUEST)? self::sanitize($_REQUEST[$input]) : false;
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function sanitize($dirty) {
    return htmlentities(trim($dirty), ENT_QUOTES, "UTF-8");
  }
////////////////////////////////////////////////////////////////////////////////////////////////////
}
