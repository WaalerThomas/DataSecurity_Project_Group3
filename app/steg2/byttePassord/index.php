<?php
// Start the session to be able to access $_SESSION.
session_start();

// Generate CSRF token
$_SESSION['CSRF_token'] = bin2hex(random_bytes(35));

// Check if they are logged in or not
if (empty($_SESSION["userId"])) {
    header("Location: ../");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bytte Passord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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
    <label>Gammelt Passord:</label>
    <input type="password" id="passord_old" name="passord_old" required><br><br>
    <label>Nytt Passord:</label>
    <input type="password" id="passord_new" name="passord_new" required><br><br>
    <label>Skriv Passord igjen:</label>
    <input type="password" id="rep_passord_new" name="rep_passord_new" required><br><br>
    <input type="submit" value="submit" name="change_pass">
</form>

</body>
</html>




