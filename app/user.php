<?php
$app->post('/booktickets', function($request, $response, $args) {
    require_once __DIR__ .'/../bootstrap/dbconnect.php';
   
    $output = array();
    $requestData = array();

    $requestData['number'] = $request->getParsedBody()['number'];
    $requestData['name'] = $request->getParsedBody()['name'];
    $requestData['age'] = $request->getParsedBody()['age'];
    $requestData['gender'] = $request->getParsedBody()['gender'];

    $query = $pdo->prepare("INSERT INTO `tickets` (`name`, `age`, `gender`, `number`) 
                           VALUES(:name, :age, :gender, :number); ");
    $query->execute($requestData);

    $output['status'] = 200;
    $output['message'] = "Ticket Booked Successfully";
    $output['ticket'] = $requestData;

    $payload = json_encode($output);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
});
?>
