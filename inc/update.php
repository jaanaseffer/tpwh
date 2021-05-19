<?php
require_once 'connect_to_db.php';

// If id is set, make updates in that row
if(isset($_POST["id"]))
{
    $value = mysqli_real_escape_string($con, $_POST["value"]);
    $query = "UPDATE work_hours SET ".$_POST["column_name"]."='".$value."' WHERE id = '".$_POST["id"]."'";
    if(mysqli_query($con, $query))
    {
        echo 'Work hours changed!';
    }
}
