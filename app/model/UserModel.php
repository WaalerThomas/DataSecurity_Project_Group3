<?php
require_once PROJECT_ROOT_PATH . "/model/Database.php";

class UserModel extends Database
{
    public function getUsers($limit) {
        return $this->select("SELECT * FROM Persons ORDER BY PersonID ASC LIMIT ?", ["i", $limit]);
    }
}
