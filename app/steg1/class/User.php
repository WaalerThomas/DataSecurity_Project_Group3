<?php
class User
{
    private $dbConn;
    private $ds;

    function __construct() {
        require_once __DIR__ . "/DataSource.php";
        $this->ds = new DataSource();
    }

    function getUserById($memberId) {
        $query = "SELECT * FROM users WHERE id = ?";
        $paramType = "i";
        $paramArray = array(
            $memberId
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);

        return $userResult;
    }

    function processLogin($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $paramType = "s";
        $paramArray = array(
            $email
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);
        return $userResult;
    }
    
    function loginUser() {
        $userResult = $this->processLogin($_POST["email"]);
        $isLoginPassword = 0;
        if (! empty($userResult)) {
            $password = $_POST["password"];
            $hashedPassword = $userResult[0]["password"];
            if (password_verify($password, $hashedPassword)) {
                $isLoginPassword = 1;
            }
        }

        if ($isLoginPassword == 1) {
            $_SESSION["userId"] = $userResult[0]["id"];
            return $userResult;
        }
    }
}
?>