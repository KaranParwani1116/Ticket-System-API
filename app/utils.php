<?php
//comment
function checkError($response, $errorData) {
    $output['status'] = $errorData[1];
    $output['message'] = IS_APP_LIVE ? "Query failed" : $errorData[2];

    $payload = json_encode($output);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
}

function validate_mobile($mobile) {
    return preg_match('/[0-9]{10}/s', $mobile);
}

function validate_name($name) {
    return preg_match('/^[a-z]*$/i', $name);
}

function checkEmpty($value) {
    if(strlen($value) == 0) {
        return true;
    }
    return false;
}

//function to throw error code on server
function throwError($response, $message) {
    $output = array();

    $output['status'] = 400;
    $output['message'] = $message;

    $payload = json_encode($output);
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
}

?>
