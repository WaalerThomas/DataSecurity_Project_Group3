<!DOCTYPE html>
<html>
<head>
<title>Registrering</title>
</head>
<body>

<form action="?" method="post"><?php /* class="emneinput"*/?>
    <label>Fullt navn:</label>
    <input type="text" id="navn" name="navn" required><br><br>
    <label>E-post:</label>
    <input type="email" id="epost" name="epost" required><br><br>
    <label>Passord:</label>
    <input type="password" id="passord" name="passord" required><br><br>
    <label>Skriv Passord igjen:</label>
    <input type="password" id="rep_passord" name="rep_passord" required><br><br>
<input type="submit" value="Submit">
</form>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST["passord"] == $_POST["rep_passord"]) {
        echo "happy :)"; 
        /* Note: Insert database query here*/ 
    }
    else
        echo "Passordene var ikke like...";
}

?>

</body>
</html>