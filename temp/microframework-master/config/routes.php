<?php


use NoahBuscher\Macaw\Macaw;

// Macaw::get('/', function(){
//   echo 'microframework ';
// });

Macaw::get('fuck', function(){
  echo 1111;
});

Macaw::get('(:all)', function($fu) {
  header('Location:'."");
  // echo '未匹配到路由<br>'.$fu;
});

Macaw::get('/', 'HomeController@home');

Macaw::dispatch();
