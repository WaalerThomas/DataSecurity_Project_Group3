<?php
define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// Include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";

// NOTE: Do we need the imports below as a part of the bootstrap?

// Include the base controller file
require_once PROJECT_ROOT_PATH . "/controller/api/BaseController.php";

// Include the user model file
require_once PROJECT_ROOT_PATH . "/model/UserModel.php";
?>
