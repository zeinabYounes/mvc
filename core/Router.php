<?php
namespace Core;
use \Core\{Config,View,Request,Response};
use Closure;
use App\Middlewares\Kernal;
class Router {

  /*
  * method execute function with sepecific Route
  * param1 url of function,
  * param2 route is a string of controllerName and actionName
  * @return void
  */
  public static function getFunction($url,$route){
    $class_data = [];
    $user_url = self::get_url(); // url that user writed
    $comare_urls = self::path_Comp($user_url,$url);
    if($comare_urls[0] == true){
      $pathController = strtok($route, "\\");
      $pathController = ucfirst($pathController) ."\\";
      $controllerName = strtok("@");
      if($controllerName == false){
        $pathController = null;
        $controllerName = strtok($route, "@");
      }
      $action = $controllerName !== false ?strtok("@") :"index";
      $controller = "App\Controllers\\".$pathController.$controllerName;
      $data_get = $comare_urls[1];
      if(!class_exists($controller)){
        throw new \Exception("The controller ".$controllerName ."  does not exist !.");
      }
      // we make object from class because function not static
      $controllerClass = new $controller($controllerName,$action);
      if(!method_exists($controller,$action)){
        throw new \Exception("The method ".$action ."  does not exist in ".$controllerName." controller !.");
      }
      $class_data = ['class'=>$controllerClass,'action'=>$action,'data'=>$data_get];
      return $class_data ;
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function get($url ,$route,$middlewares = []){
    $class_data = self::getFunction($url ,$route);
    $get = Request::isGet();
    if($class_data != null && $get){
      $request = new Request;
      if(!$middlewares==[]){
        $kernal = new Kernal;
        $kernal->middleware($middlewares);
      }
      $class_data['data']['request'] = $request;
      call_user_func_array([$class_data['class'],$class_data['action']],$class_data['data']);
      exit;
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function post($url ,$route ,$middlewares = []){
    $class_data = self::getFunction($url ,$route);
    $post = Request::isPost();

    if($class_data != null && $post){
      $request = new Request;
      if(!$middlewares==[]){
        $kernal = new Kernal;
        $kernal->middleware($middlewares);
      }
      $class_data['data']['request'] = $request;
      call_user_func_array([$class_data['class'],$class_data['action']],$class_data['data']);
      exit;
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////

  /*
  * get url that user writed
  * no param
  * @return url as string
  */
  public static function get_url(){
    $rootdir = Config::get('root_dir');
    $url = $_SERVER['REQUEST_URI'];///cms/blog/5 /cms
    $url = str_replace($rootdir, '', $url);
    $url = parse_url($url, PHP_URL_PATH);
    return $url ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * method take url and extract url path and url data
  * return array of path and data
  */

  public static function extract_url_func($url){
    $data_get = preg_split("/[\s{}]+/", $url);
    $path = $data_get[0];
    array_shift($data_get);
    $path = self::clean(explode("/",$path));
    $data = self::clean($data_get);
    return ['path'=>$path,'data'=>$data];
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * method take two url and compare with paths and data
  * return array of check and data
  */
  public static function path_Comp($user_url,$func_url  ){
    $funcUrl = self::extract_url_func($func_url) ;
    $func_data = $funcUrl['data'];
    $func_path = $funcUrl['path'];
    $user_data = [] ;
    $cont_f_path = count($func_path);
    $cont_func_url = count($func_data)+$cont_f_path ;
    $userUrl = self::clean(explode("/",$user_url));
    $cont_user_url = count($userUrl);
    if($cont_func_url == $cont_user_url){
      while(count($userUrl) > $cont_f_path){
        $user_data[] = array_pop($userUrl);
      }
      $user_data=array_reverse($user_data);
      $compare = array_diff_assoc($func_path,$userUrl);
      $check = empty($compare)? true :false;
      return [$check ,$user_data];
    }
    else{
      $check = false;
      return [$check ,$user_data];
    }
    // var_dump($func_path);
    // var_dump($userUrl);

    // var_dump($check);
    //
  }


  //////////////////////////////////////////////////////////////////////////////////////////////
  /*
  * delete any value null or "/" from array
  */
  public static function clean($array =[]){
    foreach ($array as $key => $value) {
      if($value == null || $value =="/" || $value == "" ){
        unset($array[$key]);
      }
    }
    return $array;
  }
}
