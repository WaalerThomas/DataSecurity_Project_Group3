<?php
require_once __DIR__ . "/../dbClasses/PasswordToken.php";

/**
 * Isset email key validate
 */
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"])
&& ($_GET["action"] == "reset") && !isset($_POST["action"]))
{
    $error = "";
    $curDate = date("Y-m-d H:i:s");
    $passToken = new PasswordToken();
    $tokenResult = $passToken->getEntryFromKeyAndEmail($_GET["email"], $_GET["key"]);
    if (empty($tokenResult)) {
        $error .= '<h2>Ugyldig link</h2>
        <p>Linken er ugyldig/utløpt. Enten kopierte du ikke den riktige lenken
        fra e-posten, eller du har allerede brukt nøkkelen i så fall
        så er den deaktivert.</p>
        <p><a href="./">
        Klikk her</a> for å resette passord.</p>';
    } else {
        $expDate = $tokenResult[0]["expDate"];
        if ($expDate >= $curDate) {
        ?>
            <br />
            <form method="post" action="" name="update">
                <input type="hidden" name="action" value="update" />
                <br /><br />
                <label><strong>Enter New Password:</strong></label><br />
                <input type="password" name="pass1" maxlength="15" required />
                <br /><br />
                <label><strong>Re-Enter New Password:</strong></label><br />
                <input type="password" name="pass2" maxlength="15" required/>
                <br /><br />
                <input type="hidden" name="email" value="<?php echo $_GET["email"];?>"/>
                <input type="submit" value="Reset Passord" />
            </form>
        <?php
        } else {
            $error .= "<h2>Link Utløpt</h2><p>Linken er utløpt. Du prøver å bruke den utløpte lenken som kun er gyldig i 24 timer (1 dage etter forespørsel).<br /><br /></p>";
        }
    }

    if($error!=""){
        echo "<div class='error'>".$error."</div><br />";
    }	
}

/**
 * Isset email action post
 */
if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
    require_once __DIR__ . "/../dbClasses/User.php";

    $error = "";

    if ($_POST["pass1"] != $_POST["pass2"]) {
        $error .= "<p>Passordene er ikke like.<br><br></p>";
    }

    if ($error != "") {
        echo "<div class='error'>".$error."</div><br />";
    } else {
        $user = new User();
        $user->updateUserPassword($_POST["email"], $_POST["pass1"]);

        $passToken = new PasswordToken();
        $passToken->removeEntry($_POST["email"]);

        echo '<div class="error"><p>Gratulerer! Passordet ditt har blitt oppdatert.</p><p><a href="../login">Klikk her</a> for å logge inn.</p></div><br />';
    }
}
?>