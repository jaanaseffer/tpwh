<?php
// Start sessions
session_start();
// If the user is not logged in redirect to the login page
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Terrapro workhours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" integrity="sha512-rxThY3LYIfYsVCWPCW9dB0k+e3RZB39f23ylUYTEuZMDrN/vRqLdaCBo/FbvVT6uC2r0ObfPzotsfKF9Qc5W5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-buttons-dt/1.7.0/buttons.dataTables.min.css" integrity="sha512-KzqwJFLNq+u+1P5Us3npfxbDCG/MuNL+N4TrXLakYJMOzA6fcNnlmtGZTbyIjN+e/RZ6J8PHkLsLAGim/dcc8Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.0.2/css/dataTables.dateTime.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/tpwh.css">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg fixed-top">
        <a class="navbar-brand" href="#"><img src="assets/img/logo.png" alt="Terrapro logo" class="logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav mr-4">
                <li class="nav-item">
                    <a class="nav-link" data-router-link='' data-value="enter_wh" href="">Work hours</a></li>
                <li class="nav-item">
                    <a class="nav-link" data-router-link='createABill' data-value="createABill" href="#createABill">Create
                        an invoice</a></li>
                <li class="nav-item">
                    <a class="nav-link" data-router-link='employees' data-value="employees"
                       href="#employees">Employees</a></li>
                <li class="nav-item">
                    <a class="nav-link" data-value="logout" href="/logout.php">Log out</a></li>
            </ul>
        </div>
    </nav>
    <h6>Welcome, <?= ucfirst($_SESSION['name']) ?>!</h6>
    <!-- Board member work hours table -->
    <section data-router-view="">
        <h6>View, insert, edit and delete work hours.</h6>
        <h5>Do not use Estonian special characters (ö, ä, ü, õ) and Norwegian special letters (å, ø, æ).</h5>
        <h4>To change an entry click on a cell, make the change and click somewhere else.</h4>
        <div>
            <div align="right">
                <button type="button" name="add" id="add_row" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Adds empty row to the table">Add row</button>
            </div>
            <div id="alert_message_bm"></div>
            <table id="board_member_work_hours" class="table">
                <thead>
                <tr>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Coverage No in PDB (e.g. 51234, 41234, 25123, 31123)">Coverage</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Coverage name in PDB (e.g. Oslo orthophoto)">Coverage name</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="ProHelp Project No in PDB (e.g. 10047)">Project help no</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Date in format yyyy-mm-dd">Date</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Choose which department this project belongs to">Department</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Half an hour precision. Use dot, not comma!">Hours</th>
                    <th data-bs-toggle="tooltip" data-bs-placement="top" title="Short description of your task(s).">Comments</th>
                    <th>Insert/Delete</th>
                </tr>
                </thead>
            </table>
        </div>
    </section>
    <!-- Invoice table -->
    <section data-router-view="createABill">
        <h6>Extract work hours for an invoice.</h6>
        <h4>First select dates and filter based on them, then you can use search and sort functions.</h4>
        <h4>Make sure that all rows are shown before exporting.</h4>
        <div class="justify-content-start billTableWrapper">
            <div class="row">
                <div class="col-3">
                    <label for="" class="control-label">From date:</label>
                    <input type="text" class="datepicker2" id="fromDate" name="fromDate">
                </div>
                <div class="col-3">
                    <label for="" class="control-label">To date:</label>
                    <input type="text" class="datepicker3" id="toDate" name="toDate">
                </div>
                <div class="col-2">
                    <button class="btn" type="submit" name="filter" id="filter">Filter
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
            <table class="table" id="billTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Coverage</th>
                        <th>Coverage name</th>
                        <th>Project help no</th>
                        <th>Date</th>
                        <th>Department</th>
                        <th>Hours</th>
                        <th>Comments</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    <!-- Employee management table -->
    <section data-router-view="employees">
        <h5>Do not use Estonian special characters (ö, ä, ü, õ).</h5>
        <h6>Delete and add employees</h6>
        <div id="employeeTableDiv" class="justify-content-center">
            <div align="right">
                <button type="button" name="add_employee" id="add_employee" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Adds empty row to the table">Add row</button>
            </div>
            <div id="employee_messages"></div>
            <table class="table" id="employeeTable">
                <thead>
                    <tr>
                        <th data-bs-toggle="tooltip" data-bs-placement="top" title="Employee name">Employee</th>
                        <th data-bs-toggle="tooltip" data-bs-placement="top" title="Password is not displayed">Password</th>
                        <th data-bs-toggle="tooltip" data-bs-placement="top" title="Generate password and/or insert new employee or delete an employee"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
</div>
<?php require_once "inc/footer.php"; ?>
</body>
</html>
