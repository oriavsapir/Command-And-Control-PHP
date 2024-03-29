<?php
session_start();
$token =  $_SESSION['token'] = bin2hex(random_bytes(32));

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body class="bg-light">

    <div class="container" style="margin-top:100px;text-shadow: 2px 2px 2px #222222;">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 rounded border border-dark p-4" style="  box-shadow: 10px 10px 5px lightblue;">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">LOGIN</h3>
                    </div>
                    <div class="panel-body">
                        <form action="backend/login-backend.php" method="post">
                            <div class="form-group">
                                <input class="form-control mt-2" placeholder="Name" name="username" type="text" autofocus required>
                            </div>
                            <div class="form-group mt-2 mb-2">
                                <input class="form-control" placeholder="Password" name="password" autocomplete="on" type="password" required>
                            </div>
                            <input type="submit" class="btn btn-lg btn-success mt-2 " name="login-submit" value="Log in">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>