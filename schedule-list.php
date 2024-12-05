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
            <div class="table1">
            <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Appointment ID</th>
                            <th>Appointment Status</th>
                            <th>Scheduling</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
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
                                // SQL query to fetch appointment details
                                $sql = "SELECT * FROM appointment_details
                                        JOIN patient_form ON appointment_details.patient_id = patient_form.patient_id
                                        WHERE appointment_details.EmployeeID = ?";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("i", $employeeID);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $appointments = [];
                                // Collect all appointments
                                while ($row = $result->fetch_assoc()) {
                                    $appointments[] = $row;
                                }
                                // Sort appointments by status first, then by date, then by time
                                usort($appointments, function($a, $b) {
                                    $statusOrder = ['pending', 'approved', 'complete', 'cancelled'];
                                    $statusComparison = array_search($a['appointment_status'], $statusOrder) <=> array_search($b['appointment_status'], $statusOrder);
                                    if ($statusComparison == 0) {
                                        $dateComparison = strtotime($a['appointment_date']) <=> strtotime($b['appointment_date']);
                                        if ($dateComparison == 0) {
                                            return strtotime($a['appointment_time']) <=> strtotime($b['appointment_time']);
                                        }
                                        return $dateComparison;
                                    }
                                    return $statusComparison;
                                });

                                // Now we apply the Greedy Algorithm to select non-overlapping appointments
                                $selectedAppointments = [];
                                $lastEndTime = "00:00:00";  // Start with a time that's before any appointment

                                // Greedy approach to select appointments
                                foreach ($appointments as $appointment) {
                                    $appointmentStart = $appointment['appointment_date'] . ' ' . $appointment['appointment_time'];  // Combine date and time
                                    $appointmentEnd = date("Y-m-d H:i:s", strtotime($appointmentStart) + 60 * 30);  // Example: Add 30 minutes to start time to get end time

                                    if (strtotime($appointmentStart) >= strtotime($lastEndTime)) {
                                        $selectedAppointments[] = $appointment;
                                        $lastEndTime = $appointmentEnd;
                                    }
                                }
                                ?>
                                <?php
                                // Display the selected appointments (non-overlapping)
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
                            
                                // Send appointment reschedule to patient
                                $sender_name = "CardioCompass";
                                $sender_email = "nfarice07@gmail.com";

                                $sql = "SELECT patient_form.email FROM appointment_details
                                    JOIN patient_form ON appointment_details.patient_id = patient_form.patient_id
                                    WHERE appointment_details.appointment_id = ?";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("i", $appointmentID);
                                $stmt->execute();
                                $stmt->bind_result($recipient_email);

                                if ($stmt->fetch()) {
                                    $subject = "Appointment Status";
                                    $body = "Good Day!\n\n" .
                                        "Your appointment status has been updated:\n\n" . 
                                        "Date: " . $row['appointment_date'] . "\n" .
                                        "Time: " . $row['appointment_time'] . "\n\n" .
                                        "Status: " . $appointmentStatus . "\n\n" .
                                        "Thank you for choosing CardioCompass!";

                                    if (mail($recipient_email, $subject, $body, "From: $sender_name <$sender_email>")) {
                                        echo "<script>alert('Email sent successfully.');</script>";
                                    } else {
                                        echo "<script>alert('Failed to send email.');</script>";
                                    }
                                }

                            }
                        } else {
                            echo "<tr><td colspan='7'>EmployeeID not provided.</td></tr>";
                        }
                    ?>
                    <script>
                        function filterAppointments(status) {
                            const rows = document.querySelectorAll('.appointment-row');
                            rows.forEach((row) => {
                                const rowStatus = row.getAttribute('data-status');
                                row.style.display = status === 'all' || rowStatus === status ? '' : 'none';
                            });
                        }
                    </script>
                    </tbody>
                </table>
            </div>
            <!-- Modal for rescheduling -->
            <div id="rescheduleModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form method="post" action="schedule-list.php?EmployeeID=<?php echo htmlspecialchars($employeeID); ?>">
                        <input type="hidden" id="appointment_id" name="appointment_id">
                        <label for="appointment_date">Appointment Date:</label>
                        <input type="date" id="appointment_date" name="appointment_date" required><br>
                        <label for="appointment_time">Appointment Time:</label>
                        <input type="time" id="appointment_time" name="appointment_time" required><br>
                        <input type="submit" name="reschedule" value="Reschedule">
                    </form>
                </div>
            </div>
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "clinic_website";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if (isset($_POST['reschedule']) && isset($_POST['appointment_id']) && isset($_POST['appointment_date']) && isset($_POST['appointment_time'])) {
                    $appointmentID = $_POST['appointment_id'];
                    $appointmentDate = $_POST['appointment_date'];
                    $appointmentTime = $_POST['appointment_time'];

                    // SQL to check if the same date and time already has an appointment, excluding the current appointment (by ID)
                    $query = "SELECT * FROM appointment_details WHERE appointment_date = ? AND appointment_time = ? AND appointment_id != ?";

                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param("ssi", $appointmentDate, $appointmentTime, $appointmentID);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // There is an overlapping appointment
                            echo "<script>alert('This date and time are already taken. Please choose a different time.');</script>";
                        } else {
                            // Proceed with the rescheduling logic (update appointment in database)
                            $updateQuery = "UPDATE appointment_details SET appointment_date = ?, appointment_time = ? WHERE appointment_id = ?";
                            if ($updateStmt = $conn->prepare($updateQuery)) {
                                $updateStmt->bind_param("ssi", $appointmentDate, $appointmentTime, $appointmentID);
                                if ($updateStmt->execute()) {
                                    echo "<script>alert('Appointment rescheduled successfully!');</script>";
                                                                    
                                // Send appointment reschedule to patient
                                $sender_name = "CardioCompass";
                                $sender_email = "nfarice07@gmail.com";

                                $sql = "SELECT patient_form.email FROM appointment_details
                                    JOIN patient_form ON appointment_details.patient_id = patient_form.patient_id
                                    WHERE appointment_details.appointment_id = ?";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("i", $appointmentID);
                                $stmt->execute();
                                $stmt->bind_result($recipient_email);

                                if ($stmt->fetch()) {
                                    $subject = "Appointment Rescheduled";
                                    $body = "Good Day!\n\n" .
                                        "Your appointment has been rescheduled to:\n\n" .
                                        "Date: " . $appointmentDate . "\n" .
                                        "Time: " . $appointmentTime . "\n\n" .
                                        "Thank you for choosing CardioCompass!";

                                    if (mail($recipient_email, $subject, $body, "From: $sender_name <$sender_email>")) {
                                        echo "<script>alert('Email sent successfully.');</script>";
                                    } else {
                                        echo "<script>alert('Failed to send email.');</script>";
                                    }
                                }

                                } else {
                                    echo "<script>alert('Failed to update appointment.');</script>";
                                }
                            }
                        }
                        $stmt->close();
                    }
                    $conn->close();
                }
                ?>
        </section>
        <script>
            // Get the current date
            const today = new Date();

            // Format the current date to yyyy-mm-dd (required format for <input type="date">)
            const formattedDate = today.toISOString().split('T')[0];

            // Set the min attribute to today's date to prevent past dates
            document.getElementById("appointment_date").setAttribute("min", formattedDate);

            // List of fixed holidays (same date each year)
            const fixedHolidays = [
            '01-01', // New Year's Day (January 1)
            '11-30',
            '12-25', // Christmas Day (December 25)
            // Add other fixed holidays here
            ];

            // Function to calculate Easter date (for floating holidays)
            function calculateEaster(year) {
            const f = Math.floor(year / 100);
            const g = Math.floor(year % 19);
            const c = Math.floor((f - 17) / 25);
            const x = Math.floor(f / 4);
            const z = Math.floor((f + 1) / 4);
            const d = Math.floor((19 * g + f - x - z) % 30);
            const e = Math.floor((2 * c + 2 * x + 4 * z + 6 * d) % 7);
            const date = 22 + d + e;
            // Easter Sunday is either March or April
            return new Date(year, 2, date).toISOString().split('T')[0]; // Return in yyyy-mm-dd format
            }
            // List of floating holidays (calculated based on the current year)
            function getFloatingHolidays(year) {
            const floatingHolidays = [
                calculateEaster(year), // Easter Sunday
                getLaborDay(year),     // Labor Day (First Monday in September)
            ];
            return floatingHolidays;
            }
            // Get the date of Labor Day (First Monday in September)
            function getLaborDay(year) {
            const date = new Date(year, 8, 1); // September 1st
            const dayOfWeek = date.getDay();
            const daysUntilMonday = (8 - dayOfWeek) % 7;
            date.setDate(1 + daysUntilMonday); // Adjust to the first Monday in September
            return date.toISOString().split('T')[0]; // Return in yyyy-mm-dd format
            }

            // List of already booked dates (YYYY-MM-DD format)
            const bookedDates = [
            '2024-12-10', // Example: Patient already booked on this date
            '2024-12-12', // Another booked date
            '2024-12-15', // Another booked date
            // Add more booked dates here as needed
            ];
            // Get the current year and generate the holidays for that year
            const currentYear = today.getFullYear();
            const holidays = generateHolidays(currentYear);
            // Combine fixed and floating holidays
            function generateHolidays(year) {
            const holidayDates = [];
            // Add fixed holidays (format YYYY-MM-DD)
            fixedHolidays.forEach(date => {
                holidayDates.push('${year}-${date}');
            });
            // Add floating holidays (e.g., Easter, Labor Day)
            const floatingHolidays = getFloatingHolidays(year);
            floatingHolidays.forEach(holiday => {
                holidayDates.push(holiday);
            });
            return holidayDates;
            }

            // Disable holidays and booked dates
            document.getElementById("appointment-date").addEventListener("input", function() {
            const selectedDate = this.value;
            // Check if selected date is a holiday
            if (holidays.includes(selectedDate)) {
                alert("The selected date is a holiday. Please choose another date.");
                this.value = '';  // Reset the date input field if a holiday is selected
                return;
            }
            // Check if selected date is already booked
            if (bookedDates.includes(selectedDate)) {
                alert("The selected date is already booked. Please choose another date.");
                this.value = '';  // Reset the date input field if already booked
            }
            });

            // Disable unavailable dates on the calendar (holidays and booked dates)
            function disableUnavailableDates() {
            const inputField = document.getElementById('appointment-date');
            const unavailableDates = holidays.concat(bookedDates);

            // Disable each unavailable date
            unavailableDates.forEach(date => {
                const dateOption = document.createElement('option');
                dateOption.value = date;
                dateOption.disabled = true;
                // We can't directly disable the input, so we show an alert if a user selects a date that is in the list.
            });
            }
            // Initialize the unavailable dates disabling
            disableUnavailableDates();
        </script>
    </body>
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-column">
                <a href="#" class="link">About Us</a>
                <a href="tel:+63 912 345 6789" class="link">Contact Us: +63 912 345 6789</a>
            </div>
            <div class="footer-column">
                <a href="\CardioCompass\terms&conditions.php" class="link">Terms & Conditions</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Privacy Policy</a>
            </div>
            <div class="footer-column">
                <a href="">FAQs</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Support: <a
                        href="mailto:anchormed@support.com">cardiocompass@support.com</a></a>
            </div>

        </div>
        </div>
        <div class="footer-bottom">
            <div class="social-icon">
                <a href="https://www.facebook.com/DLSU.Dasmarinas" class="fa fa-facebook facebook link"></a>
                <a href="https://x.com/DLSBrothersDSM" class="fa fa-twitter twitter link"></a>
                <a href="https://www.instagram.com/dlsud_official?igsh=MTkwNDV6MjV0dzdsaw==" class="fa fa-instagram instagram link"></a>
                <a href="https://www.linkedin.com/school/delasalleuniversitydasmarinas" class="fa fa-linkedin linkedin link"></a>
            </div>
            <hr>
            <div class="copy-right">
                <p>&copy; 2024 CardioCompass. All rights reserved.</p>
            </div>
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
    function scrollFunctionInfo() {
        const element = document.getElementById("info");
        element.scrollIntoView();
    }
    function redirectToDonate() {
        window.location.href = "/CardioCompass/customer-page/donation-page.php";
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