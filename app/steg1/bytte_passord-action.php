<?php
/* Note: validate old password */
/* Note: update new password */
session_start();
require_once __DIR__ . "/class/User.php";

if (! empty($_POST["change_pass"])) {
    # Check if the passwords don't match
    if($_POST["passord_new"] != $_POST["rep_passord_new"]) {
        $_SESSION["errorMessage"] = "Passordene er ikke like";
        header("Location: bytte_passord.php");
        exit;
    }

    $user = new User();
    $passCheck = $user->passCheck($_SESSION['userId'], $_POST['passord_old']);
    if($passCheck == FALSE) {
        $_SESSION["errorMessage"] = "Gammelt passord matcher ikke";
        header("Location: bytte_passord.php");
        exit;
    }
    
    $findEmail = $user->getEmailById($_SESSION['userId']);
    $user->updateUserPassword($findEmail[0]['email'], $_POST["passord_new"]);
}

echo '<div class="error"><p>Gratulerer! Passordet ditt har blitt oppdatert.</p><p><a href="./">Klikk her</a> for å gå tilbake til hovedsiden.</p></div><br />';
?>