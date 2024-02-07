<?php
// Start the session to be able to access $_SESSION.
session_start();

$displayName = "";
$userType = "";

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
</head>
<body>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
    }

    .header {
        overflow: hidden;
        background-color: #333;
    }

    .header a {
        float: right;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .header a:hover {
        background-color: #ddd;
        color: black;
    }

    .header span {
        color: white;
        display: block;
        float: left;
        text-align: center;
        padding: 14px 16px;
    }

    .column {
        float: left;
        padding: 10px;
    }
    .column.side {
        width: 25%;
    }
    .column.middle {
        width: 75%;
    }
    .column.full {
        width: 100%;
    }

    .row::after {
        content: "";
        display: table;
        clear: both;
    }

    .emneinput {
        position: absolute;
        top: 80%;
    }

    #commentsection{
        border: 1px solid black;
        width: 70%;
    }
    .commentsection{
        border: 1px solid black;
        width: 70%;
    }

    .username-comment{
        font-weight: bold; 
        color: grey;
        margin-left: 1rem;
        border-bottom: 1px solid black;
        width: 100px;
    }

    .report-button {
        margin-left: 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        height: 1rem;
    }

    .username-answer{
        font-weight: bold; 
        color: black;
        margin-left: 2.5rem;
        border-bottom: 1px solid black;
        width: 100px;
    }

    .comment{
        margin-left: 1rem;
        margin-top: 0;
    }

    #new-comment {
        flex: 1;
    }

    #send-comment{
        margin-top: .5rem;
    }

    .answer{
        margin-left: 2.5rem;
    }

    .emneinfo{
        display: flex;
        gap: 1rem;
    }

    .info{
        display: flex;
        gap: 10px;
    }

    .teachername{
        font-size: 15px;
        padding-top: .5rem;
        margin: 0;
    }

    .error_msg {
        color: red;
    }
    .info_msg {
        color: #7a7a7a;
    }
    .status_msg {
        color: #218c00;
    }

    .answer-textbox {
        margin-top: 4px;
        margin-left: 1rem; /* Adjust the margin as needed */
        padding: 6px;
        width: 70%;
    }

    .answer-button {
        margin-top: 4px;
        margin-left: 1rem; /* Adjust the margin as needed */
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .comment-top{
        display: flex;
        align-items: center;
    }

    .lecturer-answer {
        background: darkseagreen;
    }

</style>

<div class="header">
    <?php
    if (! empty($displayName) && !empty($userType)) {
    ?>
        <span><?php echo $displayName . " - " . $userType; ?></span>
        <a href="logout.php">Logg ut</a>
        <a href="byttePassord/">Bytte passord</a>
    <?php
    } else {
    ?>
        <a href="registrerBruker/" >Registrer</a>
        <a href="login/">Logg inn</a>
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
                            <p class="username-comment">Spørsmål</p>
                            <form action="report-action.php" method="post">
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
                            <p class="username-answer">Foreleser</p>
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
                            <p class="username-answer">Svar</p>
                            <p class="answer"><?php echo $com['comment']; ?></p>
                        </div>
                        <?php
                    }

                    if ( ($userType == "Foreleser" && empty($msg['answer'])) || $userType != "Foreleser") {
                        ?>
                        <form action="message-action.php" method="post">
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
            <select id="courseChanger" onchange="changeCourse()">
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

<script>
    var id = 0;

    function changeCourse() {
        var course = document.getElementById("courseChanger").value;
        if (course === "") {
            return;
        }

        window.location.href = "?course=" + course;
    }

    function handleButtonClick() {
        id++;
        var newComment = document.getElementById("new-comment").value;

        var newCommentDiv = document.createElement("div");
        newCommentDiv.classList.add(`comment-${id}`);

        var newCommentInfoDiv = document.createElement("div");
        newCommentInfoDiv.classList.add("comment-info");

        var newCommentTopDiv = document.createElement("div");
        newCommentTopDiv.classList.add("comment-top");

        var newUsernameParagraph = document.createElement("p");
        newUsernameParagraph.classList.add("username-comment");
        newUsernameParagraph.textContent = "Spørsmål";

        var reportButton = document.createElement("button");
        reportButton.classList.add("report-button");
        reportButton.textContent = "Rapporter";
        reportButton.onclick = function () {
            alert("Kommentar rapportert!");
        };

        var newCommentParagraph = document.createElement("p");
        newCommentParagraph.classList.add("comment");
        newCommentParagraph.textContent = newComment;

        var answerTextbox = document.createElement("input");
        answerTextbox.setAttribute("type", "text");
        answerTextbox.setAttribute("placeholder", "Skriv et svar...");
        answerTextbox.classList.add("answer-textbox");

        var answerButton = document.createElement("button");
        answerButton.classList.add("answer-button");
        answerButton.textContent = "Send svar";
        answerButton.onclick = function () {
            handleAnswerButtonClick(newCommentDiv);
        };

        newCommentInfoDiv.appendChild(newCommentTopDiv);
        newCommentTopDiv.appendChild(newUsernameParagraph);
        newCommentTopDiv.appendChild(reportButton);
        

        newCommentDiv.appendChild(newCommentInfoDiv);
        newCommentDiv.appendChild(newCommentParagraph);
        newCommentDiv.appendChild(answerTextbox);
        newCommentDiv.appendChild(answerButton);
        
        document.getElementById("commentsection").appendChild(newCommentDiv);
    }

function handleAnswerButtonClick(commentDiv) {
    var newAnswer = commentDiv.querySelector(".answer-textbox").value;

    var newAnswerDiv = document.createElement("div");
    newAnswerDiv.classList.add("answer-1");

    var newCommentInfoDiv = document.createElement("div");
    newCommentInfoDiv.classList.add("comment-info");

    var newUsernameParagraph = document.createElement("p");
    newUsernameParagraph.classList.add("username-answer");
    newUsernameParagraph.textContent = "Svar";

    var newAnswerParagraph = document.createElement("p");
    newAnswerParagraph.classList.add("answer");
    newAnswerParagraph.textContent = newAnswer;

    newCommentInfoDiv.appendChild(newUsernameParagraph);
    newCommentInfoDiv.appendChild(newAnswerParagraph);

    newAnswerDiv.appendChild(newCommentInfoDiv);

    commentDiv.insertBefore(newAnswerDiv, commentDiv.querySelector(".answer-textbox"));
}
</script>

</body>
</html>