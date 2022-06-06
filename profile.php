<?php
require_once("conn.php");
if ($_SESSION['userId'] ==  1) {
    require_once('header.php');
    (int) $_SESSION['userId'] = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-4">
                <div class="border ruonded p-4">
                    <h2>Change Password</h2>
                    <form action="change_user_and_pass" method="post" id="change_password_form">
                        <label for="current_pass"> Current Password:
                            <input class="form-control" type="password" name="current_password" id="current_pass" autocomplete>
                        </label>
                        <label for="new_pass"> New Password:
                            <input class="form-control" type="password" name="new_password" id="new_pass" autocomplete>
                        </label>
                        <label for="repeat_pass"> Confirm Password:
                            <input class="form-control" type="password" name="repeat_password" id="repeat_pass" autocomplete>
                        </label><br>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" class="btn btn-primary" name="submit_change_pass">Change it!</button>
                    </form>
                    <?php echo '<h4>';echo isset($_GET['pass_message']) ? htmlspecialchars(str_replace('_', ' ', $_GET['pass_message'])) : null;echo '</h4>'; ?>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-4">
                <div class="border ruonded p-4">
                    <h2>Change UserName</h2>
                    <small>*This is case sensitive </small>
                    <form action="change_user_and_pass" method="post" id="change_username_form">
                        <label for="current_user"> Current Username:
                            <input class="form-control" type="text" name="current_username" id="current_user" autocomplete>
                        </label>
                        <label for="new_user"> New Username:
                            <input class="form-control" type="text" name="new_username" id="new_user" autocomplete>
                        </label><br>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" class="btn btn-primary" name="submit_change_user">Change it!</button>
                    </form>
                    <?php echo '<h4>';echo isset($_GET['user_message']) ? htmlentities(str_replace('_', ' ', $_GET['user_message'])) : null;echo '</h4>'; ?>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    http_response_code(404);
    die();
}
?>