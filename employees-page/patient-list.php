<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CardioCompass/Styles/patient-list-style.css">
    <link rel="icon" type="image/png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Patient List</title>
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
    <script>
        function submitSortForm() {
            document.getElementById("sortForm").submit();
        }
    </script>
    <section>
        <button onclick="window.location.href='/CardioCompass/employees-page/employee-page-backup.php'" class='back'>Return to Home</button> 

        <!-- Sort By Form -->
        <form class="sort" method="get" action="" id="sortForm">
            <input type="hidden" name="EmployeeID" value="<?php echo htmlspecialchars($_GET['EmployeeID']); ?>">
            <label for="sortOption">Sort by:</label>
            <select name="sortOption" id="sortOption" onchange="submitSortForm()">
            <option value="patient_id" <?php if (isset($_GET['sortOption']) && $_GET['sortOption'] == 'patient_id') echo 'selected'; ?>>Patient ID</option>
                <option value="name" <?php if (isset($_GET['sortOption']) && $_GET['sortOption'] == 'name') echo 'selected'; ?>>Name</option>
                <option value="age" <?php if (isset($_GET['sortOption']) && $_GET['sortOption'] == 'age') echo 'selected'; ?>>Age</option>
            </select>
        </form>
        <div class="table1">
            <table border="1" width="1500">
                <thead>
                    <tr>
                        <th width="500">Name</th>
                        <th width="80">Age</th>
                        <th width="95">Sex</th>
                        <th width="150">Phone</th>
                        <th width="200">Email</th>
                        <th width="100">Patient ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Merge Sort Function
                    function mergeSort(&$array, $key) {
                        $n = count($array);
                        if ($n <= 1) return;
                    
                        $mid = intdiv($n, 2);
                        $left = array_slice($array, 0, $mid);
                        $right = array_slice($array, $mid);
                    
                        mergeSort($left, $key);
                        mergeSort($right, $key);
                    
                        $array = merge($left, $right, $key);
                    }
                    
                    function merge($left, $right, $key) {
                        $sorted = [];
                        while (count($left) > 0 && count($right) > 0) {
                            // Check if the sorting key is patient_id or age and compare numerically
                            if ($key === 'patient_id' || $key === 'age') {
                                if ((int)$left[0][$key] <= (int)$right[0][$key]) {
                                    array_push($sorted, array_shift($left));
                                } else {
                                    array_push($sorted, array_shift($right));
                                }
                            } else {
                                // Default string comparison (e.g. name)
                                if (strcasecmp($left[0][$key], $right[0][$key]) <= 0) {
                                    array_push($sorted, array_shift($left));
                                } else {
                                    array_push($sorted, array_shift($right));
                                }
                            }
                        }
                        return array_merge($sorted, $left, $right);
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
                        $employeeID = (int)$_GET['EmployeeID'];

                        // Fetch the patient details
                        $sql = "SELECT * FROM patient_form
                                JOIN appointment_details ON patient_form.patient_id = appointment_details.patient_id
                                WHERE appointment_details.EmployeeID = ?";

                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("i", $employeeID);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $count = 1;
                            $patients = [];
                            while ($row = $result->fetch_assoc()) {
                                $row['name'] = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                                $patients[] = $row;
                            }

                            // Sort the patient list if a sort option is provided
                            if (isset($_GET['sortOption']) && !empty($_GET['sortOption'])) {
                                $sortKey = $_GET['sortOption'];
                                mergeSort($patients, $sortKey);
                            }

                            // Display Patients
                            if (!empty($patients)) {
                                $count = 1;
                                foreach ($patients as $patient) {
                                    echo "<tr>";
                                    echo "<td> $count. " . htmlspecialchars($patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name']) . "</td>";
                                    echo "<td> " . htmlspecialchars($patient['age']) . "</td>";
                                    echo "<td> " . htmlspecialchars($patient['sex']) . "</td>";
                                    echo "<td> " . htmlspecialchars($patient['phone']) . "</td>";
                                    echo "<td> " . htmlspecialchars($patient['email']) . "</td>";
                                    echo "<td> " . htmlspecialchars($patient['patient_id']) . "</td>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='8'>No patients found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No patients found for the selected Employee.</td></tr>";
                        }

                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='8'>EmployeeID not provided.</td></tr>";
                    }

                    $con->close();
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php
        if (isset($_POST['update_patient'])) {
            $patient_id = $_POST['patient_id'];
            $first_name = $_POST['first_name'];
            $middle_name = $_POST['middle_name'];
            $last_name = $_POST['last_name'];
            $age = $_POST['age'];
            $sex = $_POST['sex'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "clinic_website";

            $con = new mysqli($servername, $username, $password, $database);

            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            $sql = "UPDATE patient_form SET 
                    first_name = ?, 
                    middle_name = ?, 
                    last_name = ?, 
                    age = ?, 
                    sex = ?,
                    phone = ?, 
                    email = ? 
                    WHERE patient_id = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssissssi", $first_name, $middle_name, $last_name, $age, $sex, $phone, $email, $patient_id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Patient updated successfully');</script>";
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('Error updating patient');</script>";
            }
            
            $stmt->close();
            $con->close();
        }
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var modal = document.getElementById("editPatientModal");
            var editBtns = document.querySelectorAll(".editbutton");
            var closeBtn = document.querySelector(".close");

            editBtns.forEach(function(btn) {
                btn.onclick = function() {
                    var firstName = this.getAttribute("data-first_name");
                    var middleName = this.getAttribute("data-middle_name");
                    var lastName = this.getAttribute("data-last_name");
                    var age = this.getAttribute("data-age");
                    var sex = this.getAttribute("data-sex");
                    var phone = this.getAttribute("data-phone");
                    var email = this.getAttribute("data-email");
                    var patientId = this.getAttribute("data-patient_id");

                    document.getElementById("editFirstName").value = firstName;
                    document.getElementById("editMiddleName").value = middleName;
                    document.getElementById("editLastName").value = lastName;
                    document.getElementById("editAge").value = age;
                    document.getElementById("editSex").value = sex;
                    document.getElementById("editPhone").value = phone;
                    document.getElementById("editEmail").value = email;
                    document.getElementById("editPatientId").value = patientId;

                    modal.style.display = "block";
                };
            });

            closeBtn.onclick = function() {
                modal.style.display = "none";
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        });
    </script>

</body>

</html>

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