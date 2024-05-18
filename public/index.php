<?php

if(!session_start()) @session_start();

require_once '../vendor/autoload.php';

use League\Plates\Engine;
use DI\ContainerBuilder;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Password\Validator;
use Password\StringHelper;



$builder = new ContainerBuilder;
    
    $builder->addDefinitions([
        PDO::class => function(){
            return new PDO('mysql:host=localhost;dbname=oop_dip_pr;', 'root', '');
        },
        QueryFactory::class => function(){
            return new QueryFactory('mysql');
        },
        Engine::class => function(){
            return new Engine('/public/views');
        },
        Auth::class => function($container){
            return new Auth($container->get('PDO'));
        },
        Validator::class => function(){
            return new Validator(new StringHelper);
        }
    ]);
    $container = $builder->build();




$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    //переход на страница page_register
    $r->addRoute('GET', '/page_register', ['App\AuthController', 'page_register']);
    $r->addRoute('POST', '/register', ['App\AuthController', 'register']);

    //login
    $r->addRoute('POST', '/login', ['App\AuthController', 'login']);
    
    //users
    $r->addRoute('GET', '/users', ['App\UsersController', 'getUsers']);




});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// var_dump($httpMethod);die;
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
// var_dump($uri);die;
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        // var_dump($routeInfo); die;
        echo $container->call($routeInfo[1], $routeInfo[2]);
        break;
}

