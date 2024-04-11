<?php
// Start the session to be able to access $_SESSION.
session_start();

// Generate CSRF token
$_SESSION['CSRF_token'] = bin2hex(random_bytes(35));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
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
        <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
        <label>E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label>Passord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="submit">
    </form>

    <br>

    <a href="../glemtPassord/" rel="noopener noreferrer" target="_top">Glemt passord?</a>
</div>

</body>
</html>