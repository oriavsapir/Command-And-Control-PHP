<?php
if (isset($_POST['login-submit'])) {
    session_start();
    require_once "../functions.php";
    require_once "../conn.php";
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