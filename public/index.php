<?php
session_start();
$token =  $_SESSION['token'] = bin2hex(random_bytes(32));
if (isset($_POST['login-submit'])) {
    require_once "functions.php";
    require_once "conn.php";
    $user_identifier  = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($username) || empty($password)) {
        addLogs($db, $user_identifier, $date, 'WARNING', " $user_identifier Users Send Req with empty fields!"); //LOG
        header("location:" . $_SERVER['HTTP_REFERER'] . "?Error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE name=?;";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            addLogs($db, $user_identifier, $date, 'error', "There is a problem with server connctions"); //LOG
            header("Location: ../index?sqlErorr");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwd_Check = password_verify($password, $row['password']);
                if ($pwd_Check == false) {
                    addLogs($db, $user_identifier, $date, 'WARNING', "Password incorrect! the input is: $password"); //LOG
                    header("location:" . $_SERVER['HTTP_REFERER'] . "?Error=worngUserorPwd");
                    exit();
                } elseif ($pwd_Check == true) {
                    session_regenerate_id(true);
                    $_SESSION['userId'] = 1;
                    addLogs($db, $user_identifier, $date, 'INFO', " $username Login successfully"); //LOG
                    header("location: ../dashboard");
                    exit();
                }
            }
        }
    }
}

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
                        <form action="#" method="post">
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