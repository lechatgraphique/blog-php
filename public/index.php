<?php
session_start();
require "../vendor/autoload.php";

$whoops = new \Whoops\Run;

$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


$router = new AltoRouter();

$router->map('GET', '/', 'App\Controller\PostController#home');
$router->map('GET', '/articles', 'App\Controller\PostController#index');
$router->map('GET', '/articles/[*:slug]-[i:id]', 'App\Controller\PostController#show');
$router->map('GET', '/contact', 'App\Controller\ContactController#index');
$router->map('GET', '/inscription', 'App\Controller\InscriptionController#index');
$router->map('GET', '/connexion', 'App\Controller\ConnexionController#index');

$router->map('GET', '/dashboard', 'App\Controller\AdminController#index');
$router->map('GET', '/dashboard/articles', 'App\Controller\AdminPostController#index');
$router->map('GET', '/dashboard/articles/[*:slug]-[i:id]', 'App\Controller\AdminPostController#formEdit');
$router->map('POST','/dashboard/articles/update/', 'App\Controller\AdminPostController#update');
$router->map('GET','/dashboard/articles/[*:slug]-[i:id]/delete', 'App\Controller\AdminPostController#delete');
$router->map('GET','/dashboard/articles/form-create', 'App\Controller\AdminPostController#formCreate');
$router->map('POST','/dashboard/articles/create/', 'App\Controller\AdminPostController#create');

$match = $router->match();

if ($match) {
    $path = explode('#', $match['target']);
    $controller = $path[0];
    $method = $path[1];
    if($_POST) {
        $match['params']['post'] = $_POST;
    }
    $params = $match['params'];

    $object = new $controller();
    $object->{$method}($params);

} else {
    header("Status: 404 Not Found", false, 404);
    header("Location: /articles");
}


