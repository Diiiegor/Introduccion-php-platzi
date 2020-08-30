<?php
ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

session_start();
$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cursophp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();

$base_url ='/curso-introduccion-php';
$map = $routerContainer->getMap();
$map->get('index', $base_url.'/',[
    'controller'=>'App\Controllers\IndexController',
    'action'=>'indexAction'
]);
$map->get('addJobs', $base_url.'/jobs/add',[
    'controller'=>'App\Controllers\JobsController',
    'action'=>'getAddJobAction'
]);

$map->post('saveJobs', $base_url.'/jobs/add',[
    'controller'=>'App\Controllers\JobsController',
    'action'=>'getAddJobAction'
]);

$map->get('createUser',$base_url.'/user/create',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'createAction'
]);

$map->post('saveUser', $base_url.'/user/save',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'saveAction'
]);

$map->get('loginForm', $base_url.'/login',[
    'controller'=>'App\Controllers\AuthController',
    'action'=>'getLogin'
]);

$map->post('auth', $base_url.'/auth',[
    'controller'=>'App\Controllers\AuthController',
    'action'=>'authAction'
]);

$map->get('admin', $base_url.'/admin',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getIndex',
    'auth'=>true
]);

$map->get('logout',$base_url.'/logout',[
    'controller'=>'App\Controllers\AuthController',
    'action'=>'getLogOut',
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

function printElement($job) {
    // if($job->visible == false) {
    //   return;
    // }

    echo '<li class="work-position">';
    echo '<h5>' . $job->title . '</h5>';
    echo '<p>' . $job->description . '</p>';
    echo '<p>' . $job->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
}

if(!$route){
    echo "No route";
}
else {
    $handlerData = $route->handler;
    $actionName = $handlerData['action'];
    $controllerName = $handlerData['controller'];
    $needsAuth = $handlerData['auth']?? false;
    $sessionUserId = $_SESSION['userId']??null;
    if ($needsAuth && !$sessionUserId){
        //echo 'protected route';
        //die;

        $controllerName='App\Controllers\AuthController';
        $actionName='getLogin';
    }

    $controller = new $controllerName;
    $response =$controller->$actionName($request);


    foreach ($response->getHeaders() as $name=>$values){
        foreach ($values as $value){
            header(sprintf('%s: %s',$name,$value),false);
        }
    }

    http_response_code($response->getStatusCode());

    echo $response->getBody();
    //var_dump($route->handler) ;
}
/*$route = $_GET["route"]??"/";

if( $route == "/"){
    require "../index.php";
} elseif( $route=="addJob"){
    require "../addJob.php";
}*/
