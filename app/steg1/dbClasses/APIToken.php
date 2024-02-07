<?php
class APIToken
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

    function getTokenByAPIKey($api_key) {
        $query = 'SELECT * FROM api_keys WHERE api_key = ?';
        $paramType = "s";
        $paramArray = array(
            $api_key
        );
        $tokenResult = $this->ds->select($query, $paramType, $paramArray);
        return $tokenResult;
    }

    function getSessionAccountId($session_id) {
        $query = 'SELECT * FROM `api_sessions` WHERE `session_id` = ?';
        $paramType = "s";
        $paramArray = array(
            $session_id
        );
        $tokenResult = $this->ds->select($query, $paramType, $paramArray);
        return $tokenResult;
    }

    function createToken($api_key, $auth_key, $expDate) {
        $query = 'INSERT INTO api_keys (api_key, auth_key, expDate)
        VALUES (?, ?, ?)';
        $paramType = "sss";
        $paramArray = array(
            $api_key,
            $auth_key,
            $expDate
        );
        $tokenResult = $this->ds->insert($query, $paramType, $paramArray);
        return $this->getTokenByAPIKey($api_key);
    }

    function createAPISession($session_id, $userId) {
        $query = 'INSERT INTO `api_sessions` (`session_id`, `users_iduser`)
        VALUES (?, ?)';
        $paramType = "si";
        $paramArray = array(
            $session_id,
            $userId
        );
        $tokenResult = $this->ds->insert($query, $paramType, $paramArray);
        return $tokenResult;
    }
}
?>