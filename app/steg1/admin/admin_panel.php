<?php
// Start the session to be able to access $_SESSION.
session_start();

// Check if they are logged in or not
if (empty($_SESSION["userId"])) {
    header("Location: ../");
    exit;
}

require_once __DIR__ . "/class/User.php";
$user = new User();
$userResult = $user->getUserById($_SESSION["userId"]);
if (! $userResult) {
    unset($_SESSION["userId"]);
    header("Location: ../");
    exit;
}

// Check that the user logged in is an admin
$userTypeResult = $user->getUserTypeById($userResult[0]["user_type_iduser_type"]);
if ($userTypeResult[0]["name"] != "admin") {
    header("Location: ./");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="logout.php">Logg ut</a>
</body>
</html>