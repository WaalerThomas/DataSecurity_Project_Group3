<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Glemt Passord?</title>
<style>
    .custom_form {
        display: inline-block;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
    }

    .error_msg {
        color: red;
    }
</style>
</head>
<body>

<div class="custom_form">
    <h2>Oppgi din e-post adresse</h2>

    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>

    <form action="glemt_passord-action.php" method="post">
        <label>E-post:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Send" name="forgot_pass">
    </form>
</div>

</body>
</html>