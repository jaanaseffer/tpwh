<?php
require_once 'connect_to_db.php';

// Array of column header
$columns = array('username');

$query = "SELECT * FROM users "; // Query for employee table

if(isset($_POST["search"]["value"])) // When search value is set, add search string into query
{
    $query .= '
 WHERE username LIKE "%'.$_POST["search"]["value"].'%" 
 ';
}

if(isset($_POST["order"])) // If sort is set/active/clicked add sort into query
{
    $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else // If sort is not set, order table by id in descending order
{
    $query .= 'ORDER BY id DESC ';
}

$query1 = ''; // Setting next query

// DataTable feature, if length is not -1 limit query with selected value: 10, 25, 50 or 100
if($_POST["length"] != -1)
{
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

// Get filtered row number
$number_filter_row = mysqli_num_rows(mysqli_query($con, $query));

// Get query with limit options
$result = mysqli_query($con, $query . $query1);

$data = array(); // Set data variable to array

while($row = mysqli_fetch_array($result)) // Loop through the query
{
    $sub_array = array();
    $sub_array[] = '<div class="employees" contenteditable data-id="'.$row["id"].'" data-column="username">' . $row["username"] . '</div>';
    $sub_array[] = '<div data-id="'.$row["id"].'" data-column="password"><i class="fas fa-user-lock"></i></div>';
    $sub_array[] = '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Delete employee"><button type="button" name="delete_employee" class="delete_employee" id="'.$row["id"].'"><i class="far fa-trash-alt"></i></button></div>';
    $data[] = $sub_array; // Set data variable value to subarray value
}

// Function to get all data from work_hours table
function get_all_data($con)
{
    $query = "SELECT * FROM users";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result);
}

// Output array, draws the table, gets all data and filtered data
$output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data($con),
    "recordsFiltered" => $number_filter_row,
    "data"    => $data
);

echo json_encode($output); //Return JSON representation of a value
