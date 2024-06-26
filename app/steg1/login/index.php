<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
    .login_form {
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

<div class="login_form">
    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>

    <form action="action.php" method="post">
        <label>E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label>Passord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="submit">
    </form>

    <br>

    <a href="../glemtPassord/">Glemt passord?</a>
</div>

</body>
</html>