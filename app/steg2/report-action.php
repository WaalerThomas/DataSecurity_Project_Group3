<?php
session_start();

require_once __DIR__ . "/../tools/monolog.php";

$systemLogger = createLogger("report-action");
$systemLogger->pushHandler($systemFileHandler);

$validationLogger = createLogger("report-action");
$validationLogger->pushHandler($validationFileHandler);

$_SESSION["errorMessage"] = "";

if (isset($_POST['report_message']) && isset($_POST['course_name']) && isset($_POST['msg_index'])
&& !empty($_POST['course_name'])) {
    require_once __DIR__ . "/dbClasses/Message.php";

    // Check the CSRF token
    $token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
    if (! $token || $token !== $_SESSION['CSRF_token']) {
        $validationLogger->alert("CSRFToken is invalid!");

        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }

    $message = new Message();
    $messageResult = $message->getAllCourseMessages($_POST['course_name']);
    if (! $messageResult) {
        $systemLogger->notice("Trying to report message that does not exist", ["course" => $_POST["course_name"]]);
        $_SESSION["errorMessage"] .= "Finner ingen meldinger på oppgitte emne. ";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $msg_index = (int)$_POST['msg_index'];
    $reportResult = $message->reportMessage($messageResult[$msg_index]['idmessages']);
    if (! $reportResult) {
        $systemLogger->alert("Failed under creation of report");
        $_SESSION['errorMessage'] .= "Feilet under oppretting av rapport. ";
    }

    $systemLogger->info("INSERT new report", ["course" => $_POST["course_name"]]);

    $_SESSION['status_message'] = "Rapport er sendt inn!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>