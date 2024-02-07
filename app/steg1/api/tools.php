<?php

function getSessionId() {
    $sessionId = null;
    $headers = getallheaders();
    foreach ($headers as $name => $value) {
        if ($name == "session_id") {
            $sessionId = $value;
        }
    }

    // No session id in the header
    if ($sessionId == null) {
        $respons["errorMessage"] = "session_id mangler i header";
        $json_response = json_encode($respons);
        echo $json_response;
        exit;
    }

    return $sessionId;
}

// Return the user_id if valid session_id
function validateSessionId($sessionId) {
    require_once __DIR__ . "/../dbClasses/APIToken.php";

    $token = new APIToken();
    $tokenResponse = $token->getSessionAccountId($sessionId);
    if (! $tokenResponse) {
        $respons["errorMessage"] = "Finner ingen sesjon med den id'en";
        $json_response = json_encode($respons);
        echo $json_response;
        exit;
    }

    return $tokenResponse[0]['users_iduser'];
}