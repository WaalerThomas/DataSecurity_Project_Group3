<?php
// Start the session to be able to access $_SESSION.
session_start();

if (! isset($_GET["type"])) {
    header("Location: ./?type=0");
    exit;
} else if (! ($_GET["type"] == 0 || $_GET["type"] == 1)) {
    header("Location: ./?type=0");
    exit;
}

// Generate CSRF token
$_SESSION['CSRF_token'] = bin2hex(random_bytes(35));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrering</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="reg_form">
    <h1 class="header-text">Registrer</h1>

    <?php
    if (isset($_SESSION["errorMessage"]) && !empty($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>

    <div class="tab">
        <a <?php if ($_GET["type"] == "0") { echo 'class="active"'; } ?> rel="noopener noreferrer" target="_top" href="?type=0">Student</a>
        <a <?php if ($_GET["type"] == "1") { echo 'class="active"'; } ?> rel="noopener noreferrer" target="_top" href="?type=1">Foreleser</a>
    </div>

    <?php
    if (isset($_GET["type"]) && $_GET["type"] == "0") {
    ?>
        <div id="student" class="tabcontent">
            <form action="action.php" method="post" class="customForm">
                <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                <label>Fornavn:</label>
                <input type="text" id="first_name" name="first_name" required><br><br>
                <label>Etternavn:</label>
                <input type="text" id="last_name" name="last_name" required><br><br>
                <label>E-post:</label>
                <input type="email" id="email" name="email" required><br><br>
                <label>Passord:</label>
                <input type="password" id="password" name="password" required><br><br>
                <label>Skriv Passord igjen:</label>
                <input type="password" id="rep_password" name="rep_password" required><br><br>
                <input type="submit" value="Submit" name="registrer_student">
            </form>
        </div>
    <?php
    } else if (isset($_GET["type"]) && $_GET["type"] == "1") {
    ?>
        <div id="foreleser" class="tabcontent">
            <form action="action.php" method="post" class="customForm" enctype="multipart/form-data">
                <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                <label>Fornavn:</label>
                <input type="text" id="first_name" name="first_name" required><br><br>
                <label>Etternavn:</label>
                <input type="text" id="last_name" name="last_name" required><br><br>
                <label>E-post:</label>
                <input type="email" id="email" name="email" required><br><br>
                <label>Passord:</label>
                <input type="password" id="password" name="password" required><br><br>
                <label>Skriv Passord igjen:</label>
                <input type="password" id="rep_password" name="rep_password" required><br><br>
                <label>Profilbilde: </label>
                <input type="file" name="fileToUpload" id="fileToUpload" required><br><br>
                <p>Undervisningsemne</p>
                <label>Emnekode:</label>
                <input type="text" id="emnekode" name="emnekode" required><br><br>
                <label>Emnepin:</label>
                <input type="number" step="1" id="emnepin" name="emnepin" name="pin" min="0" max="9999" required><br><br>
                <input type="submit" value="Submit" name="registrer_foreleser">
            </form>
        </div>
    <?php
    }
    ?>
    
    <script src="script.js"></script>
</div>

</body>
</html>