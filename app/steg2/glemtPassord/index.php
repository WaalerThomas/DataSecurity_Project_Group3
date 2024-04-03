<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Glemt Passord?</title>
    <link rel="stylesheet" href="style.css">
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

    <form action="action.php" method="post">
        <label>E-post:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Send" name="forgot_pass">
    </form>
</div>

</body>
</html>