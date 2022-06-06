<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "c2");
(!$db) ? die("Connection failed: " . mysqli_connect_error()) : "";
(int) $_SESSION['userId'] = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;