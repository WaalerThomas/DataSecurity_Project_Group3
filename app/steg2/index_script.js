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

    var newUserParagraph = document.createElement("p");
    newUserParagraph.classList.add("user-comment");
    newUserParagraph.textContent = "Spørsmål";

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
    newCommentTopDiv.appendChild(newUserParagraph);
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

    var newUserParagraph = document.createElement("p");
    newUserParagraph.classList.add("user-answer");
    newUserParagraph.textContent = "Svar";

    var newAnswerParagraph = document.createElement("p");
    newAnswerParagraph.classList.add("answer");
    newAnswerParagraph.textContent = newAnswer;

    newCommentInfoDiv.appendChild(newUserParagraph);
    newCommentInfoDiv.appendChild(newAnswerParagraph);

    newAnswerDiv.appendChild(newCommentInfoDiv);

    commentDiv.insertBefore(newAnswerDiv, commentDiv.querySelector(".answer-textbox"));
}