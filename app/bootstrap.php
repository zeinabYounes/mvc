<?php
use \Core\{Config , Router};
use Symfony\Component\Dotenv\Dotenv;
/*
* auto load class
*/
require_once(PROOT . DS . 'lib/dotenv/Dotenv.php');
spl_autoload_register(function ($classname) {
  $parts = explode('\\',$classname);
  $class = end($parts);
  array_pop($parts);
  $path = strtolower(implode(DS,$parts));
  $full_path = PROOT .DS.$path.DS.$class.".php" ;
  if(file_exists($full_path)){
    include_once($full_path);
  }
});
$dotenv = new Dotenv();
$dotenv->load(PROOT . DS . '.env');

$url = Config::get('host_name').Config::get('root_dir');
$view = PROOT.DS."app".DS."views";
define('URL' , $url);
define('VIEW' , $view);
////////////////////////////////////////////////////////////////////////////////////////////////////
require_once(PROOT.DS."core".DS."Helper.php");
////////////////////////////////////////////////////////////////////////////////////////////////////
require_once(PROOT.DS."app".DS."route.php");
 url_error();
?>
