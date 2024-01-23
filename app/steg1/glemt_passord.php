<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Glemt Passord?</title>
<style>
    .error_msg {
        color: red;
    }
</style>
</head>
<body>

<h2>What's your e-mail?</h2>
<?php
if (isset($_SESSION["errorMessage"])) {
?>
    <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
<?php
    unset($_SESSION["errorMessage"]);
}
?>
<form action="glemt_passord-action.php" method="post">
    <label>E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>
    <input type="submit" value="submit" name="forgot_pass">
</form>

</body>
</html>