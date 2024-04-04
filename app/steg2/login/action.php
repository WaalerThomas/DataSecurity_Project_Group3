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
    $user = new User();
    $isLoggedIn = $user->loginUser();
    if (! $isLoggedIn) {
        $_SESSION["errorMessage"] = "Ugyldig Påloggingsinformasjon";
        header("Location: ./");
        exit;
    }
    header("Location: ../");
}
?>