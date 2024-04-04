<?php
// Start the session to be able to access $_SESSION.
session_start();

$displayName = "";
$userType = "";

// Generate CSRF token
$_SESSION['CSRF_token'] = bin2hex(random_bytes(35));

// Populate "global" variables if someone is logged in
if (! empty($_SESSION["userId"])) {
    require_once __DIR__ . "/dbClasses/User.php";
    $user = new User();
    $userResult = $user->getUserById($_SESSION["userId"]);
    if (! $userResult) {
        unset($_SESSION["userId"]);
        header("Location: ./");
        exit;
    }

    $displayName = $userResult[0]["first_name"];

    $userTypeResult = $user->getUserTypeById($userResult[0]["user_type_iduser_type"]);
    if ($userTypeResult[0]["name"] == "admin") {
        // Redirect to admin panel
        header("Location: ./admin");
        exit;
    } else if ($userTypeResult[0]["name"] == "student") {
        $userType = "Student";
    } else if ($userTypeResult[0]["name"] == "lecturer") {
        $userType = "Foreleser";
    } else {
        $userType = "N/A";
    }
}

if (isset($_GET['hash'])) {
    $_SESSION['access_hash'] = $_GET['hash'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <?php
    if (! empty($displayName) && !empty($userType)) {
    ?>
        <span><?php echo $displayName . " - " . $userType; ?></span>
        <a href="logout.php" target="_top">Logg ut</a>
        <a href="byttePassord/" target="_top">Bytte passord</a>
    <?php
    } else {
    ?>
        <a href="registrerBruker/" target="_top">Registrer</a>
        <a href="login/" target="_top">Logg inn</a>
    <?php
    }
    ?>
</div>

<div class="row">
    <div class="column middle">
        <?php
        // Display error produced from the action script on the form
        if (isset($_SESSION["errorMessage"])) {
        ?>
            <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
        <?php
            unset($_SESSION["errorMessage"]);
        }

        if (isset($_SESSION['status_message'])) {
            ?>
            <div class="status_msg"><?php echo $_SESSION['status_message']; ?></div>
            <?php
            unset($_SESSION['status_message']);
        }
        ?>

        <?php
        $canDisplayCourse = 0;
        $courseData = null;

        // Check for logged inn student
        if (isset($_SESSION['userId']) && isset($_GET['course']) && $userType == "Student") {
            require_once __DIR__ . "/dbClasses/Course.php";
            $course = new Course();
            $courseResult = $course->getCourseByName($_GET['course']);
            if (! $courseResult) {
                echo '<div class="error_msg">Ugyldig emnenavn oppgitt</div>';
            } else {
                $courseData = $courseResult;
                $canDisplayCourse = 1;
            }
        }

        // Guest user is requesting to see the course's messages
        if (! isset($_SESSION['userId']) && isset($_GET['course']) && isset($_GET['hash'])) {
            $courseName = $_GET['course'];
            $hash = $_GET['hash'];

            // Validate the hash
            $isValidHash = 0;

            require_once __DIR__ . "/dbClasses/Course.php";
            $course = new Course();
            $courseResult = $course->getCourseByName($courseName);
            if (! $courseResult) {
                echo '<div class="error_msg">Ugyldig emnenavn oppgitt</div>';
            }
            else {
                $trueHash = md5($courseResult[0]['pin'] . "emneCourseSaltyBabeThingy" . $courseName);
                if ($hash == $trueHash) {
                    $canDisplayCourse = 1;
                    $courseData = $courseResult;
                } else {
                    echo '<div class="error_msg">Hashen samsvarer ikke</div>';
                }
            }
        }

        // Check for lecturer
        if (isset($_SESSION['userId']) && $userType == "Foreleser") {
            require_once __DIR__ . "/dbClasses/Course.php";
            $course = new Course();
            $courseResult = $course->getCourseByUserId($_SESSION['userId']);
            if (! $courseResult) {
                echo '<div class="error_msg">Finner ingen emner som tilhører deg. Dette skal ikke være mulig :/</div>';
            } else {
                $courseData = $courseResult;
                $canDisplayCourse = 1;
            }
        }

        
        if ($courseData != null) {
            require_once __DIR__ . "/dbClasses/User.php";
            $lecturer = new User();
            $lectResult = $lecturer->getUserById($courseData[0]['users_iduser']);
            if (! $lectResult) {
                echo '<div class="error_msg">Feilet under henting av foreleser navn</div>';
            }

            ?>
            <section class="emneinfo">
                <h3>Emne: <?php echo $courseData[0]['name']; ?></h3>
            </section>
            <div class="info">

            <div id="commentsection">
            <?php
            // Generate the comment section
            require_once __DIR__ . "/dbClasses/Message.php";
            $msgObject = new Message();
            $msgResult = $msgObject->getAllCourseMessages($courseData[0]['name']);
            if (! $msgResult) {
                $msgResult = array();
            }
            $msg_index = 0;
            foreach ($msgResult as $msg) {
                ?>
                <div>
                    <div class="comment-info">
                        <div class="comment-top">
                            <p class="user-comment">Spørsmål</p>
                            <form action="report-action.php" method="post">
                                <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                                <input type="hidden" id="course_name" name="course_name" value<?php echo '="'.$courseData[0]['name'].'"' ?>></input>
                                <input type="hidden" id="msg_index" name="msg_index" value<?php echo '="'.$msg_index.'"' ?>></input>
                                <input type="submit" class="report-button" value="Rapporter" name="report_message">
                            </form>
                        </div>
                    </div>
                    <p class="comment"><?php echo $msg['question']; ?></p>
                    <?php
                    if ($msg['answer']) {
                        ?>
                        <div class="lecturer-answer">
                            <p class="user-answer">Foreleser</p>
                            <p class="answer"><?php echo $msg['answer']; ?></p>
                        </div>
                        <?php
                    }

                    // Get all the comments for this message
                    $commentResult = $msgObject->getAllComments($msg['idmessages']);
                    if (! $commentResult) {
                        $commentResult = array();
                    }
                    foreach ($commentResult as $com) {
                        ?>
                        <div class="comment-info">
                            <p class="user-answer">Svar</p>
                            <p class="answer"><?php echo $com['comment']; ?></p>
                        </div>
                        <?php
                    }

                    if ( ($userType == "Foreleser" && empty($msg['answer'])) || $userType != "Foreleser") {
                        ?>
                        <form action="message-action.php" method="post">
                            <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                            <input type="hidden" id="course_name" name="course_name" value<?php echo '="'.$courseData[0]['name'].'"' ?>></input>
                            <input type="hidden" id="msg_index" name="msg_index" value<?php echo '="'.$msg_index.'"' ?>></input>
                            <input class="answer-textbox" type="text" name="answer-textbox" id="answer-textbox" placeholder="Skriv et svar...">
                            <input type="submit" value="Send svar" name="send_comment">
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <?php
                $msg_index++;
            }
            ?>
            </div>

            <aside class="emneansvarlig">
                <?php  echo '<img width="100" height="100" src="./'.$lectResult[0]['picture'].'" />'; ?>
                <h3 class="teachername"><?php echo $lectResult[0]['first_name'] . " " . $lectResult[0]['last_name']; ?></h3>
            </aside>
            </div>
            <?php
            if ($userType == "Student") {
                ?>
                <div id="send-comment">
                    <form action="message-action.php" method="post">
                        <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                        <input type="hidden" id="course_name" name="course_name" value<?php echo '="'.$courseData[0]['name'].'"' ?>></input>
                        <input type="text" id="new-comment" name="new-comment" placeholder="Skriv en kommentar...">
                        <input type="submit" value="Send" name="send_message">
                    </form>
                </div>
                <?php
            }
        } else {
            echo '<div class="info_msg">Vennligst velg et emne ...</div>';
        }
        ?>
    </div>
  
    <div class="column side">
        <?php
        if ($userType == "Student") {
            ?>
            <label>Velg et emne:</label>
            <select id="courseChanger">
                <option value="">Velg ...</option>
                <?php
                // Get all courses
                require_once __DIR__ . "/dbClasses/Course.php";
                $course = new Course();
                $courseResult = $course->getAllCourses();
                if ($courseResult) {
                    foreach ($courseResult as $c) {
                        echo '<option value="' . $c["name"] . '">' . $c["name"] . '</option>';
                    }
                }
                ?>
            </select>
            <?php
        }
        else if (empty($_SESSION['userId'])) {
            ?>
            <h2>Emnesøk</h2>
            <form action="subject-action.php" method="post">
                <input type="hidden" name="authenticity_token" value="<?php echo $_SESSION['CSRF_token'] ?? '' ?>">
                <label>Emnekode:</label>
                <input type="text" id="emnekode" name="emnekode" required><br><br>
                <label>PIN-kode:</label>
                <input type="number" step="1" id="pin" name="pin" min="0" max="9999" required><br><br>
                <input type="submit" value="submit" name="subject-search">
            </form>
            <?php
        }
        ?>
    </div>
</div>

<script src="index_script.js"></script>

</body>
</html>