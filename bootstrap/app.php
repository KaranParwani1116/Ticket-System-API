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

$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    $payload = array();
    $payload['status'] = $exception->getCode();
    $payload['message'] = $exception->getMessage();

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write( json_encode($payload));

    return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus($exception->getCode() != 0 ? $exception->getCode() : 500);
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->add(new BeforeMiddleware());
//$app->add(new AfterMiddleware());

require_once __DIR__ . '/../app/user.php';
require_once __DIR__. '/../app/utils.php';

$app->run();

