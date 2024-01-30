<?php
function save_profile_picture() {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $target_file = $target_dir . $_POST["email"] . "." . $imageFileType;
    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check == false) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Sorry, file already exists.";
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Sorry, only JPG, JPEG, & PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Sorry, there was an error uploading your file.";
        }
    }

    return null;
}

if (! empty($_POST["registrer_student"]) || !empty($_POST["registrer_foreleser"])) {
    session_start();

    $_SESSION["errorMessage"] = "";
    $userType = "0";

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

    # Do check for only the lecturer
    if (! empty($_POST["registrer_foreleser"])) {
        $userType = "1";

        $target_file = save_profile_picture();
        $_POST["profile_path"] = $target_file;
    }

    # If errors then send back to page with error messages
    if (! empty($_SESSION["errorMessage"])) {
        header("Location: registrer.php?type=" . $userType);
        exit;
    }

    # Send request to database
    require_once __DIR__ . "/class/User.php";
    $user = new User();
    $isCreated = $user->createUser();
    if (! $isCreated) {
        // It has failed, so need to remove the profile picture
        // TODO: Implement

        $_SESSION["errorMessage"] = "Feilet under oppretting av bruker";
        header("Location: registrer.php?type=" . $userType);
        exit;
    }

    // Now create the subject if it is a lecturer
    // TODO: Implement
    header("Location: ./");
}
?>