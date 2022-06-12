<?php
namespace Core;
class Config{
  private static $config = [
    'version'            => '0.0.1' ,
    'app_key'            => '$2y$10$TXv/QOGSe8rKlBf4pA92d.Bw0FeB02ZWGm4ufOlCdO.8zhgH68S2K',
    'root_dir'           => '/cms'  ,          //root dir will be '/' on live
    'host_name'          => 'http://localhost',
    'app_name'           => 'cms',
    'default_url'        => 'http://localhost/cms',
    'api_login_url'      => 'http://localhost/api/login',
    'default_controller' => 'BlogController'  ,          //the default home controller
    'default_site_title' => 'first_cms_app',
    'login_cookie_name'  => 'asdyefhl1545' ,
    'guard'              =>  ['table'=>'users','api'=>'jwt','table_primary'=>"id"]//['type'=>'web','table'=>'users']
  ];
  ////////////////////////////////////////////////////////////////////////////////////////
  public static function get($key){
    if(array_key_exists($key,$_ENV)) return $_ENV[$key];
    return array_key_exists($key,self::$config)? self::$config[$key] : NULL;
  }

}
?>
