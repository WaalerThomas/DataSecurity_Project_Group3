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
</style>
</head>
<body>

<?php
if (isset($_SESSION["errorMessage"])) {
?>
    <div><?php  echo $_SESSION["errorMessage"]; ?></div>
<?php
    unset($_SESSION["errorMessage"]);
}

if ($_SERVER["REQUEST_METHOD"] != "POST") { 
}

/* Note: validate user through database */
/* Note: redirect to student/lecturer style main page */
?>

<div class="login_form">
    <form action="login-action.php" method="post">
        <label>E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label>Passord:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Submit">
    </form>

    <br>

    <a href="glemt_passord.php">Glemt passord?</a>
</div>





</body>
</html>