<?php
/* Note: validate old password */
/* Note: update new password */
session_start();

if (! empty($_POST["change_pass"])) {
    # Check if the passwords don't match
    if($_POST["passord_new"] != $_POST["rep_passord_new"]) {
        $_SESSION["errorMessage"] = "Passordene er ikke like";
        header("Location: bytte_passord.php");
        exit;
    }
}

$_SESSION["errorMessage"] = "AAAAAAAHHHHH Not implemented yet ;)";
header("Location: bytte_passord.php");
?>