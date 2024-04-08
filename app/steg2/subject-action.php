<?php
/* Note: Validate "emnekode" with pin and so on */
/* Note: generate the said "emne" in "column middle" */

session_start();
require_once __DIR__ . "/dbClasses/Course.php";

// Check the CSRF token
$token = filter_input(INPUT_POST, 'authenticity_token', FILTER_SANITIZE_STRING);
if (! $token || $token !== $_SESSION['CSRF_token']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

if (! empty($_POST["emnekode"]) && ! empty($_POST["pin"])) {
    // Sanitize user input
    $subjectCode = filter_var($_POST["emnekode"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $pinCode = filter_var($_POST["pin"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if (! filter_var($pinCode, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) {
        $_SESSION["errorMessage"] = "PIN er ikke et nummer";
        header("Location: ./");
        exit;
    }
    
    # Check if the pin matches the course
    $course = new Course();
    $courseResult = $course->isPinValid($subjectCode, $pinCode);
    
    if ($courseResult == 1) {
        $hash = md5($pinCode . "emneCourseSaltyBabeThingy" . $subjectCode);
        header("Location: ./?course=".$subjectCode."&hash=".$hash);
        /*Need to add OPEN COURSE */
        exit;
    }
    else {
        $_SESSION["errorMessage"] = "Feil pin eller emnekode";
        header("Location: ./");
        exit;
    }

}
?>