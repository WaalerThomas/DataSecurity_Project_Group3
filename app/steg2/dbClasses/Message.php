<?php
class Message
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

    function getAllCourseMessages($name) {
        $query = 'SELECT * FROM `messages`
        WHERE `courses_idcourses` = (
            SELECT `idcourses`
            FROM `courses`
            WHERE `name` = ?
        )';
        $paramType = "s";
        $paramArray = array(
            $name
        );
        $messageResult = $this->ds->select($query, $paramType, $paramArray);
        return $messageResult;
    }

    function getAllCourseMessagesInfo($name) {
        $query = 'SELECT idmessage, question, answer, courses_idcourses FROM `messages`
        WHERE `courses_idcourses` = (
            SELECT `idcourses`
            FROM `courses`
            WHERE `name` = ?
        )';
        $paramType = "s";
        $paramArray = array(
            $name
        );
        $messageResult = $this->ds->select($query, $paramType, $paramArray);
        return $messageResult;
    }

    function createMessage($message, $courseName, $userId) {
        require_once __DIR__ . "/Course.php";
        $course = new Course();
        $courseResult = $course->getCourseByName($courseName);
        if (! $courseResult) {
            return;
        }

        $query = 'INSERT INTO messages (question, courses_idcourses, users_iduser)
        VALUES (?, ?, ?)';
        $paramType = "sii";
        $paramArray = array(
            $message,
            $courseResult[0]['idcourses'],
            $userId
        );
        $messageResult = $this->ds->insert($query, $paramType, $paramArray);
        return $messageResult;
    }

    function addAnswer($msgId, $answer) {
        $query = 'UPDATE messages SET answer = ? WHERE idmessages = ?';
        $paramType = "si";
        $paramArray = array(
            $answer,
            $msgId
        );
        $messageResult = $this->ds->execute($query, $paramType, $paramArray);
    }

    function getAllComments($id) {
        $query = 'SELECT * FROM comments WHERE messages_idmessages = ?';
        $paramType = "i";
        $paramArray = array(
            $id
        );
        $commentResult = $this->ds->select($query, $paramType, $paramArray);
        return $commentResult;
    }

    function createComment($comment, $msgId) {
        $query = 'INSERT INTO comments (comment, messages_idmessages)
        VALUES (?, ?)';
        $paramType = "si";
        $paramArray = array(
            $comment,
            $msgId
        );
        $commentResult = $this->ds->insert($query, $paramType, $paramArray);
        return $commentResult;
    }

    function reportMessage($msgId) {
        $query = 'INSERT INTO reports (messages_idmessages)
        VALUES (?)';
        $paramType = "i";
        $paramArray = array(
            $msgId
        );
        $reportResult = $this->ds->insert($query, $paramType, $paramArray);
        return $reportResult;
    }
}
?>