<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Account Information</title>
    <link rel="stylesheet" href="/CardioCompass/Styles/admin-account.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
    <link rel="icon" type="png" href="media/cardio-compass-bg-logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="return">
        <a href="/CardioCompass/admin-page/admin-page.php"> <span class="material-symbols-outlined">
                arrow_back_ios
            </span>Go Back</a>
    </div>
    <div class="container">
        <form id="account-form" action="" method="post" class="form-group">
            <div class="form-group">
                <span class="material-symbols-outlined">
                    account_circle
                </span>
                <h2>Account Information</h2>
                <label>Username:</label>
                <input type="text" name="username" id="user-name" value="<?php echo $_SESSION['username']; ?>"
                    readonly />
                <label>Full Name:</label>
                <input type="text" name="fullname" id="full-name" value="<?php echo $_SESSION['fullname']; ?>"
                    readonly />
                <label>Email Address:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $_SESSION['emailaddress']; ?>"
                    readonly />
                <label>Password:</label>
                <input type="password" name="user-password-reg" class="user-password-reg" placeholder="Password"
                    value="<?php echo $_SESSION['password']; ?>" readonly>
                <label>Confirm Password:</label>
                <input type="password" name="user-cpassword-reg" class="user-cpassword-reg"
                    placeholder="Confirm password" readonly>
                <input type="button" id="edit-btn" class="submit-btn" value="Edit" onclick="toggleEdit()">
            </div>
        </form>
    </div>

    <!-- modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to save the changes?</p>
            <button id="confirm-btn" class="submit-btn">Confirm</button>
            <button id="cancel-btn" class="submit-btn">Cancel</button>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const formFields = document.querySelectorAll('.form-group input');
            const editButton = document.getElementById('edit-btn');

            formFields.forEach(field => {
                if (field.type !== 'submit' && field.type !== 'button') {
                    field.readOnly = !field.readOnly;
                }
            });

            if (editButton.value === 'Edit') {
                editButton.value = 'Done';
            } else {
                document.getElementById('myModal').style.display = "block";
            }
        }

        var modal = document.getElementById('myModal');
        var span = document.getElementsByClassName('close')[0];
        var confirmBtn = document.getElementById('confirm-btn');
        var cancelBtn = document.getElementById('cancel-btn');


        span.onclick = function () {
            modal.style.display = 'none';
            document.getElementById('edit-btn').value = 'Edit';
        }


        cancelBtn.onclick = function () {
            modal.style.display = 'none';
            document.getElementById('edit-btn').value = 'Edit';
        }


        confirmBtn.onclick = function () {
            modal.style.display = 'none';
            document.getElementById('account-form').submit();
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                document.getElementById('edit-btn').value = 'Edit';
            }
        }
    </script>
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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = mysqli_real_escape_string($con, $_POST['username']);
    $funame = mysqli_real_escape_string($con, $_POST['fullname']);
    $em = mysqli_real_escape_string($con, $_POST['email']);
    $pw = mysqli_real_escape_string($con, $_POST['user-password-reg']);
    $cpw = mysqli_real_escape_string($con, $_POST['user-cpassword-reg']);

    if (!empty($funame) && !empty($em) && !empty($pw) && !empty($cpw)) {
        if ($pw === $cpw) {
            //joined tables for account and accdata
            $userQuery = "SELECT accounts.AccountID, accounts.AccountName, accounts.AccountType, AccData.AccountUsername, AccData.AccountEmail, AccData.AccountPass
            FROM accounts
            JOIN AccData ON accounts.AccountID = AccData.AccountID
            WHERE AccData.AccountUsername = '$uname'";
            $result = mysqli_query($con, $userQuery);
            $row = mysqli_fetch_assoc($result);
            $_SESSION["username"] = $row["AccountUsername"];
            $_SESSION["password"] = $row["AccountPass"];
            $_SESSION["fullname"] = $row["AccountName"];
            $_SESSION["emailaddress"] = $row["AccountEmail"];
            $_SESSION["user_id"] = $row["AccountID"];

            if ($result) {
                try {
                    mysqli_begin_transaction($con);
                    $sql_accounts = "UPDATE Accounts SET AccountName = '$funame' WHERE AccountID = " . $_SESSION['user_id'];
                    $sql_accdata = "UPDATE AccData SET AccountUsername = '$uname', AccountEmail = '$em', AccountPass = '$pw' WHERE AccountID = " . $_SESSION['user_id'];

                    if (mysqli_query($con, $sql_accounts) && mysqli_query($con, $sql_accdata)) {
                        mysqli_commit($con);
                        echo "<script>alert('Update successful.');</script>";
                    } else {
                        mysqli_rollback($con);
                        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
                    }
                } catch (Exception $e) {
                    mysqli_rollback($con);
                    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
                }
            } else {
                echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}

// Close the connection
mysqli_close($con);
?>