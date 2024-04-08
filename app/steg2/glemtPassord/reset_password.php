<?php
require_once __DIR__ . "/../dbClasses/PasswordToken.php";

session_start();

/**
 * Isset email key validate
 */
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"])
&& ($_GET["action"] == "reset") && !isset($_POST["action"]))
{
    // Generate CSRF token
    $_SESSION['CSRF_token'] = bin2hex(random_bytes(35));

    $error = "";

    // Sanitize and validate user input
    $email = filter_var($_GET["email"], FILTER_SANITIZE_EMAIL);
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Ugyldig epost oppgitt. ";
    }
    $key = filter_var($_GET["key"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    if (empty($error)) {
        $curDate = date("Y-m-d H:i:s");
        $passToken = new PasswordToken();
        $tokenResult = $passToken->getEntryFromKeyAndEmail($email, $key);
        if (empty($tokenResult)) {
            $error .= '<h2>Ugyldig link</h2>
            <p>Linken er ugyldig/utløpt. Enten kopierte du ikke den riktige lenken
            fra e-posten, eller du har allerede brukt nøkkelen i så fall
            så er den deaktivert.</p>
            <p><a href="./" target="_top">
            Klikk her</a> for å resette passord.</p>';
        } else {
            $expDate = $tokenResult[0]["expDate"];
            if ($expDate >= $curDate) {
            ?>
                <br />
                <form method="post" action="" name="update">
                    <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                    <input type="hidden" name="email" value="<?php echo $email;?>"/>
                    <input type="hidden" name="action" value="update" />
                    <br /><br />
                    <label><strong>Enter New Password:</strong></label><br />
                    <input type="password" name="pass1" maxlength="15" required />
                    <br /><br />
                    <label><strong>Re-Enter New Password:</strong></label><br />
                    <input type="password" name="pass2" maxlength="15" required/>
                    <br /><br />
                    <input type="submit" value="Reset Passord" />
                </form>
            <?php
            } else {
                $error .= "<h2>Link Utløpt</h2><p>Linken er utløpt. Du prøver å bruke den utløpte lenken som kun er gyldig i 24 timer (1 dage etter forespørsel).<br /><br /></p>";
            }
        }
    }

    if(! empty($error)){
        echo "<div class='error'>".$error."</div><br />";
    }
}

/**
 * Isset email action post
 */
if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
    require_once __DIR__ . "/../dbClasses/User.php";

    $error = "";

    // Sanitize and validate user input
    $email = filter_var($_GET["email"], FILTER_SANITIZE_EMAIL);
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Ugyldig epost oppgitt. ";
    }
    $pass1 = filter_var($_POST["pass1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $pass2 = filter_var($_POST["pass2"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    // Check the CSRF token
    $token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
    if (! $token || $token !== $_SESSION['CSRF_token']) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }

    if ($pass1 != $_POST["pass2"]) {
        $error .= "<p>Passordene er ikke like.<br><br></p>";
    }

    if (! empty($error)) {
        echo "<div class='error'>".$error."</div><br />";
    } else {
        $user = new User();
        $user->updateUserPassword($email, $pass1);

        $passToken = new PasswordToken();
        $passToken->removeEntry($email);

        echo '<div class="error"><p>Gratulerer! Passordet ditt har blitt oppdatert.</p><p><a href="../login" target="_top">Klikk her</a> for å logge inn.</p></div><br />';
    }
}
?>