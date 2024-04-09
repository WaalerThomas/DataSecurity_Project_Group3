<?php
class User
{
    private $dbConn;
    private $ds;

    function __construct($ds = NULL) {
        if ($ds == NULL) {
            require_once __DIR__ . "/DataSource.php";
            $this->ds = new DataSource();
        } else {
            $this->ds = $ds;
        }
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
    
    function loginUser($email, $password) {
        $userResult = $this->getUserByEmail($email);
        $isLoginPassword = 0;
        if (! empty($userResult)) {
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

    function registerAttempt($ip) {
        $query = "INSERT INTO `ip`(`address`, `timestamp`) VALUES(?, CURRENT_TIMESTAMP);";
        $paramType = "s";
        $paramArray = array(
            $ip
        );
        $userResult = $this->ds->insert($query, $paramType, $paramArray);
    }

    function getAttempts($ip) {
        $query = "SELECT COUNT(*) AS 'Count' FROM `ip` WHERE `address` LIKE ? AND `timestamp` > (now() - interval 10 minute);";
        $paramType = "s";
        $paramArray = array(
            $ip
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);
        return $userResult;
    }

    function cleanOldAttempts() {
        $query = "DELETE FROM `ip` WHERE `timestamp` < NOW() - 600";
        $paramType = "";
        $paramArray = array();
        $this->ds->execute($query, $paramType, $paramArray);
    }

    function updateUserPassword($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE `users` SET `password` = ? WHERE `email` = ?";
        $paramType = "ss";
        $paramArray = array(
            $hashedPassword,
            $email
        );
        $userResult = $this->ds->execute($query, $paramType, $paramArray);
    }

    function passCheck($id, $password) {
        $userResult = $this->getUserById($id);
        $hashedPassword = $userResult[0]["password"];
        $isLoginPassword = 0;
        if (password_verify($password, $hashedPassword)) {
            $isLoginPassword = 1;
        }

        if ($isLoginPassword == 1) {
            return TRUE;
        }
        return FALSE;
    }

    function getEmailById($id) {
        $query = "SELECT email FROM users WHERE iduser = ?";
        $paramType = "i";
        $paramArray = array(
            $id
        );
        $userResult = $this->ds->select($query, $paramType, $paramArray);

        return $userResult;
    }
}
?>