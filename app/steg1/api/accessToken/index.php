<?php
require_once __DIR__ . "/../../dbClasses/APIToken.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Generate API and AUTH keys
    $api_key = md5("littleAPISalt" . (string)(2418 * 2));
    $api_key .= substr(md5(uniqid(rand(), 1)), 3, 10);
    $auth_key = md5("someOtherAuthSalt". (string)(2418 * 2));
    $auth_key .= substr(md5(uniqid(rand(), 1)), 3, 10);

    $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m"), date("d") + 14, date("Y")
    );
    $expDate = date("Y-m-d H:i:s", $expFormat);

    $apiToken = new APIToken();
    $isCreated = $apiToken->createToken($api_key, $auth_key, $expDate);
    if (! $isCreated) {
        $respons["errorMessage"] = "Feilet under oppretting av api token";
        $json_response = json_encode($respons);
        echo $json_response;
        exit;
    }

    $json_response = json_encode($isCreated[0]);
    echo $json_response;
    exit;
}

header("HTTP/1.1 404 Not Found");
exit;
?>