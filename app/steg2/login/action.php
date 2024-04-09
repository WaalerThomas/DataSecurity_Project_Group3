<?php
require_once __DIR__ . "/../dbClasses/User.php";

// TODO: Check that all the fields are filled in.

session_start();

require_once __DIR__ . "/../../tools/monolog.php";

$systemLogger = createLogger("login::action");
$systemLogger->pushHandler($systemFileHandler);

$validationLogger = createLogger("login::action");
$validationLogger->pushHandler($validationFileHandler);

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    $validationLogger->alert("CSRFToken is invalid!");

    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

if (! empty($_POST["login"])) {
    // Register login attempt
    $user = new User();
    $ip = $_SERVER["REMOTE_ADDR"];
    $user->registerAttempt($ip);
    $attemptCount = $user->getAttempts($ip);
    if ($attemptCount[0]['Count'] > 3) {
        $systemLogger->alert("More than 3 failed login attempt in the past 10 minutes", ["email" => $_POST["email"]]);
        $_SESSION["errorMessage"] = "For mange innlogging forsøk registrert. Vennligst vent";
        header("Location: ./");
        exit;
    }
    
    // Sanitize and validate user input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationLogger->alert("Email not formatted correctly!");

        $_SESSION["errorMessage"] = "Ikke gyldig epost format oppgitt";
        header("Location: ./");
        exit;
    }
    $pass = filter_var($_POST["password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    $isLoggedIn = $user->loginUser($email, $pass);
    if (! $isLoggedIn) {
        $systemLogger->notice("Failed login attempt", ["email" => $email]);

        $_SESSION["errorMessage"] = "Ugyldig Påloggingsinformasjon";
        header("Location: ./");
        exit;
    }

    // User is logged inn. Cleanup all old login attempts
    $user->cleanOldAttempts();
    header("Location: ../");
}
?>