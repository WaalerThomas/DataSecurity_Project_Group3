<?php
require_once __DIR__ . "/class/User.php";

// TODO: Check that all the fields are filled in.

if (! empty($_POST["login"])) {
    session_start();

    $user = new User();
    $isLoggedIn = $user->loginUser();
    if (! $isLoggedIn) {
        $_SESSION["errorMessage"] = "Invalid Credentials";
        header("Location: login.php");
        exit;
    }
    header("Location: index.php");
}
?>