<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login-page.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_website";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$accountID = $_SESSION['AccountID'];
$query = "SELECT accdata.AccountEmail, accounts.AccountName, accounts.AccountType 
          FROM accdata 
          INNER JOIN accounts ON accdata.AccountID = accounts.AccountID 
          WHERE accounts.AccountID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $accountID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($employee['AccountType'] !== 'employee') {
    header('Location: ../login-page.php');
    exit;
}

// Fetch customer details
$customerQuery = "SELECT * FROM customer";
$customerResult = $conn->query($customerQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateCustomer'])) {
    $customerID = $_POST['customerID'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $customerPhone = $_POST['customerPhone'];

    $updateQuery = "UPDATE customer SET CustomerName=?, CustomerEmail=?, CustomerPhone=? WHERE CustomerID=?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $customerName, $customerEmail, $customerPhone, $customerID);
    $updateStmt->execute();

    header("Location: employee-page.php");
    exit;
}

// Fetch employee schedules
$scheduleQuery = "SELECT * FROM schedules WHERE EmployeeID = ?";
$scheduleStmt = $conn->prepare($scheduleQuery);
$scheduleStmt->bind_param("i", $accountID);
$scheduleStmt->execute();
$scheduleResult = $scheduleStmt->get_result();
$schedules = $scheduleResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Page</title>
    <link rel="stylesheet" href="../Styles/employee-page.css"> <!-- Adjust path as necessary -->
</head>

<body>
    <section>
        <div class="navigation-bar">
            <div class="logo-text">
                <img class="website-logo" src="/CardioCompass/media/cardio-compass-logo.png" alt="Logo">
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
        <div class="card-container">
            <div class="card-wrapper">
                <!-- Employee Information -->
                <h1>Welcome, <?php echo htmlspecialchars($employee['AccountName']); ?></h1>
                <p>Email: <?php echo htmlspecialchars($employee['AccountEmail']); ?></p>
                <p>Type: <?php echo htmlspecialchars($employee['AccountType']); ?></p>

                <!-- Schedule Information -->
                <h2>Schedule</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $schedule): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['ScheduleID']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['Date']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['Time']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['PatientName']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Update Customer Information -->
                <h2>Update Customer Information</h2>
                <form action="employee-page.php" method="post">
                    <input type="hidden" name="updateCustomer" value="1">
                    <label for="customerID">Customer ID:</label>
                    <input type="text" id="customerID" name="customerID" required>

                    <label for="customerName">Customer Name:</label>
                    <input type="text" id="customerName" name="customerName" required>

                    <label for="customerEmail">Customer Email:</label>
                    <input type="email" id="customerEmail" name="customerEmail" required>

                    <label for="customerPhone">Customer Phone:</label>
                    <input type="text" id="customerPhone" name="customerPhone" required>

                    <input type="submit" value="Update" class="submit-btn">
                </form>

                <!-- Customer Information -->
                <h2>Customer Information</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Customer Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($customer = $customerResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['CustomerID']); ?></td>
                                <td><?php echo htmlspecialchars($customer['CustomerName']); ?></td>
                                <td><?php echo htmlspecialchars($customer['CustomerEmail']); ?></td>
                                <td><?php echo htmlspecialchars($customer['CustomerPhone']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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
                <a href="\\CardioCompass\\terms&conditions.php" class="link">Terms & Conditions</a>
                <a href="\\CardioCompass\\privacy-policy.php" class="link">Privacy Policy</a>
            </div>
            <div class="footer-column">
                <a href="\\CardioCompass\\donate.php" class="link">Donate</a>
                <a href="mailto:info@cardiocompass.com" class="link">info@cardiocompass.com</a>
            </div>
        </div>
        <div class="footer-bottom">
            <hr>
            <p>&copy; 2024 CardioCompass. All rights reserved.</p>
        </div>
    </footer>
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

        function topFunction() {
            document.documentElement.scrollTop = 0;
        }

        function scrollFunctionInfo() {
            const element = document.getElementById("info");
            element.scrollIntoView();
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$scheduleStmt->close();
$customerStmt->close();
$conn->close();
?>