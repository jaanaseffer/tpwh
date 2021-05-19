<?php
require_once "connect_to_db.php"; // Require connection file

// https://smarttutorials.net/jquery-autocomplete-multiple-fields-using-ajax-php-mysql-example/
// If field number is not empty, get value from fieldNo
$fieldNo = !empty($_GET['fieldNo']) ? $_GET['fieldNo'] : '';

// If coverage is not empty, get value from coverage input
$coverage = !empty($_GET['coverage']) ? trim($_GET['coverage']) : '';

// Assigning fieldNames
$fieldName = 'coverage';
switch ($fieldNo) {
    case 1:
        $fieldName = 'coverage_name';
        break;
    case 2:
        $fieldName = 'project_help';
        break;
}
$data = array();

// If coverage input is not empty make a query to database and get data back
if (!empty($_GET['coverage'])) {
    $coverage = trim($_GET['coverage']);
    $sql = "SELECT coverage, coverage_name, project_help FROM work_hours WHERE $fieldName LIKE '".$coverage."%'";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $coverage = $row['coverage'] . '|' . $row['coverage_name'] . '|' . $row['project_help'];
        array_push($data, $coverage);
    }
}
echo json_encode($data);
exit;
