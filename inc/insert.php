<?php
require_once 'connect_to_db.php';

// Create a session or resume the current one
session_start();

// If coverage is set, make the query to insert data
if (isset($_POST['coverage'])) {
    $username = $_SESSION['name'];
    $coverage = mysqli_real_escape_string($con, $_POST['coverage']);
    $coverage_name = mysqli_real_escape_string($con, $_POST['coverage_name']);
    $project_help = mysqli_real_escape_string($con, $_POST['project_help']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $hours = mysqli_real_escape_string($con, $_POST['hours']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']);
    $insert_wh = "INSERT INTO work_hours(username, coverage, coverage_name, project_help, wh_date, department, hours, comments) VALUES('$username', '$coverage', '$coverage_name', '$project_help', '$date', '$department', '$hours', '$comments')";
    if (mysqli_query($con, $insert_wh)) {
        echo "Work hours inserted!";
    }
}
