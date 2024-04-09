<?php
session_start();
// TODO: Check that the request is sent by someone that has access

require_once __DIR__ . "/../tools/monolog.php";

$systemLogger = createLogger("message-action");
$systemLogger->pushHandler($systemFileHandler);

$validationLogger = createLogger("message-action");
$validationLogger->pushHandler($validationFileHandler);

require_once __DIR__ . "/dbClasses/Message.php";
$message = new Message();
$_SESSION["errorMessage"] = "";

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    $validationLogger->alert("CSRFToken is invalid!");

    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

// Check for new question/message
if (isset($_POST['new-comment']) && isset($_POST['send_message']) && isset($_POST['course_name'])
&& !empty($_POST['new-comment']) && !empty($_POST['course_name'])) {    
    $comment = htmlspecialchars($_POST["new-comment"], ENT_QUOTES);
    
    // Check that the message length is not longer than 250 characters
    if (strlen($comment) > 250) {
        $_SESSION["errorMessage"] .= "Maks melding lengde er 250 tegn. ";
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    }

    $messageResult = $message->createMessage($comment, $_POST['course_name'], $_SESSION['userId']);
    if (! $messageResult) {
        $systemLogger->alert("Failed during creation of message", ["userId" => $_SESSION["userId"]]);
        $_SESSION["errorMessage"] .= "Feilet under oppretting av melding. ";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Check for new answer/comment
if (isset($_POST['send_comment']) && isset($_POST['course_name']) && isset($_POST['msg_index']) && isset($_POST['answer-textbox'])
&& !empty($_POST['answer-textbox']) && !empty($_POST['course_name'])) {
    $messageResult = $message->getAllCourseMessages($_POST['course_name']);
    if (! $messageResult) {
        $systemLogger->alert("No messages for given subject", ["subject" => $_POST["course_name"]]);
        $_SESSION["errorMessage"] .= "Finner ingen meldinger på oppgitte emne. ";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    require_once __DIR__ . "/dbClasses/Course.php";
    $course = new Course();

    // Sanitize user input
    $answer = htmlspecialchars($_POST["answer-textbox"], ENT_QUOTES);
    $msg_index = (int)filter_var($_POST["msg_index"], FILTER_SANITIZE_NUMBER_INT);

    // Check that the message length is not longer than 250 characters
    if (strlen($answer) > 250) {
        $_SESSION["errorMessage"] .= "Maks melding lengde er 250 tegn. ";
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    }

    if (isset($_SESSION['userId'])) {
        $courseResult = $course->getCourseByName($_POST['course_name']);

        // Check if it is the lecturer that is answering
        if ($messageResult[0]['courses_idcourses'] == $courseResult[0]['idcourses'] && $courseResult[0]['users_iduser'] == $_SESSION['userId']) {
            $msgId = $messageResult[$msg_index]['idmessages'];
            $message->addAnswer($msgId, $answer);
            
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // Check if they have access to comment
    if (isset($_SESSION['userId']) || (isset($_SESSION['access_hash']) && !empty($_SESSION['access_hash'])) ) {
        // Validate the hash
        if (isset($_SESSION['access_hash'])) {
            $courseResult = $course->getCourseByName($_POST['course_name']);
            if (! $courseResult) {
                $validationLogger->alert("Invalid subject given", ["subject" => $_POST["course_name"]]);
                $_SESSION['errorMessage'] .= "Ugyldig emnenavn oppgitt. ";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $trueHash = md5($courseResult[0]['pin'] . "emneCourseSaltyBabeThingy" . $_POST['course_name']);
            if ($trueHash != $_SESSION['access_hash']) {
                $validationLogger->alert("Hash does not match");
                $_SESSION['errorMessage'] .= "Hashen samsvarer ikke. ";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        // Hash is valid if it gets here
        
        // It is a student or a guest user that is commenting
        $commentResult = $message->createComment($answer, $messageResult[$msg_index]['idmessages']);
        if (! $commentResult) {
            $systemLogger->alert("Failed during creation of message");
            $_SESSION["errorMessage"] .= "Feilet under oppretting av melding. ";
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>