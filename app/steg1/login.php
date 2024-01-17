<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>


<form action="?" method="post"><?php /* class="emneinput"*/?>
    <label>E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label>Passord:</label>
    <input type="password" id="passord" name="passord" required><br><br>
<input type="submit" value="Submit">
</form>
<br>
<a href="glemt_passord.php">Glemt passord?</a>

<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") { 
    exit;
}

/* Note: validate user through database */
/* Note: redirect to student/lecturer style main page */
?>




</body>
</html>