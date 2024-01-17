<!DOCTYPE html>
<html>
<head>
<title>Glemt Passord?</title>
</head>
<body>

<h2>What's your e-mail?</h2>
<form action="?" method="post"><?php /* class="emneinput"*/?>
    <label>E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>
<input type="submit" value="Submit">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") { 
    exit;
}

/* Note: Check that a used with that mail exists */
/* Note: Send mail to user with token link thingy */
?>


</body>
</html>