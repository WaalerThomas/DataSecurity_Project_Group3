<?php
/**
 * Following this page: https://www.allphptricks.com/forgot-password-recovery-reset-using-php-and-mysql/
 * 
 * How to implement this part:
 * 1. Create a Temporary Token Table
 * 2. Create a Database Connection
 * 3. Create an Index File (Send Email)
 * 4. Create a CSS File
 */

/* Note: Check that a used with that mail exists */
/* Note: Send mail to user with token link thingy */
require_once __DIR__ . "/class/User.php";
require_once __DIR__ . "/class/PasswordToken.php";

session_start();

if (! empty($_POST["email"])) {
    $_SESSION["errorMessage"] = "";

    // Clean email input from user
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (! $email) {
        $_SESSION["errorMessage"] .= "Invalid email address. ";
    } else {
        $user = new User();
        $userExists = $user->getUserByEmail($email);
        if (! $userExists) {
            $_SESSION["errorMessage"] .= "No user is registered with that email address. ";
        }
    }

    // If errors then send back to page with error message
    if (! empty($_SESSION["errorMessage"])) {
        header("Location: glemt_passord.php");
        exit;
    }

    $expFormat = mktime(
        date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
    );
    $expDate = date("Y-m-d H:i:s", $expFormat);
    $key = md5((string)(2418 * 2) . $email);
    $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
    $key = $key . $addKey;

    $passToken = new PasswordToken();
    $isCreated = $passToken->createPasswordResetToken($email, $key, $expDate);
    // TODO: Fix this faulty if
    if (! $isCreated) {
        $_SESSION["errorMessage"] .= "Feilet under oppretting av token. ";
        header("Location: glemt_passord.php");
        exit;
    }

    // NOTE: Maybe we can just fake this part?
    // Send a mail
    $output='<p>Dear user,</p>';
    $output.='<p>Please click on the following link to reset your password.</p>';
    $output.='<p>-------------------------------------------------------------</p>';
    $output.='<p><a href="http://localhost/steg1/reset_password.php?
    key='.$key.'&email='.$email.'&action=reset" target="_blank">
    http://localhost/steg1/reset_password.php
    ?key='.$key.'&email='.$email.'&action=reset</a></p>';		
    $output.='<p>-------------------------------------------------------------</p>';
    $output.='<p>Please be sure to copy the entire link into your browser.
    The link will expire after 1 day for security reason.</p>';
    $output.='<p>If you did not request this forgotten password email, no action 
    is needed, your password will not be reset. However, you may want to log into 
    your account and change your security password as someone may have guessed it.</p>';   	
    $output.='<p>Thanks,</p>';
    $output.='<p>Datasecurity Project Group 3</p>';
    $body = $output; 
    $subject = "Password Recovery - Datasecurity Project Group 3";
}
?>