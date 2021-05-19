<?php
require_once "inc/authenticate.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Terrapro workhours</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link rel="stylesheet" href="/assets/css/tpwh.css">
</head>
<body>
<img src="assets/img/logo.png" alt="Terrapro logo" class="logo">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <!-- Login form -->
            <form class="form" action="" method="post" id="loginForm">
                <h3>Login</h3>
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                           required>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-info btn-md" value="Login" id="login">
                </div>
                <!-- Error message -->
                <?php
                if(isset($login_error_message)){
                    echo '<div class="error_message">'.$login_error_message.'</div>';
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php require_once "inc/footer.php"; ?>
</body>
</html>
