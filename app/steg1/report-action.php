<?php
session_start();

$_SESSION["errorMessage"] = "";

if (isset($_POST['report_message']) && isset($_POST['course_name']) && isset($_POST['msg_index'])
&& !empty($_POST['course_name'])) {
    require_once __DIR__ . "/dbClasses/Message.php";

    $message = new Message();
    $messageResult = $message->getAllCourseMessages($_POST['course_name']);
    if (! $messageResult) {
        $_SESSION["errorMessage"] .= "Finner ingen meldinger på oppgitte emne. ";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $msg_index = (int)$_POST['msg_index'];
    $reportResult = $message->reportMessage($messageResult[$msg_index]['idmessages']);
    if (! $reportResult) {
        $_SESSION['errorMessage'] .= "Feilet under oppretting av rapport. ";
    }

    $_SESSION['status_message'] = "Rapport er sendt inn!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>