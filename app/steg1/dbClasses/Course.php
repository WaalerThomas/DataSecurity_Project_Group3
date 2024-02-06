<?php
class Course 
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

    function getCourseByName($name) {
        $query = "SELECT * FROM courses WHERE name = ?";
        $paramType = "s";
        $paramArray = array(
            $name
        );
        $courseResult = $this->ds->select($query, $paramType, $paramArray);
        return $courseResult;
    }

    function getAllCourses() {
        $query = "SELECT * FROM courses";
        $courseResult = $this->ds->select($query);
        return $courseResult;
    }

    function isPinValid($emnekodeResult, $pinResult) {
        $courseResult = 0;
        $emnekode = $this->getCourseByName($emnekodeResult);
        $realPin = $emnekode[0]["pin"];
        if ($pinResult == $realPin) {
            $courseResult = 1;
        }

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