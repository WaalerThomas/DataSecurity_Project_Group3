<?php
/* Note: validate old password */
/* Note: update new password */
session_start();
require_once __DIR__ . "/../dbClasses/User.php";

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

if (! empty($_POST["change_pass"])) {
    # Check if the passwords don't match
    if($_POST["passord_new"] != $_POST["rep_passord_new"]) {
        $_SESSION["errorMessage"] = "Passordene er ikke like";
        header("Location: ./");
        exit;
    }

    $user = new User();
    $passCheck = $user->passCheck($_SESSION['userId'], $_POST['passord_old']);
    if($passCheck == FALSE) {
        $_SESSION["errorMessage"] = "Gammelt passord matcher ikke";
        header("Location: ./");
        exit;
    }
    
    $findEmail = $user->getEmailById($_SESSION['userId']);
    $user->updateUserPassword($findEmail[0]['email'], $_POST["passord_new"]);
}

echo '<div class="error"><p>Gratulerer! Passordet ditt har blitt oppdatert.</p><p><a href="../" target="_top">Klikk her</a> for å gå tilbake til hovedsiden.</p></div><br />';
?>