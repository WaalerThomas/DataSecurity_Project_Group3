<?php
/* Note: Validate "emnekode" with pin and so on */
/* Note: generate the said "emne" in "column middle" */

session_start();
require_once __DIR__ . "/dbClasses/Course.php";

if (! empty($_POST["emnekode"]) && ! empty($_POST["pin"])) {
    # Check if the pin matches the course
    $course = new Course();
    $courseResult = $course->isPinValid($_POST["emnekode"], $_POST["pin"]);
    
    if ($courseResult == 1) {
        $_SESSION["errorMessage"] = "Nice, message redirect has to be implemented";
        $hash = md5($_POST['pin'] . "emneCourseSaltyBabeThingy" . $_POST['emnekode']);
        header("Location: ./?course=".$_POST['emnekode']."&hash=".$hash);
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