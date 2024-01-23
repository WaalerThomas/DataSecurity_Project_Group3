<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Registrering</title>
<style>
    .reg_form {
        display: inline-block;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
    }

    .error_msg {
        color: red;
    }
</style>
</head>
<body>

<div class="reg_form">
    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }

    $a = '1';
    $b = '2';

    echo '<a href="registrer.php?type=' . $a . '">Student</a>';
    echo ' ';
    echo '<a href="registrer.php?type=' . $b . '">Foreleser</a>';


    if(isset($_GET['type'])) {
        if($_GET['type'] == '1') {
            echo '<form action="registrer-action.php" method="post">';
            echo '<label>Fullt navn:</label>';
            echo '<input type="text" id="full_name" name="full_name" required><br><br>';
            echo '<label>E-post:</label>';
            echo '<input type="email" id="email" name="email" required><br><br>';
            echo '<label>Passord:</label>';
            echo '<input type="password" id="password" name="password" required><br><br>';
            echo '<label>Skriv Passord igjen:</label>';
            echo '<input type="password" id="rep_password" name="rep_password" required><br><br>';
            echo '<input type="submit" value="Submit" name="registrer_student">';
            echo '</form>';
        }
        else {
            echo '<form action="registrer-action.php" method="post">';
            echo '<label>Fullt navn:</label>';
            echo '<input type="text" id="full_name" name="full_name" required><br><br>';
            echo '<label>E-post:</label>';
            echo '<input type="email" id="email" name="email" required><br><br>';
            echo '<label>Passord:</label>';
            echo '<input type="password" id="password" name="password" required><br><br>';
            echo '<label>Skriv Passord igjen:</label>';
            echo '<input type="password" id="rep_password" name="rep_password" required><br><br>';
            echo '<label>Emnekode:</label>';
            echo '<input type="text" id="emnekode" name="emnekode" required><br><br>';
            echo '<label>Emnepin:</label>';
            echo '<input type="text" id="emnepin" name="emnepin" required><br><br>';
            echo '<label>Profilbilde: </label>';
            echo '<input type="file" name="fileToUpload" id="fileToUpload">';
            echo '<br><br>';
            echo '<input type="submit" value="Submit" name="registrer_foreleser">';
            echo '</form>';
        }

    }
    ?>

    
</div>

</body>
</html>