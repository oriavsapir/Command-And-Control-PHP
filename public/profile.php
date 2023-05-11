<?php   

require_once 'header.php';
    
?>
<div class="container mt-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 border rounded">
                <h2 class="mb-4">Change Password</h2>
                <form action="change_user_and_pass" method="post" id="change_password_form">
                    <div class="form-group">
                        <label for="current_pass">Current Password:</label>
                        <input class="form-control" type="password" name="current_password" id="current_pass" required>
                    </div>
                    <div class="form-group">
                        <label for="new_pass">New Password:</label>
                        <input class="form-control" type="password" name="new_password" id="new_pass" required>
                    </div>
                    <div class="form-group">
                        <label for="repeat_pass">Confirm Password:</label>
                        <input class="form-control" type="password" name="repeat_password" id="repeat_pass" required>
                    </div>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <button type="submit" class="btn btn-primary" name="submit_change_pass">Change Password</button>
                </form>
                <?php if (isset($_GET['pass_message'])): ?>
                    <h4 class="mt-3"><?= htmlspecialchars(str_replace('_', ' ', $_GET['pass_message'])) ?></h4>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="card p-4 border rounded">
                <h2 class="mb-4">Change Username</h2>
                <small>*This is case sensitive</small>
                <form action="change_user_and_pass" method="post" id="change_username_form">
                    <div class="form-group">
                        <label for="current_user">Current Username:</label>
                        <input class="form-control" type="text" name="current_username" id="current_user" required>
                    </div>
                    <div class="form-group">
                        <label for="new_user">New Username:</label>
                        <input class="form-control" type="text" name="new_username" id="new_user" required>
                    </div>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <button type="submit" class="btn btn-primary" name="submit_change_user">Change Username</button>
                </form>
                <?php if (isset($_GET['user_message'])): ?>
                    <h4 class="mt-3"><?= htmlentities(str_replace('_', ' ', $_GET['user_message'])) ?></h4>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php 
require_once 'footer.php'; 
?>