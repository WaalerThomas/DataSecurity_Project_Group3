<?php
class PasswordToken
{
    private $dbConn;
    private $ds;

    function __construct() {
        require_once __DIR__ . "/DataSource.php";
        $this->ds = new DataSource();
    }

    function createPasswordResetToken($email, $key, $expDate) {
        $query = "INSERT INTO `password_reset_tmp` (`email`, `key`, `expDate`)
        VALUES (?, ?, ?)";
        $paramType = "sss";
        $paramArray = array(
            $email,
            $key,
            $expDate
        );
        $userResult = $this->ds->insert($query, $paramType, $paramArray);
        return $userResult;
    }
}
?>