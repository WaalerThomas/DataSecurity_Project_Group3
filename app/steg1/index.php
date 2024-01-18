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
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet pretium urna. Vivamus venenatis velit nec neque ultricies, eget elementum magna tristique. Quisque vehicula, risus eget aliquam placerat, purus leo tincidunt eros, eget luctus quam orci in velit. Praesent scelerisque tortor sed accumsan convallis.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet pretium urna. Vivamus venenatis velit nec neque ultricies, eget elementum magna tristique. Quisque vehicula, risus eget aliquam placerat, purus leo tincidunt eros, eget luctus quam orci in velit. Praesent scelerisque tortor sed accumsan convallis.</p>
  </div>
  
  <div class="column side">
    <h2>Emnesøk</h2>
    <form action="?" method="post">
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

</body>
</html>