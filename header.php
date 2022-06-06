<?php
if (isset($_POST['logoff'])) {
    session_destroy();
    header("Location:login");
}
$token =  $_SESSION['token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Connand and Control</title>

</head>


<body class="bg-light">
    <div class="text-right">
        <a href="Dashboard" class="text-dark text-decoration-none"> Dashboard |</a>
        <a href="profile" class="text-dark text-decoration-none"> Profile |</a>
        <a href="error_log" class="text-dark text-decoration-none">Server error logs |</a>
        <a href="log" class="text-dark text-decoration-none"> Command and response logs | </a>
        <form action="" method="post" class="float-end">
            <button name="logoff" class="bg-light border-0">Logout</button>
        </form>
        <div class="p-0 m-0 bg-white" style="min-height:10px;"></div>
    </div>