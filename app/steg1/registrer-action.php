<?php
if (! empty($_POST["registrer_student"]) || !empty($_POST["registrer_foreleser"])) {
    session_start();

    $_SESSION["errorMessage"] = "";

    # Check if first- and last name are valid
    if (! preg_match("/^[a-zA-Z-' æøåÆØÅ]*$/", $_POST["first_name"])) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Fornavn kan bare inneholde bokstaver fra a-å, -, ', og mellomrom. ";
    }
    if (! preg_match("/^[a-zA-Z-' æøåÆØÅ]*$/", $_POST["last_name"])) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Etternavn kan bare inneholde bokstaver fra a-å, -, ', og mellomrom. ";
    }

    # Check if valid email
    if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Ugyldig e-postadresse oppgitt. ";
    }

    # Check if the passwords don't match
    if ($_POST["password"] != $_POST["rep_password"]) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Passordene er ikke like. ";
    }

    # If errors then send back to page with error messages
    if (! empty($_SESSION["errorMessage"])) {
        header("Location: registrer.php");
        exit;
    }

    require_once __DIR__ . "/class/User.php";
    $user = new User();
    $isCreated = $user->createUser();
    if (! $isCreated) {
        $_SESSION["errorMessage"] = "Feilet under oppretting av bruker";
        header("Location: registrer.php");
        exit;
    }
    header("Location: ./");
}
?>