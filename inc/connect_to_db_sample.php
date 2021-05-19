<?php
//https://codeshack.io/secure-login-system-php-mysql/

// Create a session or resume the current one
session_start();

// Database connection info
$database_user = '';
$database_pass = '';
$dbname = '';
$host = '';

// 3WSchool & https://www.mitrajit.com/php-login-pdo-connection/
// Create connection
$con = mysqli_connect($host, $database_user, $database_pass, $dbname);
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
