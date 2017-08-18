<?php


/**
* HomeController
*/
class HomeController extends \BaseController
{

  public function home()
  {
    $res=$GLOBALS['db']->select('articles','*',['LIMIT'=>1]);
    require dirname(__FILE__).'/../views/home/index.php';
  }
}
