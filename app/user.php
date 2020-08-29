<?php

//route to book ticket
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
    $requestData['timing'] = $request->getParsedBody()['timing'];

    if(ticketCount($response, $requestData['timing']) > 2) {
        $output['status'] = 200;
        $output['message'] = "Tickets full can't book more.";
        
        $payload = json_encode($output);
        $response->getBody()->write($payload);
    
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    $query = $pdo->prepare("INSERT INTO `tickets` (`name`, `age`, `gender`, `number`, `t_id`, `timing`) 
                           VALUES(:name, :age, :gender, :number, :t_id, :timing); ");
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

//route to view ticket by ticket id
$app->get('/getDetailById', function($request, $response, $args) {
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


//route to view tickets by ticket timings
$app->get('/getDetailByDate', function($request, $response, $args) {
    require_once __DIR__. '/../bootstrap/dbconnect.php';

    $timings = $request->getQueryParams()['timings'];

    $query = $pdo->prepare("SELECT * FROM `tickets` WHERE `timing`=:timings");
    $query->bindParam(":timings", $timings);
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

function ticketCount($response, $timings) {
    require __DIR__ .'/../bootstrap/dbconnect.php';
    
    $query = $pdo->prepare("SELECT * FROM `tickets` WHERE `timing`=:timings");
    $query->bindParam(":timings", $timings);
    $query->execute();

    $errorData = $query->errorInfo();

    if($errorData[1]) {
        return checkError($response, $errorData);
    }

    return $query->rowcount();
    
}

?>
