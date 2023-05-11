<?php
if (isset($_POST['logoff'])) {
    session_start();
    session_destroy();
    header("Location: ../index");
}