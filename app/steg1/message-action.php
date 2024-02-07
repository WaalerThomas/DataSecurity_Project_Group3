<?php
session_start();
// TODO: Check that the request is sent by someone that has access

require_once __DIR__ . "/dbClasses/Message.php";
$message = new Message();
$_SESSION["errorMessage"] = "";

// Check for new question/message
if (isset($_POST['new-comment']) && isset($_POST['send_message']) && isset($_POST['course_name'])
&& !empty($_POST['new-comment']) && !empty($_POST['course_name'])) {    
    $messageResult = $message->createMessage($_POST['new-comment'], $_POST['course_name'], $_SESSION['userId']);
    if (! $messageResult) {
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
        $_SESSION["errorMessage"] .= "Finner ingen meldinger på oppgitte emne. ";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (isset($_SESSION['userId'])) {
        require_once __DIR__ . "/dbClasses/Course.php";
        $course = new Course();
        $courseResult = $course->getCourseByName($_POST['course_name']);

        // Check if it is the lecturer that is answering
        if ($messageResult[0]['courses_idcourses'] == $courseResult[0]['idcourses'] && $courseResult[0]['users_iduser'] == $_SESSION['userId']) {
            $msg_index = (int)$_POST['msg_index'];
            $msgId = $messageResult[$msg_index]['idmessages'];
            $msgResult = $message->addAnswer($msgId, $_POST['answer-textbox']);
            if (! $msgResult) {
                $_SESSION["errorMessage"] .= "Feilet under oppdatering av meldings svar. ";    
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    
    $msg_index = (int)$_POST['msg_index'];
    $commentResult = $message->createComment($_POST['answer-textbox'], $messageResult[$msg_index]['idmessages']);
    if (! $commentResult) {
        $_SESSION["errorMessage"] .= "Feilet under oppretting av melding. ";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>