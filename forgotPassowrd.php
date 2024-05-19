<?php

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

    $email = test_input($_POST['email']);

    if (empty($email)) {
        $errorMag['email'] = "Please Enter a email...";
    }

    if (count($errorMag) <= 0) {
        $sql = "SELECT * from user where email='$email'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        if ($num == 1) {
            // echo "<script>alert('Account is Found')</script>";
            $otp = rand(100000, 999999);
            $updateSql = "UPDATE user SET otp = '$otp' WHERE email = '$email'";
            $result = mysqli_query($conn, $updateSql);
            if ($result) {
                // require_once("./registermail.php");
                require_once("./vendor/mail/mail.php");
                $sub = "OTP Send Successful";
                $msg = "your Password reset OTP is $otp";
                sendMail($email, $sub, $msg);
                header("Location: ./otp.php?email=$email");
            } else {
                echo "<script>alert('Failed to send OTP')</script>";
            }
        } else {
            echo "<script>alert('Account Not Found')</script>";
        }
    }
}


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">

    <title>Hello, world!</title>
</head>

<body>
<?php require 'partials/nav.php'  ?>
    <div class="container bg-info signup-form">
        <h3 class="text-center">Login to our Website</h3>
        <div class="">
            <form action="" method="post">

                <div class="mb-3 mt-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email...." name="email">
                    <?php if (isset($errorMag['email'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['email'];   ?> </span>
                    <?php  } ?>
                </div>
                <button type="submit" class="btn btn-primary">Find Account</button>
            </form>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>