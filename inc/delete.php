<?php
require_once 'connect_to_db.php'; // Require connection file

// If id is set, delete the row to which this id belongs to
if(isset($_POST["id"]))
{
    // Delete query based on id
    $query = "DELETE FROM work_hours WHERE id = '".$_POST["id"]."'";

    // If query/deleting is successful display success message
    if(mysqli_query($con, $query))
    {
        echo 'Work hours deleted!';
    }

    // If query/deleting is not successful, display error message
    else {
        echo "Something went wrong!";
    }
}
