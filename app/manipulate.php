<?php

//route to update ticket timings
$app->post('/updatetiming', function($request, $response, $args) {
  require_once __DIR__. '/../bootstrap/dbconnect.php';

  $requestData = array();

  $requestData['t_id'] = $request->getParsedBody()['t_id'];
  $requestData['timing'] = $request->getParsedBody()['timing'];

  //validating data length
  if(checkEmpty($requestData['t_id']) or checkEmpty($requestData['timing'])) {
      $message = "Parameter can't be null";
      return throwError($response, $message);
  }

  $query = $pdo->prepare("UPDATE `tickets` SET `timing` = :timing WHERE `t_id` = :t_id");
  $query->execute($requestData);

  $errorData = $query->errorInfo();

  if($errorData[1]) {
      return checkError($response, $errorData);
  }

  $output['status'] = 200;
  $output['message'] = "Tickets timing updated successfully";

  $payload = json_encode($output);
  $response->getBody()->write($payload);

  return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

//route to delete ticket
$app->delete('/deleteById/[{t_id}]', function($request, $response, $args) {
    require_once __DIR__. '/../bootstrap/dbconnect.php';
    
    $ticketId = $args['t_id'];

    //validating data
    if(checkEmpty($ticketId)) {
        $message = "Ticket id can't be null";
        return throwError($response, $message);
    }

    $query = $pdo->prepare("DELETE FROM `tickets` WHERE `t_id` = :t_id");
    $query->bindParam('t_id', $ticketId);
    $query->execute();

    $errorData = $query->errorInfo();

    if($errorData[1]) {
        return checkError($response, $errorData);
    }

    $output['status'] = 200;
    $output['message'] = "Tickets Deleted Successfully";
  
    $payload = json_encode($output);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});
?>