<?php
namespace Core;
use Core\DB;
use Core\{Response,Session};
class Validator{
  protected static $errors = [];

  protected static $messages = [
    'required'=>" The :attribute is required !",
    'string' =>"The :attribute is must be valid string!",
    'email' =>"The :attribute is must be valid email!",
    'file' =>"The :attribute is must be  file!",
    'image' =>"The :attribute is must be  image!",
    'number' =>"The :attribute is must be  number! ",
    'unique' =>"The :attribute is must be  unique!",
    'password' =>"The :attribute is must contain one digit and be between 6-20 character!",
    'array' =>"The :attribute is must be array!",
    'max' =>"The :attribute is must be not maximum :condition!",
    'min' =>"The :attribute is must be not minimum :condition!",
    'confirm' =>"The :attribute is must be same :condition! ",
  ];
  //////////////////////////////////////////////////////////////////////////////////////////////////
  // validte($request,[
  //   'name'=>['required','string','max:100','unique:users','confirm:password'],
  //   'email'=>[],
  //   'password'=>[],
  //   'phone'=>[]
  // ])
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function setMessage($key,$value){
    self::$messages[$key] = $value;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getMessage(){
    return self::$messages;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function setAttrMsg($rule,$attribute,$replaced){
    self::$messages[$rule] = str_replace($replaced,"(".$attribute.")",self::$messages[$rule]);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function setDefaultAttrMsg($rule,$attribute,$replaced){
    self::$messages[$rule] = str_replace("(".$attribute.")",$replaced,self::$messages[$rule]);  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getValidate($data,$attributes){
    self::getError($data,$attributes,"json");
    return  self::$errors;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getError($data,$attributes,$json=null){
    Session::delete('errors');
    foreach ($data as $key => $value) {
      if(array_key_exists($key,$attributes)){
        foreach ($attributes[$key] as $value) {
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "required"){
            $check = self::validRequired($data[$key]);
            if(!$check){
              self::setAttrMsg("required",$key,":attribute");
              self::$errors[$key] = self::$messages['required'];
              self::setDefaultAttrMsg("required",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "string"){
            $check = self::validString($data[$key]);
            if(!$check){
              self::setAttrMsg("string",$key,":attribute");
              self::$errors[$key] = self::$messages['string'];
              self::setDefaultAttrMsg("string",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "email"){
            $check = self::validEmail($data[$key]);
            if(!$check){
              self::setAttrMsg("email",$key,":attribute");
              self::$errors[$key] = self::$messages['email'];
              self::setDefaultAttrMsg("email",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "image"){

          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "file"){

          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "number"){
            $check = self::validNumber($data[$key]);
            if(!$check){
              self::setAttrMsg("number",$key,":attribute");
              self::$errors[$key] = self::$messages['number'];
              self::setDefaultAttrMsg("number",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if(strstr($value,":",true) == "unique"){
            $rest = strstr($value,":");//:100
            $table = trim($rest,":");
            //dd($table);
            $check = self::validUnique($key,$data[$key],$table,$json);
            if(!$check){
              self::setAttrMsg("unique",$key,":attribute");
              self::$errors[$key] = self::$messages['unique'];
              self::setDefaultAttrMsg("unique",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if(strstr($value,":",true)=="max"){
            $rest = strstr($value,":");//:100
            $number = trim($rest,":");
            $check = self::validMax($data[$key],$number);
            if(!$check){
              self::setAttrMsg("max",$key,":attribute");
              self::setAttrMsg("max",$number,":condition");
              self::$errors[$key] = self::$messages['max'];
              self::setDefaultAttrMsg("max",$key,":attribute");
              self::setDefaultAttrMsg("max",$number,":condition");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if(strstr($value,":",true)=="min"){
            $rest = strstr($value,":");//:100
            $number = trim($rest,":");
            $check = self::validMin($data[$key],$number);
            if(!$check){
              self::setAttrMsg("min",$key,":attribute");
              self::setAttrMsg("min",$number,":condition");
              self::$errors[$key] = self::$messages['min'];
              self::setDefaultAttrMsg("min",$key,":attribute");
              self::setDefaultAttrMsg("min",$number,":condition");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if(strstr($value,":",true)=="confirm"){
            $rest = strstr($value,":");//:100
            $other_attr = trim($rest,":");
            $check = self::validMatched($data[$key],$data[$other_attr]);
            if(!$check){
              self::setAttrMsg("confirm",$key,":attribute");
              self::setAttrMsg("confirm",$number,":condition");
              self::$errors[$key] = self::$messages['confirm'];
              self::setDefaultAttrMsg("confirm",$key,":attribute");
              self::setDefaultAttrMsg("confirm",$other_attr,":condition");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "password"){
            $check = self::validPassword($data[$key]);
            if(!$check){
              self::setAttrMsg("password",$key,":attribute");
              self::$errors[$key] = self::$messages['password'];
              self::setDefaultAttrMsg("password",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
          if($value == "array"){
            $check = self::validArray($data[$key]);
            if(!$check){
              self::setAttrMsg("array",$key,":attribute");
              self::$errors[$key] = self::$messages['array'];
              self::setDefaultAttrMsg("array",$key,":attribute");
              break;
            }
          }
          //////////////////////////////////////////////////////////////////////////////////////////
        }
      }
    }

  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validate($data,$attributes){
    self::getError($data,$attributes);
    if(count(self::$errors) > 0){
      Session::set('errors',self::$errors) ;
      Response::back();
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validMax($value,$rule){
    $pass = strlen($value) <= $rule;
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validMin($value,$rule){
    $pass = strlen($value) >= $rule;
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validMatched($value,$rule){
    return $value == $rule;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validEmail($email){
    $pass = true;
    if(!empty($email)) {
      $pass = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validUnique($attribute,$value,$table,$json){
    $db = DB::getInstance($json);
    $res = $db->selectFirst($table,[$attribute])->where($attribute,$value)->get();
    if($res == false) {
      return true;
    }
    return false;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validString($value){
    $pass = is_string( filter_var(trim($value), FILTER_SANITIZE_STRING));
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validNumber($value){
    $pass = is_int($value);
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validRequired($value){
    $pass = $value != '' && isset($value) && !empty($value) && $value != null;
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static  function validPassword($password){
    $pass = preg_match("/^(?=.*\d)(?=.*[a-z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $password);
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validArray($value){
    $pass = is_array($value);
    return $pass;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validImage(){

  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function validFile(){

  }


}
