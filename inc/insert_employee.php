<?php
require_once 'connect_to_db.php';

// Create a session or resume the current one
session_start();

$username = $_SESSION['name'];

// Insert users into database
//https://www.w3jar.com/php-code-for-login-registration-page/
if (isset($_POST['new_username']) && isset($_POST['new_password'])) {

    // Escape special characters
    $new_username = mysqli_real_escape_string($con, htmlspecialchars($_POST['new_username']));

    // Check if username already exists in database
    $check_username = mysqli_query($con, "SELECT username FROM users WHERE username = '$new_username'");
    if (mysqli_num_rows($check_username) > 0) {
        echo "Username is already in use.";
    } else {
        $new_password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Insert new employee/user into database
        $insert_new_user = mysqli_query($con, "INSERT INTO users (username, password) VALUES ('$new_username', '$new_password_hash')");

        if ($insert_new_user === true) {
            echo "New employee added successfully.";
        } else {
            echo "Something went wrong.";
        }
    }
}
