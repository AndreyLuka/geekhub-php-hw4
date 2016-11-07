<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'config';
$controllerName = ucfirst($controllerName) . 'Controller';
$controllerName = 'Controllers\\' . $controllerName;

$connector = new Repositories\Connector(
    $config['database'],
    $config['user'],
    $config['password']
);

$controller = new $controllerName($connector);

$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';
$actionName = $actionName . 'Action';

$response = $controller->$actionName();

echo $response;
