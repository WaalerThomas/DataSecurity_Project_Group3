<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Registrering</title>
<style>
    .reg_form {
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

<div class="reg_form">
    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>

    <form action="registrer-action.php" method="post">
        <label>Fullt navn:</label>
        <input type="text" id="full_name" name="full_name" required><br><br>
        <label>E-post:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label>Passord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label>Skriv Passord igjen:</label>
        <input type="password" id="rep_password" name="rep_password" required><br><br>
        <input type="submit" value="Submit" name="register_user">
    </form>
</div>

</body>
</html>