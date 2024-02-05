<?php
class Course 
{
    private $dbConn;
    private $ds;

    function __construct() {
        require_once __DIR__ . "/DataSource.php";
        $this->ds = new DataSource();
    }

    function getCourseByName($name) {
        $query = "SELECT * FROM courses WHERE name = ?";
        $paramType = "s";
        $paramArray = array(
            $name
        );
        $courseResult = $this->ds->select($query, $paramType, $paramArray);
        return $courseResult;
    }

    function createCourse($userId) {
        # Check if the course already exists
        $courseResult = $this->getCourseByName($_POST["emnekode"]);
        if (! empty($courseResult)) {
            return;
        }

        $query = "INSERT INTO courses (name, pin, users_iduser)
        VALUES (?, ?, ?)";
        $paramType = "sii";
        $paramArray = array(
            $_POST["emnekode"],
            $_POST["emnepin"],
            $userId
        );
        $courseResult = $this->ds->insert($query, $paramType, $paramArray);
        return $courseResult;
    }
}
?>