<?php
session_start();
if ($_SESSION['acctype'] != 'employee') {
    header('Location: ../login-page.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "clinic_website";

$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    $employeeQuery = "SELECT * FROM accounts
        JOIN employee ON accounts.AccountID = employee.AccountID 
        JOIN employee_details ON employee.EmployeeID = employee_details.EmployeeID
        JOIN accdata ON accounts.AccountID = accdata.AccountID
        WHERE accdata.AccountUsername = '{$_SESSION['username']}'";
        
    $result = mysqli_query($con, $employeeQuery);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["username"] = $row["AccountUsername"];
        $_SESSION["password"] = $row["AccountPass"];
        $_SESSION["fullname"] = $row["AccountName"];
        $_SESSION["emailaddress"] = $row["AccountEmail"];
        $_SESSION["user_id"] = $row["AccountID"];
        $_SESSION["acctype"] = $row["AccountType"];
        $_SESSION["accprofile"] = $row["EmployeePicture"];
        $_SESSION["employee_id"] = $row["EmployeeID"];  // Store EmployeeID in session
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Account Information</title>
<link rel="stylesheet" href="/CardioCompass/Styles/employee-page.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
<link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<section>
<div class="navigation-bar">
    <div class="logo-text">
        <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="Logo">
        <a>CardioCompass</a>
    </div>
    <div class="user-side">
        <div class="dropdown">
            <button class="profile-btn">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
            <div class="navdropdown-content">
                <a href="/CardioCompass/employees-page/employee-account.php">Account</a>
                <a href="/CardioCompass/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>
</section>
<section class="card-info">
<h2>Welcome back, Dr. <?php echo $_SESSION["fullname"]; ?></h2>
<h4>Doctor Dashboard</h4>
<div class="card-container">
    <div>
        <button class="doctor-profile" onclick="ToSchedule()">
            <img src="/CardioCompass/media/calendar_symbol.svg">
            Schedule
        </button>
    </div>
    <div>
        <button class="doctor-profile" onclick="ToPatients()">
            <img src="/CardioCompass/media/patients_symbol.svg">
            Patients
    </button>
    </div>
    <div>
        <button class="doctor-profile" onclick="Screening()">
            <img src="/CardioCompass/media/heart_screening_symbol.svg">
            Heart Screening
    </button>
    </div>
</div>

</section>
<footer class="footer">
    <div class="footer-top">
        <div class="footer-column">
            <a href="#" class="link">About Us</a>
            <a href="tel:+63 912 345 6789" class="link">Contact Us: +63 912 345 6789</a>
        </div>
        <div class="footer-column">
            <a href="/CardioCompass/terms&conditions.php" class="link">Terms & Conditions</a>
            <a href="/CardioCompass/privacy-policy.php" class="link">Privacy Policy</a>
        </div>
        <div class="footer-column">
            <a href="">FAQs</a>
            <a href="/CardioCompass/privacy-policy.php" class="link">Support: </a>
            <a href="mailto:cardiocompass@support.com" class="link">cardiocompass@support.com</a>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="social-icon">
            <a href="https://www.facebook.com/zorah.19" class="fa fa-facebook facebook link"></a>
            <a href="https://x.com/_IUofficial" class="fa fa-twitter twitter link"></a>
            <a href="https://www.instagram.com/pookie_bear_fanpage_/" class="fa fa-instagram instagram link"></a>
            <a href="https://www.linkedin.com" class="fa fa-linkedin linkedin link"></a>
        </div>
        <hr>
        <div class="copy-right">
            <p>&copy; 2024 CardioCompass. All rights reserved.</p>
        </div>
    </div>
</footer>

<body>
<script>
    let backToTopBtn = document.getElementById("backToTopBtn");
    window.onscroll = function () {
        scrollFunction();
    };
    function scrollFunction() {
        if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    }
    function scrollFunctionInfo() {
        const element = document.getElementById("info");
        element.scrollIntoView();
    }
    function redirectToDonate() {
        window.location.href = "/CardioCompass/customer-page/donation-page.php";
    }
    function ToPatients() {
        window.location.href = "/CardioCompass/employees-page/patient-list.php?EmployeeID=<?php echo $_SESSION['employee_id']; ?>";
    }
    function ToSchedule() {
        window.location.href = "/CardioCompass/employees-page/schedule-list.php?EmployeeID=<?php echo $_SESSION['employee_id']; ?>";
    }
    function Screening() {
        window.location.href = "/CardioCompass/employees-page/heart-disease-screening.php?EmployeeID=<?php echo $_SESSION['employee_id']; ?>";
    }
</script>
</body>
</html>