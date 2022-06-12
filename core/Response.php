<?php
namespace Core;
class Response{
  public static function redirect($path){
    $fullpath = get_url($path);
    if(!headers_sent()) {
      header('Location:  '.$fullpath,true,301);
    }
    exit;
  }
  public static function back(){
    $prev = $_SERVER['HTTP_REFERER'];
    header("Location:{$prev}");
    exit;
  }
  public static function response($message,$code,$data=null){
    $rep_data = ['data'=>$data,'message'=>$message,'status'=>$code];
    echo json_encode($rep_data);
    exit;
  }
}
?>
