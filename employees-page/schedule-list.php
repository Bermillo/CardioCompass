<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CardioCompass/Styles/schedule-list-style.css">
<link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
<link rel="icon" type="image/png" href="/CardioCompass/media/cardio-compass-bg-logo.png">

<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Schedule List</title>
</head>

<body>
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
    <section>
        <button onclick="window.location.href='/CardioCompass/employees-page/employee-page-backup.php'" class='back'>Return to Home</button> 
        <!-- Sort By Dropdown -->
        <div class="sort-container">
            <form class="sort" method="get" action="schedule-list.php">
                <input type="hidden" name="EmployeeID" value="<?php echo urlencode($_GET['EmployeeID']); ?>">
                <label for="sort_by">Sort By:</label>
                <select name="sort_by" id="sort_by" onchange="this.form.submit()">
                    <option value="appointment_date" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'appointment_date') ? 'selected' : ''; ?>>Appointment Date</option>
                    <option value="appointment_status" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'appointment_status') ? 'selected' : ''; ?>>Appointment Status</option>
                </select>
            </form>
        </div>
        <div class="table1">
            <table border="1" width="1500">
                <thead>
                    <tr>
                        <th width="0.5">Patient ID</th>
                        <th width="500">Patient Name</th>
                        <th width="80">Appointment Date</th>
                        <th width="95">Appointment Time</th>
                        <th width="0.5">Appointment ID</th>
                        <th width="100">Appointment Status</th>
                        <th width="50">Scheduling</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Greedy sorting function for appointment date
                    function greedySortByAppointmentDate($appointments) {
                        $sortedAppointments = [];
                        while (count($appointments) > 0) {
                            $minDate = null;
                            $minIndex = -1;

                            // Select the minimum appointment date (greedy choice)
                            foreach ($appointments as $index => $appointment) {
                                if ($minDate === null || strtotime($appointment['appointment_date']) < strtotime($minDate)) {
                                    $minDate = $appointment['appointment_date'];
                                    $minIndex = $index;
                                }
                            }

                            // Move the selected appointment to the sorted array
                            $sortedAppointments[] = $appointments[$minIndex];
                            unset($appointments[$minIndex]); // Remove the selected appointment from the original array
                        }
                        return $sortedAppointments;
                    }
                    // Greedy sorting function for appointment status
                    function greedySortByAppointmentStatus($appointments) {
                        $statusOrder = ['Pending', 'Approved', 'Complete', 'Cancelled'];
                        $sortedAppointments = [];
                        while (count($appointments) > 0) {
                            $minStatus = null;
                            $minIndex = -1;

                            // Select the minimum appointment status (greedy choice)
                            foreach ($appointments as $index => $appointment) {
                                if ($minStatus === null || array_search($appointment['appointment_status'], $statusOrder) < array_search($minStatus, $statusOrder)) {
                                    $minStatus = $appointment['appointment_status'];
                                    $minIndex = $index;
                                }
                            }
                            // Move the selected appointment to the sorted array
                            $sortedAppointments[] = $appointments[$minIndex];
                            unset($appointments[$minIndex]); // Remove the selected appointment from the original array
                        }
                        return $sortedAppointments;
                    }

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "clinic_website";

                    $con = new mysqli($servername, $username, $password, $database);

                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    }

                    if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
                        $employeeID = filter_input(INPUT_GET, 'EmployeeID', FILTER_VALIDATE_INT);

                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id']) && isset($_POST['appointment_status'])) {
                            $appointmentID = (int)$_POST['appointment_id'];
                            $appointmentStatus = $_POST['appointment_status'];

                            // Updating appointment status
                            $updateSql = "UPDATE appointment_details SET appointment_status = ? WHERE appointment_id = ?";
                            $updateStmt = $con->prepare($updateSql);
                            $updateStmt->bind_param("si", $appointmentStatus, $appointmentID);
                            $updateStmt->execute();
                            $updateStmt->close();
                        }

                        if ($employeeID === false) {
                            echo "<tr><td colspan='7'>Invalid EmployeeID provided.</td></tr>";
                        } else {
                            $sql = "SELECT * FROM appointment_details
                                    JOIN patient_form ON appointment_details.patient_id = patient_form.patient_id
                                    WHERE appointment_details.EmployeeID = ?";

                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("i", $employeeID);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            $appointments = [];

                            while ($row = $result->fetch_assoc()) {
                                $appointments[] = $row;
                            }

                            // Sorting based on user selection
                            if (isset($_GET['sort_by'])) {
                                $sortBy = $_GET['sort_by'];

                                if ($sortBy == 'patient_name') {
                                    usort($appointments, function($a, $b) {
                                        return strcmp($a['first_name'] . " " . $a['middle_name'] . " " . $a['last_name'], $b['first_name'] . " " . $b['middle_name'] . " " . $b['last_name']);
                                    });
                                } elseif ($sortBy == 'appointment_date') {
                                    usort($appointments, function($a, $b) {
                                        return strtotime($a['appointment_date']) - strtotime($b['appointment_date']);
                                    });
                                } elseif ($sortBy == 'appointment_status') {
                                    usort($appointments, function($a, $b) {
                                        $statusOrder = ['pending', 'approved', 'complete', 'cancelled'];
                                        return array_search($a['appointment_status'], $statusOrder) - array_search($b['appointment_status'], $statusOrder);
                                    });
                                }
                            }

                            // Displaying sorted appointments
                            if (count($appointments) > 0) {
                                foreach ($appointments as $row) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['patient_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars(date('H:i', strtotime($row['appointment_time']))) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['appointment_id']) . "</td>";
                                    echo "<td>";

                                    echo "<form method='post' action='schedule-list.php?EmployeeID=" . htmlspecialchars($employeeID) . "'>";
                                    echo "<input type='hidden' name='appointment_id' value='" . htmlspecialchars($row['appointment_id']) . "'>";
                                    echo "<select name='appointment_status' onchange='this.form.submit()'>";
                                    echo "<option value='pending'" . ($row['appointment_status'] == 'pending' ? ' selected' : '') . ">Pending</option>";
                                    echo "<option value='approved'" . ($row['appointment_status'] == 'approved' ? ' selected' : '') . ">Approved</option>";
                                    echo "<option value='complete'" . ($row['appointment_status'] == 'complete' ? ' selected' : '') . ">Complete</option>";
                                    echo "<option value='cancelled'" . ($row['appointment_status'] == 'cancelled' ? ' selected' : '') . ">Cancelled</option>";
                                    echo "</select>";
                                    echo "</form>";
                                    echo "</td>";

                                    // Reschedule Button
                                    echo "<td><button class='reschedule-btn' data-appointment_id='" . htmlspecialchars($row['appointment_id']) . "' data-appointment_time='" . htmlspecialchars($row['appointment_time']) . "'>Reschedule</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No appointments found for Employee ID " . htmlspecialchars($employeeID) . ".</td></tr>";
                            }

                            $stmt->close();
                        }
                    } else {
                        echo "<tr><td colspan='7'>EmployeeID not provided.</td></tr>";
                    }

                    $con->close();
                ?>
                </tbody>
            </table>
        </div>
        <!-- Modal for rescheduling -->
        <div id="rescheduleModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="post" action="schedule-list.php?EmployeeID=<?php echo htmlspecialchars($employeeID); ?>">
                    <input type="hidden" id="appointment_id" name="appointment_id">
                    <label for="appointment_time">Appointment Time:</label>
                    <input type="time" id="appointment_time" name="appointment_time" required><br>
                    <input type="submit" class=reschedule-btn name="reschedule" value="Reschedule">
                </form>
            </div>
        </div>

        <?php
            // Handle reschedule request
            if (isset($_POST['reschedule'])) {
                $appointmentTime = $_POST['appointment_time'];
                $appointmentID = $_POST['appointment_id'];

                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "clinic_website";

                $con = new mysqli($servername, $username, $password, $database);

                if ($con->connect_error) {
                    die("Connection failed: " . $con->connect_error);
                }

                $sql = "UPDATE appointment_details SET appointment_time = ? WHERE appointment_id = ?";

                $stmt = $con->prepare($sql);
                $stmt->bind_param("si", $appointmentTime, $appointmentID);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<script>
                        alert('Appointment rescheduled successfully.');
                        document.querySelector('button[data-appointment_id=\"" . htmlspecialchars($appointmentID) . "\"]').closest('tr').querySelector('td:nth-child(4)').innerText = '" . htmlspecialchars($appointmentTime) . "';
                    </script>";
                } else {
                    echo "<script>alert('Failed to reschedule appointment.');</script>";
                }

                $stmt->close();
                $con->close();
            }
        ?>
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
</body>
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

// For Reschedule
document.addEventListener("DOMContentLoaded", function() {
    const rescheduleButtons = document.querySelectorAll('.reschedule-btn');

    const modal = document.getElementById("rescheduleModal");
    const closeModal = modal.querySelector(".close");

    rescheduleButtons.forEach(button => {
        button.addEventListener("click", function() {
            const appointmentID = button.getAttribute("data-appointment_id");
            const appointmentTime = button.getAttribute("data-appointment_time");

            document.getElementById("appointment_id").value = appointmentID;
            document.getElementById("appointment_time").value = appointmentTime;

            modal.style.display = "block";
        });
    });

    closeModal.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});
</script>
</html>
