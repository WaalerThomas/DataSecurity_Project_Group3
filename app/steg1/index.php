<?php
// Start the session to be able to access $_SESSION.
session_start();
?>

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

.username-answer{
  font-weight: bold; 
  color: black;
  margin-left: 2.5rem;
  border-bottom: 1px solid black;
  width: 100px;
}

.comment{
  margin-left: 1rem;
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
</style>

<?php
$displayName = "";

if (! empty($_SESSION["userId"])) {
  require_once __DIR__ . "/class/User.php";
  $user = new User();
  $userResult = $user->getUserById($_SESSION["userId"]);

  $displayName = $userResult[0]["first_name"];
}
?>

<div class="header">
  <?php
  if (! empty($displayName)) {
  ?>
    <span><?php echo $displayName . " - Student|Foreleser"; ?></span>
    <a href="logout.php">Logg ut</a>
  <?php
  } else {
  ?>
    <a href="registrer.php" >Registrer</a>
    <a href="login.php">Logg inn</a>
  <?php
  }
  ?>
</div>

<div class="row">
  <div class="column middle">
    <h2>Main Content</h2>
    <section class="emneinfo">
      <h3>Emnekode</h3>
      <h3>Emnenavn</h3>
    </section>
    <div class="info">
      <div class="commentsection">
        <div class="comment-info">
          <p class="username-comment">username</p>
          <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
       </div>
       <div class="comment-answer">
           <p class="username-answer">username</p>
        <p class="answer">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, 
          similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
       </div>
      </div>
    
      <aside class="emneansvarlig">
        <img src="https://placehold.co/100x100.png"></img>
        <h3 class="teachername">Emneansvarlig</h3>
      </aside>
    </div>
  </div>
  
  <div class="column side">
    <h2>Emnesøk</h2>
    <form action="subject-action.php" method="post">
      <label>Emnekode:</label>
      <input type="text" id="emnekode" name="emnekode" required><br><br>
      <label>PIN-kode:</label>
      <input type="password" id="pin" name="pin" required><br><br>
      <input type="submit" value="Submit" name="subject-search">
    </form>
  </div>
</div>

<?php 
/* Note: Validate "emnekode" with pin and so on */
/* Note: generate the said "emne" in "column middle" */
?>

</body>
</html>