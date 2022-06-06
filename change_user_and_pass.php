<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('conn.php');
    require_once("functions.php");
    if (isset($_POST['submit_change_pass']) and isset($_POST['current_password']) and isset($_POST['new_password']) and isset($_POST['repeat_password'])) {
        if (!empty($_POST['token'])) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                $sql = "SELECT password FROM users limit 1;";
                $stmt = mysqli_stmt_init($db);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: profile?pass_message=SQL_problem");
                    exit();
                } else {
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {

                        $pwd_Check = password_verify($_POST['current_password'], $row['password']);
                        if ($pwd_Check == true) {
                            if ($_POST['repeat_password'] !== $_POST['new_password']) {
                                header("Location: profile?pass_message=password_match");
                            } else {
                                $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                                $sql = 'UPDATE users set password = ? where id = 1';
                                $stmt = mysqli_prepare($db, $sql);
                                mysqli_stmt_bind_param($stmt, 's', $password);
                                mysqli_stmt_execute($stmt);
                                addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'INFO', "password chagne successfuly"); //LOG
                                header("Location: profile?pass_message=password_chagne_successfuly");
                            }
                        } else {
                            header("Location: profile?pass_message");
                        }
                    } else {
                        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "recive FALSE token in Change Password! Be Careful!"); //LOG
                        http_response_code(404);
                    }
                }
            } else {
                addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "recive FALSE token in Change Password! Be Careful!"); //LOG
                http_response_code(404);
            }
        } else {
            addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Dont recive token in Change Password! Be Careful!"); //LOG
            http_response_code(404);
        }
    } else {
        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Dont recive token in Change Password! Be Careful!"); //LOG
        http_response_code(404);
    }


    if (isset($_POST['submit_change_user']) and isset($_POST['current_username']) and isset($_POST['new_username'])) {
        if (!empty($_POST['token'])) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {

                $sql = "SELECT name FROM users limit 1;";
                $stmt = mysqli_stmt_init($db);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../profile?user_message=sql_problem");
                    exit();
                } else {
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        if ($row['name'] == $_POST['current_username']) {
                            $sql = 'UPDATE users set name = ? where id = 1';
                            $stmt = mysqli_prepare($db, $sql);
                            mysqli_stmt_bind_param($stmt, 's', $_POST['new_username']);
                            mysqli_stmt_execute($stmt);
                            addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'INFO', "Username chagne successfuly"); //LOG
                            header("Location: profile?user_message=username_chagne_successfuly");
                        } else {
                            header("Location: profile?user_message=There_is_not_such_a_user");
                        }
                    } else {
                        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "recive FALSE token in Change Username! Be Careful!"); //LOG
                    }
                }
            } else {
                addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "recive FALSE token in Change Username! Be Careful!"); //LOG
                http_response_code(404);
            }
        } else {
            addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Dont recive token in Change Username! Be Careful!"); //LOG
            http_response_code(404);
        }
    } else {
        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Dont recive token in Change Username! Be Careful!"); //LOG
        http_response_code(404);
    }
}else {
    http_response_code(404);
}
