<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Registrering</title>
<style>
    .reg_form {
        display: inline-block;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
    }

    .error_msg {
        color: red;
    }

    input[type=submit] {
        width: 100%;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }
    .tab button:hover {
        background-color: #ddd;
    }

    .tab button.active {
        background-color: #ccc;
    }

    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .customForm {
        display: grid;
        grid-template-columns: [labels] auto [controls] 1fr;
        grid-auto-flow: row;
        grid-gap: .8em;
    }
    .customForm > label  {
        grid-column: labels;
        grid-row: auto;
    }
    .customForm > input,
    .customForm > textarea {
        grid-column: controls;
        grid-row: auto;
    }
    .customForm > input[type=submit] {
        grid-column: labels / a;
        grid-row: auto;
    }
</style>
</head>
<body>

<div class="reg_form">
    <h1 style="text-align: center;">Registrer</h1>

    <?php
    if (isset($_SESSION["errorMessage"])) {
    ?>
        <div class="error_msg"><?php  echo $_SESSION["errorMessage"]; ?></div>
    <?php
        unset($_SESSION["errorMessage"]);
    }
    ?>

    <div class="tab">
        <button class="tablinks active" onclick="openTab(event, 'student')">Student</button>
        <button class="tablinks" onclick="openTab(event, 'foreleser')">Foreleser</button>
    </div>

    <div id="student" class="tabcontent" style="display: block">
        <form action="registrer-action.php" method="post" class="customForm">
            <label>Fornavn:</label>
            <input type="text" id="first_name" name="first_name" required><br><br>
            <label>Etternavn:</label>
            <input type="text" id="last_name" name="last_name" required><br><br>
            <label>E-post:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label>Passord:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label>Skriv Passord igjen:</label>
            <input type="password" id="rep_password" name="rep_password" required><br><br>
            <input type="submit" value="Submit" name="registrer_student">
        </form>
    </div>

    <div id="foreleser" class="tabcontent">
        <form action="registrer-action.php" method="post" class="customForm">
            <label>Fornavn:</label>
            <input type="text" id="first_name" name="first_name" required><br><br>
            <label>Etternavn:</label>
            <input type="text" id="last_name" name="last_name" required><br><br>
            <label>E-post:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label>Passord:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label>Skriv Passord igjen:</label>
            <input type="password" id="rep_password" name="rep_password" required><br><br>
            <label>Profilbilde: </label>
            <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
            <p>Undervisningsemne</p>
            <label>Emnekode:</label>
            <input type="text" id="emnekode" name="emnekode" required><br><br>
            <label>Emnepin:</label>
            <input type="text" id="emnepin" name="emnepin" required><br><br>
            <input type="submit" value="Submit" name="registrer_foreleser">
        </form>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;

            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</div>

</body>
</html>