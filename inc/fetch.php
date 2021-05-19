<?php
require_once 'connect_to_db.php'; // Require connection file

// Create a session or resume the current one
session_start();
$username = $_SESSION['name']; // Setting username value

// Array of column headers
$columns = array('coverage', 'coverage_name', 'project_help', 'wh_date', 'department', 'hours', 'comments');

// Main query where username is employee who logged in
$query = "SELECT * FROM work_hours WHERE username = '$username'";

if(isset($_POST["search"]["value"])) // When search value is set, add search string into query
{
    $query .= '
 AND (wh_date LIKE "%'.$_POST["search"]["value"].'%" 
 OR coverage_name LIKE "%'.$_POST["search"]["value"].'%"
 OR coverage LIKE "%'.$_POST["search"]["value"].'%"
 OR project_help LIKE "%'.$_POST["search"]["value"].'%"
 OR department LIKE "%'.$_POST["search"]["value"].'%") 
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
    $sub_array = array(); // Set subarray variable to array and get all needed values from query
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="coverage">' . $row["coverage"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="coverage_name">' . $row["coverage_name"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="project_help">' . $row["project_help"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="date">' . $row["wh_date"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="department">' . $row["department"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="hours">' . $row["hours"] . '</div>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="comments">' . $row["comments"] . '</div>';
    $sub_array[] = '<button data-bs-toggle="tooltip" data-bs-placement="top" title="Deletes this row" type="button" name="delete" class="delete" id="'.$row["id"].'"><i class="far fa-trash-alt"></i></button>';
    $data[] = $sub_array; // Set data variable value to subarray value

}

// Function to get all data from work_hours table
function get_all_data($con)
{
    $query = "SELECT * FROM work_hours";
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
