<?php
require_once __DIR__ . "/../dbClasses/User.php";

// TODO: Check that all the fields are filled in.

if (! empty($_POST["login"])) {
    session_start();

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