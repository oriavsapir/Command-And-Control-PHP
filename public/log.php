<?php

require_once 'header.php';

$user_id = isset($_GET['user_identifier']) ? $_GET['user_identifier'] : null;
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="style/style.css">

<div class="container">
    <div class="mb-3">
        <form action="actions.php" method="POST" id="log_checkbox">
            <input type="hidden" name="delete" required>
            <button type="submit" name="delete_logs" class="text-danger bg-white border border-danger ">delete selected
                logs <i class="fa fa-trash-o"></i></button>
        </form>
    </div>
    <table class="table" id="log_table">
        <thead>
            <tr>
                <th><input type="checkbox" onClick="toggle(this)"></th>
                <th>#</th>
                <th>identifier</th>
                <th>date</th>
                <th>severity</th>
                <th>log</th>
                <th>actions</th>
            </tr>
        </thead>
        <?php
            if ($user_id != null) {
                $sql = "SELECT      *
                        FROM        audits
                        WHERE       user_identifier = ? ";
            } else {
                $sql = "SELECT      *
                        FROM        audits";
            }
            $stmt = mysqli_stmt_init($db);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "SQL4 statement failed!";
            } else {
                if ($user_id != null) {
                    mysqli_stmt_bind_param($stmt, 'i', $user_id);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '   <td><input form="log_checkbox" type="checkbox" value=' . htmlspecialchars($row['id']) . ' name="logs_id[]">&nbsp;</td>
                            <th>' . htmlspecialchars($row['id']) . '</th>
                            <td>' . htmlspecialchars($row['user_identifier']) . '</td>
                            <td style="min-width: 130px;"> ' . htmlspecialchars($row['date']) . '</td>
                            <td> ' . htmlspecialchars($row['severity']) . '</td>
                            <td> ' . htmlspecialchars($row['log']) . '</td>
                            <td>
                                <form action="actions.php" method="post">
                                    <button name="delete_log" class="btn btn-danger btn-xs delete" value=' . htmlspecialchars($row["id"]) . '>Del </button>
                                </form>
                            </td>
                            </tr>';
                }
            } ?>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
if (new URL(window.location.href).searchParams.get("type") == "critical") {
    $(document).ready(function() {
        $('#log_table').DataTable({
            'columnDefs': [{
                'orderable': false,
                'targets': [0, 6]
            }], // hide sort icon on header of first column
            'aaSorting': [
                [1, 'asc']
            ],
            "search": {
                "search": "Critical"
            }
        });
    });
} else {
    $(document).ready(function() {
        $('#log_table').DataTable({
            'columnDefs': [{
                'orderable': false,
                'targets': [0, 6]
            }], // hide sort icon on header of first column
            'aaSorting': [
                [1, 'asc']
            ]
        });
    });
}

function toggle(source) {
    checkboxes = document.getElementsByName('logs_id[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>
<script src="//cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>

<?php 
require_once 'footer.php'; 
?>