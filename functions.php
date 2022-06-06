<?php
if (isset($_POST)) {

    function addLogs($db, $userlog, $date, $logType, $logContent)
    {
        $sql = "INSERT INTO audits (user_identifier, date, severity, log) VALUES (?,?,?,?);";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL statement failed!";
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $userlog,  $date, $logType, $logContent);
            mysqli_stmt_execute($stmt);
        }
    }
}
function OS_list($db, $os)
{
    global $user_id;
    $sql = "SELECT user_identifier,OS FROM victims WHERE OS LIKE ?;";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL4 statement failed!";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $os);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id[$row['user_identifier']] = $row['OS'];
        }
    }
}
function OS_list_selected($db, $os){
    {
        global $user_id;
        $sql = "SELECT user_identifier,OS FROM victims WHERE user_identifier LIKE ?;";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL4 statement failed!";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $os);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $user_id[$row['user_identifier']] = $row['OS'];
            }
        }
    }
}