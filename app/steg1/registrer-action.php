<?php
if (! empty($_POST["register_user"])) {
    session_start();

    # Check if the passwords don't match
    if($_POST["password"] != $_POST["rep_password"]) {
        $_SESSION["errorMessage"] = "Passordene er ikke like";
        header("Location: registrer.php");
        exit;
    }

    $_SESSION["errorMessage"] = "AAAAAAAHHHHH Not implemented yet ;)";
    header("Location: registrer.php");
    exit;
}
?>