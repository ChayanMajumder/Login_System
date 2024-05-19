<?php
require_once("./partials/dbconnect.php");
$errorMag = [];
$showAlert = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = test_input($_POST['name']);
    $userName = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $cpassword = test_input(($_POST['cpassword']));

    if (empty($name)) {
        $errorMag['name'] = "Please Enter a Name...";
    }
    if (empty($userName)) {
        $errorMag['userName'] = "Please Enter a UserName...";
    }
    if (empty($email)) {
        $errorMag['email'] = "Please Enter a email...";
    }
    if (empty($password)) {
        $errorMag['password'] = "Please Enter a password...";
    }
    if (empty($cpassword)) {
        $errorMag['cpassword'] = "Please Enter a Confirm Password";
    }

    if (count($errorMag) <= 0) {

        $existSql = "SELECT * FROM `user` WHERE username = '$userName'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);

        if ($numExistRows > 0) {
            $showError = "UserName Already Exists";
        } else {
            if ($password === $cpassword) {
                // print_r($_POST);
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insertSql = "INSERT INTO user(name, username, email, password) VALUES ('$name' ,'$userName','$email','$hash')";
                $result = mysqli_query($conn, $insertSql);
                if ($result) {
                    require_once("./registermail.php");
                    require_once("./vendor/mail/mail.php");
                    $sub = "Registration Successful";
                    $msg = $msg;
                    sendMail($email, $sub, $msg);
                    $showAlert = true;
                }
            } else {
                $showError = "password Do Not Match";
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SingUp Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php require 'partials/nav.php'  ?>
    <?php
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Account Create Successfully!</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }

    ?>
    <?php
    if ($showError) {
        echo '<div class="alert alert-warning  alert-dismissible fade show" role="alert">
    <strong>Error!</strong> ' . $showError . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }

    ?>
    <div class="container bg-info signup-form">
        <h3 class="text-center">Signup to our Website</h3>
        <div class="">
            <form action="" method="post">
                <div class="mb-3 mt-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Full Nmae.." name="name">
                    <?php if (isset($errorMag['name'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['name'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3 mt-3">
                    <label for="username" class="form-label">UserName:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter UserName.." name="username">
                    <?php if (isset($errorMag['userName'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['userName'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3 mt-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email...." name="email">
                    <?php if (isset($errorMag['email'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['email'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Password..." name="password">
                    <?php if (isset($errorMag['password'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['password'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3 mt-3">
                    <label for="cpassword" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="cpassword" placeholder="Enter Confirm Password..." name="cpassword">
                    <?php if (isset($errorMag['password'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['cpassword'];   ?> </span>
                    <?php  } ?>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>