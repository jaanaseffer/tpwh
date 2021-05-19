$(document).ready(function () {

    //https://www.webslesson.info/2017/07/live-add-edit-delete-datatables-records-using-php-ajax.html
    // Fetch data for employee and board member work hour tables
    fetch_data();
    function fetch_data() {
        $('#employees_work_hours, #board_member_work_hours').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "columnDefs": [{
                "targets": 7,
                "orderable": false // Last column is not sortable
            }],
            "language": {
                "decimal": ".", // Displaying dot instead comma
            },
            "ajax": {
                url: "../../inc/fetch.php",
                type: "POST"
            }
        });
    }

    /*--- Datepicker / kuupäeva valimine ---*/
    // Datepickers for filtering invoice table
    $(function () {
        $('.datepicker2, .datepicker3').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            orientation: "bottom",
            weekStart: 1
        }).datepicker('setDate', 'today');
    });

    // Fetching data for invoice table
    let billTable = $('#billTable').DataTable({
        "processing": true,
        "serverSide": true,
        // Creating Excel export
        dom: 'Blfrtip',
        buttons: [ 'excel' ],
        "order": [],
        "language": {
            "decimal": ".",
        },
        "ajax": {
            "url": "../../inc/fetch_all_hours.php",
            "type": "POST",
            "data": function(data) {
                // Passing from and to date values with ajax call
                let fromDate = $('#fromDate').val();
                let toDate = $('#toDate').val();

                data.searchByFromDate = fromDate;
                data.searchByToDate = toDate;
            }
        }
    });
    // Adjusting column size to window
    billTable.columns.adjust().draw();

    // After filtering draw the table again
    $(document).on('click', '#filter',function() {
        billTable.draw();
    });

    // Fetching all employees/users
    fetch_employees();
    function fetch_employees() {
        $('#employeeTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "columnDefs": [{
                "targets": 1,
                "orderable": false
            }, {
                "targets": 2,
                "orderable": false
            }, {
                "width": "20%", "targets": 0
            }, {
                "width": "20%", "targets": 1
            }, {
                "width": "20%", "targets": 2
            }],
            "ajax": {
                url: "../../inc/fetch_employees.php",
                type: "POST"
            }
        });
    }

    // Updating data in employee and board member work hours table
    function update_data(id, column_name, value) {
        $.ajax({
            url: "../../inc/update.php",
            method: "POST",
            data: {id: id, column_name: column_name, value: value},
            success: function (data) {
                $('#alert_message, #alert_message_bm').html('<div class="alert alert-success">' + data + '</div>');
                $('#employees_work_hours, #board_member_work_hours').DataTable().destroy();
                fetch_data();
            }
        });
        setInterval(function () {
            $('#alert_message, #alert_message_bm').html('');
        }, 5000);
    }

    // On blur event update changed data
    $(document).on('blur', '.update', function () {
        let id = $(this).data("id");
        let column_name = $(this).data("column");
        let value = $(this).text();
        update_data(id, column_name, value);
    });

    // Adding new row into employee table
    $(document).on('click', '#add_employee',function() {
        let html = '<tr>';
        html += '<td id="data2"><input type="text" data-type="username" name="new_username" id="new_username"></td>';
        html += '<td id="data3"><input type="text" data-type="password" name="new_password" id="new_password"></td>';
        html += '<td><input type="button" class="btn btn-info btn-md" onclick="document.getElementById(\'new_password\').value = Password.generate(11)" id="generatePassword" value="Generate password"><input type="submit" name="create" class="btn btn-info btn-md" value="Insert" id="insertEmployee"></td>';
        html += '</tr>';

        $('#employeeTable tbody').prepend(html);
    })

    // Adding new row into employee work hours table
    $(document).on('click', '#add',function () {
        let html = '<tr>';
        html += '<td contenteditable id="data2"><input type="number" data-type="coverage" name="coverage[]" id="coverage_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data3"><input type="text" data-type="coverage_name" name="coverage_name[]" id="coverage_name_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data4"><input type="number" data-type="project_help" name="project_help[]" id="project_help_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data5"><input class="datepicker" type="text" id="wh_date"></td>';
        html += '<td contenteditable id="data6"><select name="department" id="department"><option value="MMS">MMS</option><option value="CP">CP</option><option value="FP">FP</option><option value="3DV">3DV</option><option value="DD">DD</option></select></td>';
        html += '<td contenteditable id="data7"></td>';
        html += '<td contenteditable id="data8"></td>';
        html += '<td><button data-bs-toggle="tooltip" data-bs-placement="top" title="Inserts work hours into database" type="button" name="insert" id="insert" class="btn btn-success btn-xs">Insert</button></td>';
        html += '</tr>';

        // https://forum.jquery.com/topic/date-picker-for-dymically-added-row-not-working
        // Initializing datepicker for date column
        $('#employees_work_hours tbody').prepend(html).find('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            orientation: "bottom",
            weekStart: 1
        }).datepicker('setDate', 'today');

        // https://smarttutorials.net/jquery-autocomplete-multiple-fields-using-ajax-php-mysql-example/
        // Calling autocomplete function
        autoComplete();
    });

    // Adding new row into board member work hours table
    $(document).on('click', '#add_row',function () {
        let html = '<tr>';
        html += '<td contenteditable id="data2"><input type="text" data-type="coverage" name="coverage[]" id="coverage_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data3"><input type="text" data-type="coverage_name" name="coverage_name[]" id="coverage_name_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data4"><input type="text" data-type="project_help" name="project_help[]" id="project_help_1" class="autocomplete_txt"></td>';
        html += '<td contenteditable id="data5"><input class="datepicker" type="text" id="wh_date"></td>';
        html += '<td contenteditable id="data6"><select name="department" id="department"><option value="MMS">MMS</option><option value="CP">CP</option><option value="FP">FP</option><option value="3DV">3DV</option><option value="DD">DD</option></select></td>';
        html += '<td contenteditable id="data7"></td>';
        html += '<td contenteditable id="data8"></td>';
        html += '<td><button data-bs-toggle="tooltip" data-bs-placement="top" title="Inserts work hours into database" type="button" name="insert" id="insert_bm" class="btn btn-success btn-xs">Insert</button></td>';
        html += '</tr>';

        // https://forum.jquery.com/topic/date-picker-for-dymically-added-row-not-working
        // Initializing datepicker for date column
        $('#board_member_work_hours tbody').prepend(html).find('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            orientation: "bottom",
            weekStart: 1
        }).datepicker('setDate', 'today');

        // https://smarttutorials.net/jquery-autocomplete-multiple-fields-using-ajax-php-mysql-example/
        autoComplete();
    });

    // Inserting new employee with ajax call
    $(document).on('click', '#insertEmployee', function () {
        let new_username = $('#new_username').val();
        let new_password = $('#new_password').val();
        if (new_username != '' && new_password !='') {
            $.ajax({
                url: "../../inc/insert_employee.php",
                method: "POST",
                data: {
                    new_username: new_username,
                    new_password: new_password,
                },
                success: function (data) {
                    $('#employee_messages').html('<div class="alert alert-success">' + data + '</div>');
                    $('#employeeTable').DataTable().destroy();
                    fetch_employees();
                }
            });
            setInterval(function () {
                $('#employee_messages').html('');
            }, 3000);
        } else {
            alert("New employee added!");
        }
    });

    // Inserting work hours in employee and board member table
    $(document).on('click', '#insert, #insert_bm', function () {
        let coverage = $('#coverage_1').val();
        let coverage_name = $('#coverage_name_1').val();
        let project_help = $('#project_help_1').val();
        let date = $('#wh_date').val();
        let department = $('#department').val();
        let hours = $('#data7').text();
        let comments = $('#data8').text();
        if (coverage != '' && coverage_name !='' && date != '' && department != '' && hours != '') {
            $.ajax({
                url: "../../inc/insert.php",
                method: "POST",
                data: {
                    coverage: coverage,
                    coverage_name: coverage_name,
                    project_help: project_help,
                    date: date,
                    department: department,
                    hours: hours,
                    comments: comments
                },
                success: function (data) {
                    $('#alert_message, #alert_message_bm').html('<div class="alert alert-success">' + data + '</div>');
                    $('#employees_work_hours, #board_member_work_hours').DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function () {
                $('#alert_message, #alert_message_bm').html('');
            }, 3000);
        } else {
            alert("Only project help number and comments field can be empty");
        }
    });

    // Deleting work hours from database
    $(document).on('click', '.delete', function () {
        let id = $(this).attr("id");
        if (confirm("Are you sure you want to remove this work hours?")) {
            $.ajax({
                url: "../../inc/delete.php",
                method: "POST",
                data: {id: id},
                success: function (data) {
                    $('#alert_message, #alert_message_bm').html('<div class="alert alert-success">' + data + '</div>');
                    $('#employees_work_hours, #board_member_work_hours').DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function () {
                $('#alert_message, #alert_message_bm').html('');
            }, 3000);
        }
    });

    // Delete employee from database
    $(document).on('click', '.delete_employee', function () {
        let id = $(this).attr("id");
        if (confirm("Are you sure you want to remove this employee?")) {
            $.ajax({
                url: "../../inc/delete_employee.php",
                method: "POST",
                data: {id: id},
                success: function (data) {
                    $('#employee_messages').html('<div class="alert alert-success">' + data + '</div>');
                    $('#employeeTable').DataTable().destroy();
                    fetch_employees();
                }
            });
            setInterval(function () {
                $('#employee_messages').html('');
            }, 3000);
        }
    });

    // https://www.w3schools.com/bootstrap4/bootstrap_tooltip.asp
    // Making tooltips toggle
    $('[data-bs-toggle="tooltip"]').tooltip();

    /*--- Router-link / router-view ---*/
    // Adding page to main url
    $(document).on('click', '[data-router-link]', function () {
        let page = $(this).data('router-link');
        let url = window.location.origin + window.location.pathname + '?page=' + page;
        window.history.pushState({}, '', url);
        router();
    });

    // Making nav link active and adding page
    function router() {
        let urlParams = new URLSearchParams(window.location.search);
        let page = urlParams.get('page') || '';
        $('[data-router-view]').removeClass('active');
        $('[data-router-view="' + page + '"]').addClass('active');
        $('[data-router-link]').removeClass('nav_item--active')
        $('[data-router-link="' + page + '"]').addClass('nav_item--active');
    }

    router();
    $(window).on('popstate', function () {
        router();
    });

// https://www.scrapersnbots.com/blog/code/how-to-put-mouse-cursor-inside-input-text-textarea.php
// Put cursor in username input on login page
    $('#username').focus();

    // Function to show autocomplete search results
    function autoComplete() {
        $(document).on('focus', '.autocomplete_txt', handleAutocomplete);
        // Function to get current element id and splitting it, returning row number
        function getId(element){
            let id, idArray;
            id = element.attr('id');
            idArray = id.split('_');
            console.log(idArray);
            return idArray[idArray.length - 1];
        }

        // Function to assign field numbers to different cases
        function getFieldNo(type) {
            let fieldNo;
            switch (type) {
                case 'coverage':
                    fieldNo = 0;
                    break;
                case 'coverage_name':
                    fieldNo = 1;
                    break;
                case 'project_help':
                    fieldNo = 2;
                    break;
                default:
                    break;
            }
            return fieldNo;
        }

        // Function for auto completing three fields
        function handleAutocomplete() {
            let type, fieldNo, currentElement;
            type = $(this).data('type');
            fieldNo = getFieldNo(type);
            currentElement = $(this);

            if (typeof fieldNo === 'undefined') {
                return false;
            }

            // Initializing jQuery autocomplete
            $(this).autocomplete({
                // Making ajax call to get the data we want to autocomplete
                source: function(data, callback) {
                    // Making ajax call to getDetails.php
                    $.ajax({
                        url:'../../inc/getDetails.php',
                        method: 'get',
                        dataType: 'json',
                        data: {
                            coverage: data.term,
                            fieldNo: fieldNo
                        },
                        success: function(response) {
                            let result;
                            result = [
                                {
                                    label: '',
                                    value: ''
                                }
                            ];
                            // Making an object and saving it into variable result
                            if(response.length) {
                                result = $.map(response, function (obj) {
                                    let array = obj.split("|");
                                    return {
                                        label: array[fieldNo],
                                        value: array[fieldNo],
                                        data: obj
                                    };
                                });
                            }
                            callback(result);
                        }
                    });
                },
                autofocus: true,
                minLength: 1,
                // After selecting something from suggestion list fill in other two fields
                select: function(event, ui) {
                    let resultArray, rowNo;

                    rowNo = getId(currentElement);
                    resultArray = ui.item.data.split("|");

                    $('#coverage_' + rowNo).val(resultArray[0]);
                    $('#coverage_name_' + rowNo).val(resultArray[1]);
                    $('#project_help_' + rowNo).val(resultArray[2]);
                }
            });
        }
    }

    // Autoclosing collapse menu
    $(document).on('click', '.navbar-collapse a',function () {
        $(".navbar-collapse").collapse("hide");
    });
});
// Generate password
// https://stackoverflow.com/questions/9719570/generate-random-password-string-with-requirements-in-javascript
//
let Password = {

    // Pattern from where symbols are selected
    _pattern: /[a-zA-Z0-9_\-\+\.\!\?\@\#\$\%\&\*\(\)\{\}\[\]\,\;\:]/,
    // Function to get random symbols from pattern using window crypto and getRandomValues method
    _getRandomByte: function () {
        if (window.crypto && window.crypto.getRandomValues) {
            // Uint8Array - array of 8-bit integers, length 1
            let result = new Uint8Array(1);
            window.crypto.getRandomValues(result);
            return result[0];
        } else if (window.msCrypto && window.msCrypto.getRandomValues) {
            let result = new Uint8Array(1);
            window.msCrypto.getRandomValues(result);
            return result[0];
        } else {
            return Math.floor(Math.random() * 256);
        }
    },

    // Putting password together from symbols
    generate: function (length) {
        return Array.apply(null, {
            'length': length
        })
            .map(function () {
                let result;
                while (true) {
                    result = String.fromCharCode(this._getRandomByte());
                    if (this._pattern.test(result)) {
                        return result;
                    }
                }
            }, this)
            .join('');
    }

};
