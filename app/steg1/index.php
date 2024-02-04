<!DOCTYPE html>
<html>
<head>
<title>Main page</title>
</head>
<body>

<style>
.button {
  padding: 6px 18px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  margin: 4px 2px;
  cursor: pointer;
}

.button1 {  
  padding: 6px 18px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  margin: 4px 2px;
  cursor: pointer;
}

/*div {
  padding: 10px 40px;
  text-align: right;
  text-decoration: none;
  display: inline-block;
  margin: 4px 2px;
  cursor: pointer;
  position: absolute;
  left: 90%;
}*/

div1 {
  padding: 10px 40px;
  text-align: right;
  text-decoration: none;
  display: inline-block;
  /*margin: 4px 2px;*/
  cursor: pointer;
  position: absolute;
  left: 40%;
  top: 50%;
}

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

.username-comment {
  font-weight: bold;
  color: grey;
  margin-left: 1rem;
  border-bottom: 1px solid black;
  display: inline-block;
}

.report-button {
  margin-left: 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.username-answer{
  font-weight: bold; 
  color: black;
  margin-left: 2.5rem;
  border-bottom: 1px solid black;
  width: 100px;
}

.comment {
  margin-left: 1rem;
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
</style>

<div class="header">
  <a href="registrer.php" >Registrer</a>
  <a href="login.php">Logg inn</a>
</div>

<div class="row">
  <div class="column middle">
    <h2>Main Content</h2>
    <section class="emneinfo">
      <h3>Emnekode</h3>
      <h3>Emnenavn</h3>
    </section>
    <div class="info">
      <div id="commentsection">
        <div class="comment-1">
          <div class="comment-info">
            <p class="username-comment">username</p>
            <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
              eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
       </div>
        <div class="answer-1">
          <div class="comment-answer">
            <p class="username-answer">username</p>
            <p class="answer">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, 
              similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
          </div>
        </div>
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
    <?php 
        echo $_POST["pin"]
    ?>

    </div>
  
  <div class="column side">
    <label>Choose a course:</label>
      <select>
        <option value="dataingeniør">Ingeniørfag, Data</option>
        <option value="informasjonssystemer">Informasjonssystemer</option>
      </select>
    <h2>Emnesøk</h2>
    <?php /*<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit..</p>*/?>
    <form action="?" method="post"><?php /* class="emneinput"*/?>
        <?php /*<label >Emnekode input</label> <br> style="position:absolute; left: 40%;"*/?>
        <label>Emnekode:</label>
        <input type="text" id="emnekode" name="emnekode" required><br><br>
        <label>PIN-kode:</label>
        <input type="password" id="pin" name="pin" required><br><br>
    <input type="submit" value="Submit">
    </form>
  </div>
</div>

<?php 
/* Note: Validate "emnekode" with pin and so on */
/* Note: generate the said "emne" in "column middle" */

?>
<script>

  var id = 0;

  function handleButtonClick() {
    id++;
    var newComment = document.getElementById("new-comment").value;

    var newCommentDiv = document.createElement("div");
    newCommentDiv.classList.add(`comment-${id}`);

    var newCommentInfoDiv = document.createElement("div");
    newCommentInfoDiv.classList.add("comment-info");

    var newUsernameParagraph = document.createElement("p");
    newUsernameParagraph.classList.add("username-comment");
    newUsernameParagraph.textContent = "username";

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

    newCommentInfoDiv.appendChild(newUsernameParagraph);
    newCommentInfoDiv.appendChild(reportButton);

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
    newUsernameParagraph.textContent = "username";

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