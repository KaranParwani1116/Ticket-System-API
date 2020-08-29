<?php

//route to update ticket timings
$app->post('/updatetiming', function($request, $response, $args) {
  require_once __DIR__. '/../bootstrap/dbconnect.php';

  $requestData = array();

  $requestData['t_id'] = $request->getParsedBody()['t_id'];
  $requestData['timing'] = $request->getParsedBody()['timing'];

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
})
?>