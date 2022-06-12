<?php
namespace Core;
use \Core\Config;
class View{

  private $site_title = " ",$content = [] , $current_content , $buffer  ;
  private $default_view_path, $public_path,$view_path,$status ;
  //////////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($path = "" ){
    $this->default_view_path = $path ;
    $this->status = "authed" ;
    $this->site_title = Config::get('default_site_title');
    $this->view_path = PROOT.DS."app".DS."views".DS ;

  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * set layout value
  */
  public function setStatus($status){
    $this->status = $status;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  public function getStatus(){
    return $this->status;
  }

  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * set title value
  */
  public function setSiteTitle($title){
    $this->site_title = $title ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * get title value
  */
  public function getSiteTitle(){
    return $this->site_title;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * this take page path to show it as view
  * param path of page
  * @return void
  */
  public function render($path = "",$data=[]){
    if(empty($path)){
      $path = $this->default_view_path;
    }
    $path = $this->extractAsView($path);
    $fullPath = PROOT.DS."app".DS."views".DS.$path.".php";
    if(!file_exists($fullPath)){
      throw new \Exception("The view ".$path ."  does not exist !.");
    }

    if($data !=[]){
      extract($data);
      // $this->current_content = $data;
    }
    global $errors ;
    $errors = [];
    if(Session::get('errors')){
      $errors = Session::get('errors');
    }
    Session::delete('errors');
    include_once($fullPath);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * this method open buffer with specific key
  * param key
  * @return void
  */
  public function start($key){
    if(empty($key)){
      throw new \Exception("your start method required a valid key");
    }
    $this->buffer = $key;
    ob_start();
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * this method set buffer content in content array after this delete it
  * no param
  * @return void
  */
  public function end(){
    if(empty($this->buffer)) {
      throw new \Exception("You must first run the start method.");
    }
    $this->content[$this->buffer] = ob_get_clean();
    $this->buffer = null;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * this method return content array that contain buffer content if exists specific key used in layouts
  * param key
  * @return array
  */
  public function contents($key){
    if(array_key_exists($key, $this->content)) {
      return $this->content[$key];
    } else {
      return " ";
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * this method include specific layout
  * no param
  * @return void
  */
  public function viewExtend($string){
    $path = $this->view_path;
    $view = $this->extractAsView($string);
    if(!file_exists($path.$view.".php")){
      throw new \Exception("The view ".$path.$view ."  does not exist !.");
    }
    include($path . $view .".php");

  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * get view page from string path
  * param1 path as string sperated with point
  * @return right path
  */
 private function extractAsView($path){
    $arr_path = explode(".",$path);
    $path = null;
    $count = count($arr_path );
    for ($i=0; $i <$count ; $i++) {
      if($i== $count -1){
        $path = $path . $arr_path[$i];
      }
      else{
        $path = $path . $arr_path[$i].DS;
      }
    }
    return $path ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
}
