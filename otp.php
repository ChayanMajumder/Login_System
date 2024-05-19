<?php
$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once("./partials/dbconnect.php");

    $errorMag = [];

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $otp = test_input($_POST['otp']);

    if (empty($otp)) {
        $errorMag['otp'] = "Please Enter a otp...";
    }

    if (count($errorMag) <= 0) {
        $selectSql =  $sql = "SELECT * from user where otp='$otp'";
        $result = mysqli_query($conn, $selectSql);
        $num = mysqli_num_rows($result);

        if ($num == 1) {
            echo "<script>alert('OTP Match Successful')</script>";
            $updateSql = "UPDATE user SET otp = NULL WHERE otp = '$otp'";
            $result = mysqli_query($conn, $updateSql);
            header("Location: ./reset.php?email=$email");
        } else {
            echo "<script>alert('OTP Not Match')</script>";
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

    <div class="container bg-info signup-form">
        <h3 class="text-center">Login to our Website</h3>
        <div class="">
            <form action="" method="post">

                <div class="mb-3">
                    <label for="otp" class="form-label">OTP:</label>
                    <input type="text" class="form-control" id="otp" placeholder="Enter OTP..." name="otp">
                    <?php if (isset($errorMag['password'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['password'];   ?> </span>
                    <?php  } ?>
                </div>
                <button type="submit" class="btn btn-primary">OTP Verify</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>