<?php
// For fixing my permission denied problem when saving pictures: https://stackoverflow.com/questions/8103860/move-uploaded-file-gives-failed-to-open-stream-permission-denied-error

function checkProfilePictures() {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //$target_file = $target_dir . $_POST["email"] . "." . $imageFileType;
    $target_file = $target_dir . basename($_FILES['fileToUpload']['tmp_name']) . "." . $imageFileType;
    
    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check == false) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Filen er ikke et bilde.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Beklager, filen eksisterer allerede.";
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Beklager, filen din er for stor.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION["errorMessage"] = $_SESSION["errorMessage"] . "Beklager, bare JPG, JPEG, & PNG filer er lovlig.";
        $uploadOk = 0;
    }

    return array($uploadOk, $target_file);
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
    $profResult = array();
    if (! empty($_POST["registrer_foreleser"])) {
        $userType = "1";

        $profResult = checkProfilePictures();
        if ($profResult[0] == 0) {
            $_SESSION["errorMessage"] .= "Beklager, feilet under opplasting av filen. ";
        } else {
            // Removes the first dots in the filepath
            $_POST["profile_path"] = ltrim($profResult[1], '.');
        }
    }

    # If errors then send back to page with error messages
    if (! empty($_SESSION["errorMessage"])) {
        header("Location: ./?type=" . $userType);
        exit;
    }

    require_once __DIR__ . "/../dbClasses/DataSource.php";
    $ds = new DataSource();
    $ds->startTransaction();

    # Send request to database
    require_once __DIR__ . "/../dbClasses/User.php";
    $user = new User($ds);
    $isCreated = $user->createUser();
    if (! $isCreated) {
        $_SESSION["errorMessage"] = "Feilet under oppretting av bruker";
        $ds->rollbackTransaction();
        header("Location: ./?type=" . $userType);
        exit;
    }

    // Now create the subject if it is a lecturer
    if (! empty($_POST["registrer_foreleser"])) {
        require_once __DIR__ . "/../dbClasses/Course.php";
        $course = new Course($ds);
        $isCourseCreated = $course->createCourse($_SESSION["userId"]);
        if (! $isCourseCreated) {
            $_SESSION["errorMessage"] = "Feilet under oppretting av emne";
            $ds->rollbackTransaction();
            header("Location: ./?type=" . $userType);
            exit;
        }
    }

    // Save the profile picture now that the user is created
    if (! empty($profResult) && $profResult[0] == 1) {
        $target_file = $profResult[1];
        if (! move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $_SESSION["errorMessage"] .= "Beklager, feilet under opplasting av filen.";
            $ds->rollbackTransaction();
            header("Location: ./?type=" . $userType);
            exit;
        }
    }

    $ds->commitTransaction();
    
    header("Location: ../");
}
?>