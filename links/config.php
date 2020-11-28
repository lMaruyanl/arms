<?php
error_reporting(0);
define('DB_NAME', 'heroku_04694c75558c49f');
define('DB_USER', 'b05b9648d0c6c0');
define('DB_PASSWORD', 'def94a3c');
define('DB_HOST', 'us-cdbr-east-02.cleardb.com');

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
