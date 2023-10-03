<?php


//httpd-vhosts.conf
// DocumentRoot "D:/IT/PHP-zadania/Notatki"
// ServerName notes.localhost
// <Directory "D:/IT/PHP-zadania/Notatki">


declare(strict_types=1);

//laduje klasy
spl_autoload_register(function(string $classNamespace) {
    $path = str_replace(['\\','App/'], ['/',''], $classNamespace);  //zamieniamy '\App Requet' na '/Request'- bo tak
    $path="src/$path.php";
    require_once($path);
});

require_once ("src/Utils/debug.php");  
$configuration= require_once ("config/config.php");  

use App\Controller\AbstractController;
use App\Controller\NoteController;
use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;



$request = new Request($_GET, $_POST, $_SERVER);

try{
    AbstractController::initConfiguration($configuration);  //musi byc przed new Controller
    $controller = new NoteController($request);
    $controller->run();
    //(new Controller($request))->run(); to samo
}catch(ConfigurationException $e){
    echo "Problem z configuracja- prosze o kontakt z adminem.";
}catch(AppException $e){
    echo "Wystąpil błąd w app /<br>";
    echo $e->getMessage();
}
catch(\Throwable $e){
    dump($e);
    echo "Wystąpil błąd :error";
}


