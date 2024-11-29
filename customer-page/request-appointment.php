<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CardioCompass/Styles/request-appointment-style.css">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
<link rel="icon" type="image/png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<title>Request Appointment</title>
</head>

<body>
<section>
    <div class="navigation-bar">
        <div onclick="redirecttoHome()" class="logo-text">
            <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="Logo">
            <a href="/CardioCompass/customer-page/welcome-page.php">CardioCompass</a>
        </div>
        <div class="user-side">
            <div class="dropdown">
                <button class="profile-btn">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
                <div class="dropdown-content">
                    <a href="/CardioCompass/account-page.php">Account</a>
                    <a href="/CardioCompass/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function redirecttoHome() {
        window.location.href = "/CardioCompass/customer-page/welcome-page.php";
    }

    window.onload = function() {
        document.querySelector('button[type="submit"]').onclick = function() {
            window.location.href = 'mailto:<?php echo htmlspecialchars($doctor['AccountEmail']); ?>';
        };
    };
</script>

<div class="background">
    <img src="\CardioCompass\Media\Landscape.svg">
</div>

<!-- Patient Form -->
<div class="form-container">
    <div class="patient-form1">
        <div class="column1">
            <h2>Patient Details</h2>
            <form id="appointmentForm" method="POST" action="process-appointment.php">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" required>

                <label for="middle-name">Middle Inital:</label>
                <input type="text" id="middle-name" name="middle_name">

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" required>

                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>

                <label>Sex:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="sex" value="male" required> Male
                    </label>
                    <label>
                        <input type="radio" name="sex" value="female"> Female
                    </label>
                </div>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
        </div>
    </div>
    <div class="patient-form2">
        <div class="column2">
            <h2>Appointment Details</h2>
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "clinic_website";

                    $conn = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }

                    // SELECTED DOCTOR DETAILS
                    if (isset($_GET['EmployeeID'])) {
                    $employeeId = (int)$_GET['EmployeeID'];

                    $sql = "SELECT * FROM accounts
                            JOIN employee ON accounts.AccountID = employee.AccountID 
                            JOIN employee_details ON employee.EmployeeID = employee_details.EmployeeID
                            JOIN accdata ON accounts.AccountID = accdata.AccountID
                            JOIN employee_schedule ON employee.EmployeeID = employee_schedule.EmployeeID
                            WHERE employee.EmployeeID = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $employeeId);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $doctor = $result->fetch_assoc();
                            echo '<div class="doctor">';
                            echo '<img src="' . htmlspecialchars($doctor['EmployeePicture']) . '" alt=""></img>'; 
                            echo '<p>Request Appointment with: Dr. ' . htmlspecialchars($doctor['AccountName']) . '<br>' . 
                                    'Doctor ID: ' . htmlspecialchars($doctor['EmployeeID']) . '<br>' . 
                                    'Specialty: ' . htmlspecialchars($doctor['EmployeeSpecialty']) . '<br>' . 
                                    'Room Number: ' . htmlspecialchars($doctor['RoomNumber']) . '<br>' . 
                                    'Availability: ' . htmlspecialchars($doctor['DayofWeek']) . ' from ' . 
                                    htmlspecialchars($doctor['StartTime']) . ' to ' . htmlspecialchars($doctor['EndTime']) . 
                                    '</p>';
                            echo '</div>';
                        } else {
                            echo "<p class='error-message'>Doctor not found.<br></p>";
                        }
                    } else {
                        echo "<p class='error-message'>Query execution failed: " . htmlspecialchars($stmt->error) . "<br></p>";
                    }

                    $stmt->close();
                    } else {
                    echo "<p class='error-message'>No Selected Doctor.<br></p>";
                    }

                    $conn->close();
                    ?>
                <label for="appointment-date"><h3>Appointment Date:</h3></label>
                <input type="date" id="appointment-date" name="appointment_date" required>
                <label for="appointment-time"><h3>Appointment Time:</h3></label>
                <input type="time" id="appointment-time" name="appointment_time" required>
                <input type="hidden" name="EmployeeID" value="<?php echo htmlspecialchars($employeeId); ?>">
                <button type="submit" onclick="window.location.href = 'mailto:<?php echo htmlspecialchars($doctor['AccountEmail']); ?>';">Schedule Appointment</button>
                <button type="button" onclick="cancelAppointment(event)">Cancel</button>
            </form>
            <script>
                // Get the current date
                const today = new Date();

                // Format the current date to yyyy-mm-dd (required format for <input type="date">)
                const formattedDate = today.toISOString().split('T')[0];

                // Set the min attribute to today's date to prevent past dates
                document.getElementById("appointment-date").setAttribute("min", formattedDate);

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
                    holidayDates.push(`${year}-${date}`);
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
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer" id="footer">
    <div class="footer-top">
        <div class="footer-column">
            <a href="#" class="link ">About Us</a>
            <a href="tel:+63 912 345 6789" class="link">Contact Us: +63 912 345 6789</a>
        </div>
        <div class="footer-column">
            <a href="\CardioCompass\terms&conditions.php" class="link">Terms & Conditions</a>
            <a href="\CardioCompass\privacy-policy.php" class="link">Privacy Policy</a>
        </div>
        <div class="footer-column">
            <a href="\CardioCompass\privacy-policy.php" class="link">FAQs</a>
            <a href="\CardioCompass\privacy-policy.php" class="link">Support: <a
                    href="mailto:cardiocompass@support.com">cardiocompass@support.com</a></a>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="divider social-media">
            <a href="https://www.facebook.com/DLSU.Dasmarinas" class="fa fa-facebook link"></a>
            <a href="https://x.com/DLSBrothersDSM" class="fa fa-twitter link"></a>
            <a href="https://www.instagram.com/dlsud_official?igsh=MTkwNDV6MjV0dzdsaw==" class="fa fa-instagram link"></a>
            <a href="https://www.linkedin.com/school/delasalleuniversitydasmarinas" class="fa fa-linkedin link"></a>
        </div>
        <hr>
        <div class="copy-right">
            <p>&copy; 2024 CardioCompass. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<script>
    // Function to handle cancel button click
    function cancelAppointment(event) {
        event.preventDefault();  // Prevent form submission
        redirectBack();          // Call the redirectBack function
    }

    // Redirect to available doctors page
    function redirectBack() {
        window.location.href = "/CardioCompass/customer-page/available-doctors.php";
    }
</script>
</body>

</body>
</html>