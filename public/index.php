<?php

if(!session_id()) @session_start();

require_once '../vendor/autoload.php';

use League\Plates\Engine;
use DI\ContainerBuilder;
use Aura\SqlQuery\QueryFactory;
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
            return new Engine(__DIR__ . '/views');
        },
        Validator::class => function(){
            return new Validator(new StringHelper);
        }
    ]);
    $container = $builder->build();




$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\UsersController', 'getUsers']);
    //register and login
    $r->addRoute('GET', '/page_register', ['App\AuthController', 'page_register']);
    $r->addRoute('GET', '/page_login', ['App\AuthController', 'page_login']);
    $r->addRoute('POST', '/register', ['App\AuthController', 'register']);
    $r->addRoute('POST', '/login', ['App\AuthController', 'login']);
    $r->addRoute('GET', '/logaut', ['App\AuthController', 'logaut']);
    
    //users
    $r->addRoute('GET', '/users', ['App\UsersController', 'getUsers']);
    $r->addRoute('GET', '/create_user', ['App\UsersController', 'pageCreateUser']);
    $r->addRoute('POST', '/create', ['App\UsersController', 'insertUser']);
    
    $r->addRoute('GET', '/edit_user/{id:\d+}', ['App\UsersController', 'editUser']);
    $r->addRoute('POST', '/update_user/{id:\d+}', ['App\UsersController', 'updateUser']);

    $r->addRoute('GET', '/edit_security/{id:\d+}', ['App\SecurityMediaController', 'editSecurity']);
    $r->addRoute('POST', '/update_security/{id:\d+}', ['App\SecurityMediaController', 'updateSecurity']);

    $r->addRoute('GET', '/edit_status/{id:\d+}', ['App\SecurityMediaController', 'editStatus']);
    $r->addRoute('POST', '/update_status/{id:\d+}', ['App\SecurityMediaController', 'updateStatus']);

    $r->addRoute('GET', '/edit_avatar/{id:\d+}', ['App\SecurityMediaController', 'editAvatar']);
    $r->addRoute('POST', '/update_avatar/{id:\d+}', ['App\SecurityMediaController', 'updateAvatar']);

    $r->addRoute('GET', '/page_profile/{id:\d+}', ['App\UsersController', 'pageProfile']);

    $r->addRoute('GET', '/delete_user/{id:\d+}', ['App\UsersController', 'deleteUser']);

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
        // var_dump($routeInfo[1]); die;
        echo $container->call($routeInfo[1], $routeInfo[2]);
        break;
}

