<?php
require_once __DIR__ . "/../../dbClasses/Message.php";

//header("Content-Type: application/json; charset=UTF-8");

$message = new Message();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    /**
     * Enpoint - /message?course={course_name}
     */
    if (isset($_GET['course']) && !empty($_GET['course'])) {
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
    if (isset($_POST['course']) && isset($_POST['message'])) {
        $messageResponse = $message->createMessage($_POST['message'], $_POST['course'], 2);
        if (! $messageResponse) {
            $respons["errorMessage"] = "Feilet under oppretting av melding";
            $json_response = json_encode($respons);
            echo $json_response;
            exit;
        }
        $json_response = json_encode($messageResponse);
        echo $json_response;
        exit;
    }
}

header("HTTP/1.1 404 Not Found");
exit;
?>