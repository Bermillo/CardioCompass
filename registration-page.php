<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<link rel="stylesheet" href="/CardioCompass/Styles/regis-style.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
<link rel="icon" type="png" href="media/cardio-compass-bg-logo.png">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
    <div class="container">
        <form action="" method="post" class="form-group">
            <div class="form-group">
                <img src="media/cardio-compass-bg-logo.png">
                <h2>Create an Account</h2>
                <label>Username:</label>
                <input type="text" name="username" id="user-name" placeholder="Username" required />
                <label>Full Name:</label>
                <input type="text" name="fullname" id="full-name" placeholder="Full Name" required />
                <label>Email Address:</label>
                <input type="email" name="email" class="form-control" placeholder="E-mail" required />
                <label>Password:</label>
                <input type="password" name="user-password-reg" class="user-password-reg" placeholder="Password"
                    required>
                <label>Confirm Password:</label>
                <input type="password" name="user-cpassword-reg" class="user-cpassword-reg"
                    placeholder="Confirm password" required>
                <div class="checkboxdirection">
                    <input type="checkbox" id="terms-and-conditions" name="terms-and-conditions" required>
                    <label for="terms-and-conditions" span=2fr>I agree to the
                        <a href="/CardioCompass/terms&conditions.php">terms and conditions</a>
                    </label>
                </div>
                <input type="submit" name="submit" class="submit-btn" value="Register">
                <div>
                    <p>Already have an account? <a class="hyperlink" href="login-page.php">Login here</a>.</p>
                </div>
            </div>
        </form>
    </div>
    <div class="center-reg1">
    </div>
    <!-- <div class="img1">
        <img src="hand-drawn-doctor-character-in-flat-style-vector.jpg" alt="">
    </div> -->
    <div class="center-reg2">
    </div>
    <!-- start of footer class -->
    <div class="footer">
        <div class="item1"></div>
    </div>
</body>

</html>
<?php
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$database = "clinic_website"; // your database name

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uname = mysqli_real_escape_string($con, $_POST['username']);
        $funame = mysqli_real_escape_string($con, $_POST['fullname']);
        $em = mysqli_real_escape_string($con, $_POST['email']);
        $pw = mysqli_real_escape_string($con, $_POST['user-password-reg']);
        $cpw = mysqli_real_escape_string($con, $_POST['user-cpassword-reg']);

        if (!empty($funame) && !empty($em) && !empty($pw) && !empty($cpw)) {
            if ($pw === $cpw) {
                // Check if email already exists
                $checkEmailQuery = "SELECT AccountID FROM AccData WHERE AccountEmail = '$em'";
                $result = mysqli_query($con, $checkEmailQuery);

                if (mysqli_num_rows($result) > 0) {
                    echo "<script>alert('An account with this email already exists.');</script>";
                } else {
                    // Begin transaction
                    mysqli_begin_transaction($con);

                    try {
                        // Insert into Accounts table
                        $sql_accounts = "INSERT INTO Accounts (AccountName, AccountType) VALUES ('$funame', 'customer')";
                        if (mysqli_query($con, $sql_accounts)) {
                            // Get the last inserted ID
                            $last_id = mysqli_insert_id($con);

                            // Insert into AccData table
                            $sql_accdata = "INSERT INTO AccData (AccountID, AccountUsername , AccountEmail, AccountPass) 
                            VALUES ('$last_id', '$uname', '$em', '$pw')";

                            if (mysqli_query($con, $sql_accdata)) {
                                // Insert into Customers table
                                $sql_acccustomers = "INSERT INTO customer (CustomerID, AccountID) 
                                                    VALUES ('$last_id', '$last_id')";
                                if (mysqli_query($con, $sql_acccustomers)) {
                                    // Commit transaction if all inserts succeed
                                    mysqli_commit($con);
                                    echo "<script>alert('Registration successful.');</script>";
                                    $registrationSuccess = true;
                                } else {
                                    // Rollback transaction if insert into customers fails
                                    mysqli_rollback($con);
                                    echo "Error: " . mysqli_error($con);
                                }
                            } else {
                                // Rollback transaction if insert into AccData fails
                                mysqli_rollback($con);
                                echo "Error: " . mysqli_error($con);
                            }
                        } else {
                            // Rollback transaction if insert into Accounts fails
                            mysqli_rollback($con);
                            echo "Error: " . mysqli_error($con);
                        }
                    } catch (Exception $e) {
                        // Rollback transaction in case of an exception
                        mysqli_rollback($con);
                        echo "Error: " . $e->getMessage();
                    }
                }
            } else {
                echo "<script>alert('Passwords do not match.');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all the fields.');</script>";
        }
    }
}

// Close the connection
mysqli_close($con);
?>