<?php
$email = $_GET['email'];
require_once("./partials/dbconnect.php");
$errorMag = [];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $newPassword = test_input($_POST['newPassword']);
    $reEnterPassword = test_input($_POST['reEnterPassword']);

    if (empty($newPassword)) {
        $errorMag['newPassword'] = "Please Enter a newPassword...";
    }
    if (empty($reEnterPassword)) {
        $errorMag['reEnterPassword'] = "Please Enter a Re-Enter Password...";
    }

    if (count($errorMag) <= 0) {
        if ($newPassword === $reEnterPassword) {
            // print_r($_POST);
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $upDateSql = "UPDATE user SET password = '$hash' WHERE email = '$email'";
            $result = mysqli_query($conn, $upDateSql);
            if($result) {
                echo "<script>alert('Password UpDate Successfully')</script>";
                header("Location: ./login.php");
            } else {
                echo "<script>alert('Password Not Update')</script>";
            }
           
        } else {
            echo "<script>alert('Password Not Match')</script>";
           
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
                    <label for="newPassword" class="form-label">New Password:</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="Enter New password" name="newPassword">
                    <?php if (isset($errorMag['newPassword'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['newPassword'];   ?> </span>
                    <?php  } ?>
                </div>
                <div class="mb-3">
                    <label for="reEnterPassword" class="form-label">Re-Enter Password:</label>
                    <input type="password" class="form-control" id="reEnterPassword" placeholder="Re-Enter New password" name="reEnterPassword">
                    <?php if (isset($errorMag['reEnterPassword'])) {  ?>
                        <span style="color:red"> <?php echo $errorMag['reEnterPassword'];   ?> </span>
                    <?php  } ?>
                </div>
                <button type="submit" class="btn btn-primary">UpDate Password</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>