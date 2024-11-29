<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CardioCompass/Styles/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
    <link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <title>Login</title>
</head>

<body>
    <!-- start of container class -->
    <div class="container">

        <!-- start of form-group -->
        <form action="" method="post" class="form-group">
            <img src="media/cardio-compass-bg-logo.png">

            <div class="form-group">
                <h2>Login to CardioCompass</h2>
                <label>Username:</label>
                <input type="text" name="username" id="user-name" placeholder="Username" required />

                <label>Password:</label>
                <input type="password" name="password" class="user-password" placeholder="Password" required>
                <input type="submit" name="submit" class="submit-btn" value="Sign in">
                <p>Don't have an account? <a href="registration-page.php"> Register here.</a> </p>
                <p>By clicking sign in, you are agreeing to the <a class="hyperlink" href="terms&conditions.php">Terms &
                        Conditions</a>
                </p>
            </div>
        </form>

        <!-- end of form-group -->

    </div>
    <!-- end of container class -->


    <div class="center">
    </div>
    <!-- <div class="img1">
        <img src="hand-drawn-doctor-character-in-flat-style-vector.jpg" alt="">
    </div> -->
    <div class="center1">
    </div>
    <!-- start of footer class -->
    <div class="footer">
        <div class="item1"></div>
    </div>
</body>

</html>

<?php
$servername = "localhost"; //database server
$username = "root"; //database username
$password = ""; //database password
$database = "clinic_website"; //database name

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if fields are filled
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Initialize variables for queries and POST method
            $un = mysqli_real_escape_string($con, $_POST['username']); // Clean email text
            $pw = mysqli_real_escape_string($con, $_POST['password']); // Clean password text

            // Check if the email exists in the database
            $userQuery = "SELECT accounts.AccountID, accounts.AccountName, accounts.AccountType, AccData.AccountUsername, AccData.AccountEmail, AccData.AccountPass
                          FROM accounts
                          JOIN AccData ON accounts.AccountID = AccData.AccountID
                          WHERE AccData.AccountUsername = '$un'";
            $result = mysqli_query($con, $userQuery);

            // Check if the query has a result
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION["username"] = $row["AccountUsername"];
                $_SESSION["password"] = $row["AccountPass"];
                $_SESSION["fullname"] = $row["AccountName"];
                $_SESSION["emailaddress"] = $row["AccountEmail"];
                $_SESSION["user_id"] = $row["AccountID"];
                $_SESSION["acctype"] = $row["AccountType"];
                if ($row['AccountPass'] == $pw) {
                    // Password is correct
                    if ($row["AccountType"] == "customer") {
                        header("Location: /CardioCompass/customer-page/welcome-page.php");
                    } elseif ($row["AccountType"] == "admin") {
                        header("Location: /CardioCompass/admin-page/admin-page.php");
                    } elseif ($row["AccountType"] == "employee") {
                        header("Location: /CardioCompass/employees-page/employee-page-backup.php");
                    }
                } else {
                    // Password is incorrect
                    echo "<script>alert('Incorrect password');</script>";
                }
            } else {
                // Email not present in the database
                echo "<script>alert('An account with this username doesn\'t exist');</script>";
            }
        } else {
            // Fields are not filled
            echo "<script>alert('Please fill in all the fields.');</script>";
        }
    }
}

// Close the connection
mysqli_close($con);
?>