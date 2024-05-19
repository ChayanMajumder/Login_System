<?php
require_once("./partials/dbconnect.php");
$errorMag = [];
$showError = false;
$login = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $userName = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    if (empty($userName)) {
        $errorMag['userName'] = "Please Enter a UserName...";
    }
    if (empty($password)) {
        $errorMag['password'] = "Please Enter a password...";
    }

    if (count($errorMag) <= 0) {
        // $sql = "SELECT * from user where userName='$userName' AND password='$password'";
        $sql = "SELECT * from user where userName='$userName'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        if ($num == 1) {
            if (password_verify($password, $row['password'])) {
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['userName'] = $userName;
                header("location: welcome.php");
            } else {
                $showError = true;
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
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php require 'partials/nav.php'  ?>
    <?php
    if ($login) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Login Successfully!</strong> You should check in on some of those fields below.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }

    ?>
    <?php
    if ($showError) {
        echo '<div class="alert alert-warning  alert-dismissible fade show" role="alert">
    <strong>Password Do Not Match!</strong> You should check in on some of those fields below.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }

    ?>
    <div class="container bg-info signup-form">
        <h3 class="text-center">Login to our Website</h3>
        <div class="">
            <form action="" method="post">
                <div class="mb-3 mt-3">
                    <label for="username" class="form-label">UserName:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter UserName.." name="username">
                    <?php if (isset($errorMag['userName'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['userName'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                    <?php if (isset($errorMag['password'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['password'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-1 forget-btn">
                    <a style="color:red" href="./forgotPassowrd.php">Forgot Password?</a>

                </div>
                <div class="mb-3">
                    <a style="color:blue" href="./signup.php">Create New Account</a>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>