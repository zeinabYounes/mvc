<?php
namespace Core;
use \Core\{Config , View,Response};

class Controller {
  private $controller_name , $action_name;
  public $view ,$response ;

  public  function __construct($controller,$action){
    $this->controller_name = $controller;
    $this->action_name = $action ;
    //if you want to put on View class instead controller class but here is right
    $view_path = Config::get('default_view');
    $this->view = new View($view_path);
  }
}
