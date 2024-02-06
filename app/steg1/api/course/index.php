<?php
require_once __DIR__ . "/../../dbClasses/Course.php";

header("Content-Type: application/json; charset=UTF-8");

$course = new Course();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    /**
     * Enpoint - /course
     */
    if (! isset($_GET['name'])) {
        $courseResponse = $course->getAllCoursesInfo();
        $json_response = json_encode($courseResponse);
        echo $json_response;
        exit;
    }

    /**
     * Enpoint - /course?name={name}
     */
    if (! empty($_GET['name'])) {
        // TODO: Validate name from GET
        $courseResponse = $course->getCourseByNameInfo($_GET['name']);
        $json_response = json_encode($courseResponse[0]);
        echo $json_response;
        exit;
    }
}

header("HTTP/1.1 404 Not Found");
exit;
?>