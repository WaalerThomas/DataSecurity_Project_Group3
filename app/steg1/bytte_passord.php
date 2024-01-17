<!DOCTYPE html>
<html>
<head>
<title>Bytte Passord</title>
</head>
<body>

<form action="?" method="post">
    <label>Gammelt Passord:</label>
    <input type="password" id="passord_old" name="passord_old" required><br><br>
    <label>Nytt Passord:</label>
    <input type="password" id="passord_new" name="passord_new" required><br><br>
    <label>Skriv Passord igjen:</label>
    <input type="password" id="rep_passord_new" name="rep_passord_new" required><br><br>
<input type="submit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") { 
    exit;
}
if($_POST["passord_new"] != $_POST["rep_passord_new"]) {
    echo "Passordene var ikke like...";
    exit;
}

echo "happy :)"; 
/* Note: validate old password */
/* Note: update new password */

?>

</body>
</html>




