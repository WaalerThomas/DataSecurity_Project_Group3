<?php
require_once __DIR__ . "/../../dbClasses/APIToken.php";

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../tools/monolog.php";
$apiLogger = createLogger("api::accessToken::login");
$apiLogger->pushHandler($apiFileHandler);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['hash'])
    && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['hash'])) {
        // Check that keys are valid
        $api_key_header = null;
        $headers = getallheaders();
        foreach ($headers as $name => $value) {
            if ($name == "api_key") {
                $api_key_header = $value;
            }
        }
        if (empty($api_key_header)) {
            $apiLogger->notice("api_key in header is missing");

            $respons["errorMessage"] = "api_key i header finnes ikke";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }

        $token = new APIToken();
        $tokenResult = $token->getTokenByAPIKey($api_key_header);
        if (! $tokenResult) {
            $apiLogger->notice("Cannot find API key in database", ["api_key" => $api_key_header]);

            $respons["errorMessage"] = "Finner ikke angitt api nøkkel i databasen.";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }

        $auth_key = $tokenResult[0]['auth_key'];
        $expDate = $tokenResult[0]['expDate'];
        $curDate = date("Y-m-d H:i:s");
        if ($expDate < $curDate) {
            $respons["errorMessage"] = "Authentication key has expired";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }

        $hash = md5($api_key_header . $auth_key);
        if ($hash != $_POST['hash']) {
            $apiLogger->notice("Not the correct hash given");
            
            $respons["errorMessage"] = "Not the correct hash given";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }

        // Check login details
        require_once __DIR__ . "/../../dbClasses/User.php";
        $email = $_POST["email"];
        $pass = $_POST["password"];
        $user = new User();
        $userResult = $user->loginUser($email, $pass);
        if (! $userResult) {
            $apiLogger->notice("Failed login attempt", ["email" => $email]);

            $respons["errorMessage"] = "Ugyldig Påloggingsinformasjon";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }

        $session_id = md5("SESSIDSALTING" . (string)(2418 * 2));
        $session_id .= substr(md5(uniqid(rand(), 1)), 3, 10);
        $sessionResult = $token->createAPISession($session_id, $userResult[0]['iduser']);
        // TODO: Check that the creation is successfull

        $respons["session_id"] = $session_id;
        $json_response = json_encode($respons);
        echo $json_response;
        exit;
    }
}

header("HTTP/1.1 404 Not Found");
exit;
?>