<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Zhukmax\Slim\Controllers\UserController;

$app = AppFactory::create();

ORM::configure([
    'connection_string' => 'mysql:host=127.0.0.1;dbname=mydb',
    'username' => 'root',
    'password' => '124'
]);

$app->get('/', [UserController::class, "getMax"]);
$app->get('/users', [UserController::class, "list"]);
$app->get('/users/{id}', [UserController::class, "getOne"]);
$app->post('/users', [UserController::class, "add"]);
$app->put('/users', [UserController::class, "update"]);
$app->delete('/users', [UserController::class, "delete"]);

$app->run();
