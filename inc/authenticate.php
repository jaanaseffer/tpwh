<?php
//https://codeshack.io/secure-login-system-php-mysql/

// Create a session or resume the current one
session_start();

require_once 'connect_to_db.php';

// https://www.w3jar.com/php-code-for-login-registration-page/
// Checking if the data from login form was submitted
if(isset($_POST['username']) && isset($_POST['password'])){

    // Check if username and password are not empty
    if (!empty(trim($_POST['username'])) && !empty(trim($_POST['password']))) {

        // Escape special characters
        $username = mysqli_real_escape_string($con,htmlspecialchars(trim($_POST['username'])));

        // Making query, finding username from table
        $query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");

        // If number of rows in result is greater than 0
        if (mysqli_num_rows($query) > 0) {

            // Fetching row as array
            $row = mysqli_fetch_assoc($query);

            // Getting user password in database
            $user_password_in_database = $row['password'];

            // Verify password, checking entered password against password in database
            $check_password = password_verify($_POST['password'], $user_password_in_database);

            // If password check is successful
            if ($check_password === true) {
                session_regenerate_id(); // Updates current session id
                $_SESSION['loggedin'] = true; // Session variable, logged in or not
                $_SESSION['name'] = $_POST['username']; // Session name equals username (employee who logged in)
                $_SESSION['id'] = $id; // Session id

                // Checking if board member logged in or not, redirecting accordingly
                if ($_SESSION['name'] === 'rivo') {
                    header('Location: ../dashboard_boardmember.php');
                } else {
                    header('Location: ../dashboard.php');
                }
                exit;
            } else {

                // Incorrect password
                $login_error_message = "Password or username incorrect.";
            }
        } else {

            // Username not registered
            $login_error_message = "Password or username incorrect.";
        }
    }
}
