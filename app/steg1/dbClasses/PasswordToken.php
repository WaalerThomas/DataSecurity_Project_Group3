<?php
class PasswordToken
{
    private $dbConn;
    private $ds;

    function __construct() {
        require_once __DIR__ . "/DataSource.php";
        $this->ds = new DataSource();
    }

    function getEntryFromKeyAndEmail($email, $key) {
        $query = "SELECT * FROM `password_reset_temp` WHERE `email` = ? AND `key` = ?";
        $paramType = "ss";
        $paramArray = array(
            $email,
            $key
        );
        $tokenResult = $this->ds->select($query, $paramType, $paramArray);
        return $tokenResult;
    }

    function createPasswordResetToken($email, $key, $expDate) {
        // TODO: Check first if there is an active token already
        
        $query = "INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
        VALUES (?, ?, ?)";
        $paramType = "sss";
        $paramArray = array(
            $email,
            $key,
            $expDate
        );
        $tokenResult = $this->ds->insert($query, $paramType, $paramArray);
        return $tokenResult;
    }

    function removeEntry($email) {
        $query = "DELETE FROM `password_reset_temp` WHERE email = ?";
        $paramType = "s";
        $paramArray = array(
            $email
        );
        $this->ds->execute($query, $paramType, $paramArray);
    }
}
?>