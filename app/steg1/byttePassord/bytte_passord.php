<?php
// Start the session to be able to access $_SESSION.
session_start();

// Check if they are logged in or not
if (empty($_SESSION["userId"])) {
    header("Location: ./");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Bytte Passord</title>
<style>
    .error_msg {
        color: red;
    }
</style>
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
<form action="bytte_passord-action.php" method="post">
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




