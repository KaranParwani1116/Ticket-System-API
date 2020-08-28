<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../middleware/BeforeMiddleware.php';
require __DIR__ . '/../middleware/AfterMiddleware.php';

$app = AppFactory::create();
$app->setBasePath('/ticketsystem/public');

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->add(new BeforeMiddleware());
$app->add(new AfterMiddleware());


$app->get('/hello/{name}', function ($request, $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

// Run app
$app->run();

