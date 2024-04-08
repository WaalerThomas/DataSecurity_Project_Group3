<?php
require_once __DIR__ . "/../../dbClasses/Message.php";
require_once __DIR__ . "/../tools.php";

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../tools/monolog.php";
$apiLogger = createLogger("api::message::index");
$apiLogger->pushHandler($apiFileHandler);

$message = new Message();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    /**
     * Enpoint - /message?course={course_name}
     */
    if (isset($_GET['course']) && !empty($_GET['course'])) {
        $sessionId = getSessionId();
        $idUser = validateSessionId($sessionId);

        $messageResponse = $message->getAllCourseMessages($_GET['course']);
        $json_response = json_encode($messageResponse);
        echo $json_response;
        exit;
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Enpoint - /message?course={course_name}
     */
    if (isset($_POST['course']) && isset($_POST['message']) && !empty($_POST['course']) && !empty($_POST['message'])) {
        $sessionId = getSessionId();
        $idUser = validateSessionId($sessionId);
        
        $messageResponse = $message->createMessage($_POST['message'], $_POST['course'], $idUser);
        if (! $messageResponse) {
            $apiLogger->notice("Failed message creation attempt", ["userId" => $idUser]);

            $respons["errorMessage"] = "Feilet under oppretting av melding";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }
        $respons["status"] = "200";
        $respons["melding"] = "Melding opprettet";
        $json_response = json_encode($respons);
        echo $json_response;
        exit;
    }
}

header("HTTP/1.1 404 Not Found");
exit;
?>