<?php
$app->post('/booktickets', function($request, $response, $args) {
    require_once __DIR__ .'/../bootstrap/dbconnect.php';
   
    $output = array();
    $requestData = array();

    $id = uniqid();

    $requestData['t_id'] = $id;
    $requestData['number'] = $request->getParsedBody()['number'];
    $requestData['name'] = $request->getParsedBody()['name'];
    $requestData['age'] = $request->getParsedBody()['age'];
    $requestData['gender'] = $request->getParsedBody()['gender'];

    $query = $pdo->prepare("INSERT INTO `tickets` (`name`, `age`, `gender`, `number`, `t_id`) 
                           VALUES(:name, :age, :gender, :number, :t_id); ");
    $query->execute($requestData);

    $errorData = $query->errorInfo();

    if($errorData[1]) {
        return checkError($response, $errorData);
    }

    $output['status'] = 200;
    $output['message'] = "Ticket Booked Successfully";
    $output['ticket'] = $requestData;

    $payload = json_encode($output);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
});

//Creaing a node for viewing user details by ticket id

$app->get('/getDetail', function($request, $response, $args) {
    require_once __DIR__. '/../bootstrap/dbconnect.php';

    $ticketId = $request->getQueryParams()['t_id'];

    $query = $pdo->prepare("SELECT * FROM `tickets` WHERE `t_id`=:ticketId");
    $query->bindParam(":ticketId", $ticketId);
    $query->execute();

    $errorData = $query->errorInfo();

    if($errorData[1]) {
        return checkError($response, $errorData);
    }

    $user = $query->fetchAll(PDO::FETCH_ASSOC);

    $output['status'] = 200;
    $output['Message'] = "Ticket fetched Successfully";
    $output['user'] = $user;

    $payload = json_encode($output);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

?>
