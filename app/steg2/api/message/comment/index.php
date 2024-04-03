<?php
require_once __DIR__ . "/../../../dbClasses/Message.php";
require_once __DIR__ . "/../../tools.php";

header("Content-Type: application/json; charset=UTF-8");

$message = new Message();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    /**
     * Enpoint - /message/comment?course={course_name}&message_index={index}
     */
    if (isset($_GET['msg_id']) && !empty($_GET['msg_id'])) {
        $sessionId = getSessionId();
        $idUser = validateSessionId($sessionId);
        
        $messageResponse = $message->getAllComments($_GET['msg_id']);
        $json_response = json_encode($messageResponse);
        echo $json_response;
        exit;
    }
}

header("HTTP/1.1 404 Not Found");
exit;
?>