<?php
session_start();
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$database = "clinic_website"; // your database name

$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$sess_username = $_SESSION['username'];

$stmt = $con->prepare("SELECT * FROM accounts
                        JOIN employee ON accounts.AccountID = employee.AccountID 
                        JOIN employee_details ON employee.EmployeeID = employee_details.EmployeeID
                        JOIN accdata ON accounts.AccountID = accdata.AccountID
                        WHERE accdata.AccountUsername = ?");

// Bind the parameter
$stmt->bind_param("s", $sess_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $un = $_SESSION['username'];
}

// Close the statement
$stmt->close();

// Close the connection
$con->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Account Information</title>
    <link rel="stylesheet" href="/CardioCompass/Styles/employee-account.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
    <link rel="icon" type="png" href="media/cardio-compass-logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/CardioCompass/media/cardio-compass-logo.png">
</head>

<body>

    <div class="logo-text">
        <img src="/CardioCompass/media/cardio-compass-logo.png" alt="">
        <a href="">CardioCompass</a>
    </div>

    <div class="container">
        <form id="account-form" action="" method="post"></form>
        <div class="profile">
            <div class="return">
                <a href="javascript:history.back()">
                    <span class="material-symbols-outlined">
                        arrow_back_ios
                    </span>Go Back</a>
            </div>
            <h2>Account Information</h2>
            <img src="<?php echo $row['EmployeePicture']; ?>" alt="">
            <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
                <label for="profile">Upload Photo</label>
                <input type="file" name="profile">
                <input type="submit" class="submit-btn btn" value="Upload" name="submit">

            </form>
        </div>
        <div class="form-group">
            <div class="left-col">
                <label>Username:</label>
                <input type="text" name="username" id="user-name" value="<?php echo $_SESSION['username']; ?>"
                    readonly />
                <label>Full Name:</label>
                <input type="text" name="fullname" id="full-name" value="<?php echo $_SESSION['fullname']; ?>"
                    readonly />
                <label>Email Address:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $_SESSION['emailaddress']; ?>"
                    readonly />
                <input type="button" id="edit-btn" class="submit-btn btn" value="Edit" onclick="toggleEdit()">
            </div>
            <div class="right-col">
                <label>Password:</label>
                <input type="password" name="user-password-reg" class="user-password-reg" placeholder="Password"
                    value="<?php echo $_SESSION['password']; ?>" readonly>
                <label>Confirm Password:</label>
                <input type="password" name="user-cpassword-reg" class="user-cpassword-reg"
                    placeholder="Confirm password" readonly>
            </div>
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

if (isset($_POST['submit'])) {
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/CardioCompass/employeePicture/";
    $targetFile = $targetDir . basename($_FILES["profile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Check file size
    if ($_FILES["profile"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
            echo "The file " . basename($_FILES["profile"]["name"]) . " has been uploaded.";

            // Insert file path into the database
            $accountID = $_SESSION['user_id'];
            $relativePath = "/CardioCompass/employeePicture/" . basename($_FILES["profile"]["name"]);
            $insertQuery = "UPDATE employee_details 
                            SET EmployeePicture='$relativePath' 
                            WHERE EmployeeID = (SELECT employee.EmployeeID FROM employee WHERE employee.AccountID='$accountID')";

            if (mysqli_query($con, $insertQuery)) {
                echo "Profile picture uploaded successfully.";
            } else {
                echo "Error updating record: " . mysqli_error($con);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

mysqli_close($con);
?>