<?php
require_once __DIR__ . "/class/PasswordToken.php";

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
        $error .= '<h2>Invalid Link</h2>
        <p>The link is invalid/expired. Either you did not copy the correct link
        from the email, or you have already used the key in which case it is 
        deactivated.</p>
        <p><a href="./glemt_passord.php">
        Click here</a> to reset password.</p>';
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
                <input type="submit" value="Reset Password" />
            </form>
        <?php
        } else {
            $error .= "<h2>Link Expired</h2><p>The link is expired. You are trying to use the expired link which as valid only 24 hours (1 days after request).<br /><br /></p>";
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
    require_once __DIR__ . "/class/User.php";

    $error = "";

    if ($_POST["pass1"] != $_POST["pass2"]) {
        $error .= "<p>Password do not match, both passwords should be the same.<br><br></p>";
    }
    if ($error != "") {
        echo "<div class='error'>".$error."</div><br />";
    } else {
        $user = new User();
        $userResult = $user->updateUserPassword($_POST["email"], $_POST["pass1"]);
        if ($userResult == 0) {
            echo "<p>IT FAILED TO UPDATE</p>";
        }
        else {
            echo "<p>UPDATED USERS</p>";
        }
        echo "UserResult: " . $userResult;
        echo "Email: " . $_POST["email"];
        echo "Pass: " . $_POST["pass1"];

        $passToken = new PasswordToken();
        $passResult = $passToken->removeEntry($_POST["email"]);

        echo '<div class="error"><p>Congratulations! Your password has been updated successfully.</p><p><a href="./login.php">Click here</a> to Login.</p></div><br />';
    }
}
?>