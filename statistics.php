<?php
require_once("conn.php");
if ($_SESSION['userId'] ==  1) {

    //count users
    $sql = "SELECT COUNT(id) FROM victims;";
    $totaluser = mysqli_fetch_row($result = mysqli_query($db, $sql))[0];

    //count active users
    $sql = "SELECT COUNT(active) FROM victims WHERE active = 'Yes';";
    $activeuser = mysqli_fetch_row($result = mysqli_query($db, $sql))[0];

    //Critical logs
    $sql = "SELECT COUNT(id) FROM `audits` WHERE `severity` = 'Critical';";
    $critical_log = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //Active Seseion
    //$sql = 'SELECT victims.Active, command.send_command, user_identifier FROM command,victims WHERE user_identifier='
    $sql = "SELECT count(*) from command WHERE command.id IN (SELECT max(command.id) id
            FROM victims, command
            WHERE command.user_identifier = victims.user_identifier
            AND victims.active = 'Yes'
            GROUP BY command.user_identifier) and command.send_command NOT LIKE 'sleep%'";
    $number_of_active_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //OS unmber PieChart Win
    $sql = "SELECT COUNT(OS) FROM victims WHERE OS LIKE '%WIN%'";
    $number_of_win = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //OS unmber PieChart Linux
    $sql = "SELECT COUNT(OS) FROM victims WHERE OS LIKE '%Linux%'";
    $number_of_Linux = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //OS unmber PieChart Mac
    $sql = "SELECT COUNT(OS) FROM victims WHERE OS LIKE '%mac%'";
    $number_of_mac = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //OS unmber PieChart Null
    $sql = "SELECT COUNT(*) FROM victims WHERE OS IS NULL";
    $number_of_null = mysqli_fetch_row(mysqli_query($db, $sql))[0];

    //OS unmber PieChart Other
    $sql = "SELECT COUNT(OS) FROM victims WHERE OS NOT LIKE '%WIN%' AND '%Linux%' AND '%mac%'";
    $number_of_other = mysqli_fetch_row(mysqli_query($db, $sql))[0];


?>
    <div class="container">
        <div class="row">
            <div class="col-8 text-center ">
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold display-6">
                                        Total Users:<br><span class="text-success"><?php echo htmlspecialchars($totaluser); ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold display-6">
                                        Active Users:<br>
                                        <span class="text-success"><?php echo htmlspecialchars($activeuser); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold display-6">
                                        Active Sessions:<br>
                                        <span class="text-success"><?php echo htmlspecialchars($number_of_active_user); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold display-6">
                                        Critical & Important Log<br>
                                        <?php
                                        if ($critical_log != 0) {
                                            echo ' <a href="log?type=critical" class="text-danger">';
                                        } else {
                                            echo ' <a href="log?type=critical" class="text-success">';
                                        }
                                        echo htmlspecialchars($critical_log); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-left-primary shadow">
                    <div id="piechart"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {
            'packages': ['corechart']
        });

        function QueryToOS() {}
        QueryToOS()
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['', ''],
                ['Windows', <?= htmlspecialchars($number_of_win) ?>],
                ['Linux', <?= htmlspecialchars($number_of_Linux) ?>],
                ['Mac', <?= htmlspecialchars($number_of_mac) ?>],
                ['Other', <?= htmlspecialchars($number_of_other) ?>],
                ['Null', <?= htmlspecialchars($number_of_null) ?>]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {
                'title': 'OS',
                'width': 320,
                'height': 165
            };

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    </body>

    </html>
<?php
} else {
    http_response_code(404);
    die();
} ?>