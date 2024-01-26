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
        $query = "SELECT * FROM users WHERE iduser = ?";
        $paramType = "i";
        $paramArray = array(
            $memberId
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);

        return $userResult;
    }

    function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $paramType = "s";
        $paramArray = array(
            $email
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);
        return $userResult;
    }

    function getUserTypeByName($typeName) {
        $query = "SELECT * FROM user_type WHERE name = ?";
        $paramType = "s";
        $paramArray = array(
            $typeName
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);
        return $userResult;
    }

    function getUserTypeById($typeId) {
        $query = "SELECT * FROM user_type WHERE iduser_type = ?";
        $paramType = "i";
        $paramArray = array(
            $typeId
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);
        return $userResult;
    }
    
    function loginUser() {
        $userResult = $this->getUserByEmail($_POST["email"]);
        $isLoginPassword = 0;
        if (! empty($userResult)) {
            $password = $_POST["password"];
            $hashedPassword = $userResult[0]["password"];
            if (password_verify($password, $hashedPassword)) {
                $isLoginPassword = 1;
            }
        }

        if ($isLoginPassword == 1) {
            $_SESSION["userId"] = $userResult[0]["iduser"];
            return $userResult;
        }
    }

    function createUser() {
        # Check if a user has already registered the email
        $userResult = $this->getUserByEmail($_POST["email"]);
        if (! empty($userResult)) {
            return;
        }

        $hashedPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $userType = null;
        $picture = null;
        if (! empty($_POST["registrer_student"])) {
            $userType = $this->getUserTypeByName("student")[0]["iduser_type"];
        } elseif (! empty($_POST["registrer_foreleser"])) {
            $userType = $this->getUserTypeByName("lecturer")[0]["iduser_type"];
            $picture = $_POST["profile_path"];
        }

        $query = "INSERT INTO users (first_name, last_name, password, email, picture, user_type_iduser_type)
        VALUES (?, ?, ?, ?, ?, ?);";
        $paramType = "sssssi";
        $paramArray = array(
            $_POST["first_name"],
            $_POST["last_name"],
            $hashedPassword,
            $_POST["email"],
            $picture,
            $userType
        );
        $userResult = $this->ds->insert($query, $paramType, $paramArray);
        $_SESSION["userId"] = $userResult;
        return $userResult;
    }
}
?>