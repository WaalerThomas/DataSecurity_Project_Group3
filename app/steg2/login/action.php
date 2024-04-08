<?php
require_once __DIR__ . "/../dbClasses/User.php";

// TODO: Check that all the fields are filled in.

session_start();

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

if (! empty($_POST["login"])) {
    // Sanitize and validate user input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errorMessage"] = "Ikke gyldig epost format oppgitt";
        header("Location: ./");
        exit;
    }
    $pass = filter_var($_POST["password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    $user = new User();
    $isLoggedIn = $user->loginUser($email, $pass);
    if (! $isLoggedIn) {
        $_SESSION["errorMessage"] = "Ugyldig Påloggingsinformasjon";
        header("Location: ./");
        exit;
    }
    header("Location: ../");
}
?>