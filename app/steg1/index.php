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
    <section class="emneinfo">
      <h3>Emnekode</h3>
      <h3>Emnenavn</h3>
    </section>
    <div class="info">
      <div id="commentsection">
      </div>
      <aside class="emneansvarlig">
        <img src="https://placehold.co/100x100.png"></img>
        <h3 class="teachername">Emneansvarlig</h3>
      </aside>      
    </div>
    <div id="send-comment">
      <input type="text" id="new-comment" placeholder="Skriv en kommentar...">
      <button id="button" onclick="handleButtonClick()">Send</button> 
    </div>
  </div>
  
  <div class="column side">
    <label>Choose a course:</label>
      <select>
        <?php
        // Get all courses
        require_once __DIR__ . "/dbClasses/Course.php";
        $course = new Course();
        $courseResult = $course->getAllCourses();
        if ($courseResult) {
          foreach ($courseResult as $c) {
            echo '<option value="' . $c["idcourses"] . '">' . $c["name"] . '</option>';
          }
        }
        ?>
      </select>
    <h2>Emnesøk</h2>
    <?php
    // Display error produced from the action script on the form
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>
    <form action="subject-action.php" method="post">
      <label>Emnekode:</label>
      <input type="text" id="emnekode" name="emnekode" required><br><br>
      <label>PIN-kode:</label>
      <input type="number" step="1" id="pin" name="pin" min="0" max="9999" required><br><br>
      <input type="submit" value="submit" name="subject-search">
    </form>
  </div>
</div>

<script>

  var id = 0;

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