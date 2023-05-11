<?php
$db = mysqli_connect("db", "root", "root", "c2");
(!$db) ? die("Connection failed: " . mysqli_connect_error()) : "";
