<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CardioCompass/Styles/admin-page.css">
    <link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Admin</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            //Add Customer Btns
            var addCustomerModal = document.getElementById("addCustomerModal");
            var addCustomerBtn = document.querySelectorAll(".addCustomerBtn");
            var addCustomerSpan = addCustomerModal.querySelector(".close");

            addCustomerBtn.forEach(function (btn) {
                btn.onclick = function () {
                    addCustomerModal.style.display = "block";
                };
            });

            addCustomerSpan.onclick = function () {
                addCustomerModal.style.display = "none";
            };

            //Add Doctor Btns
            var addDoctorModal = document.getElementById("addDoctorModal");
            var addDoctorBtns = document.querySelectorAll(".addDoctorBtn");
            var addDoctorSpan = addDoctorModal.querySelector(".close");

            addDoctorBtns.forEach(function (btn) {
                btn.onclick = function () {
                    addDoctorModal.style.display = "block";
                };
            });

            addDoctorSpan.onclick = function () {
                addDoctorModal.style.display = "none";
            };

            //Edit Btns
            var editAccModal = document.getElementById("editAccModal");
            var editAccSpan = editAccModal.querySelector(".close");
            var editBtns = document.querySelectorAll(".editBtn");

            var editDocModal = document.getElementById("editDocModal");
            var editDocSpan = editDocModal.querySelector(".close");
            var editDocBtns = document.querySelectorAll(".editDocBtn");

            for (var i = 0; i < editBtns.length; i++) {
                editBtns[i].onclick = function () {
                    var accountID = this.getAttribute("data-id");
                    var accountName = this.getAttribute("data-name");
                    var accountType = this.getAttribute("data-type");
                    var accountEmail = this.getAttribute("data-email");
                    var accountUsername = this.getAttribute("data-username");
                    var accountPassword = this.getAttribute("data-password");

                    document.getElementById("editAccountID").value = accountID;
                    document.getElementById("editAccountName").value = accountName;
                    document.getElementById("editAccountType").value = accountType;
                    document.getElementById("editAccountEmail").value = accountEmail;
                    document.getElementById("editAccountUsername").value = accountUsername;
                    document.getElementById("editAccountPassword").value = accountPassword;

                    editAccModal.style.display = "block";
                }
            }

            for (var i = 0; i < editDocBtns.length; i++) {
                editDocBtns[i].onclick = function () {
                    var employeeID = this.getAttribute("data-id");
                    var accountName = this.getAttribute("data-name");
                    var specialty = this.getAttribute("data-specialty");
                    var roomNumber = this.getAttribute("data-room");

                    document.getElementById("editEmployeeID").value = employeeID;
                    document.getElementById("editDoctorName").value = accountName;
                    document.getElementById("editDoctorSpecialty").value = specialty;
                    document.getElementById("editDoctorRoomNumber").value = roomNumber;

                    editDocModal.style.display = "block";
                }
            }

            editAccSpan.onclick = function () {
                editAccModal.style.display = "none";
            };

            editDocSpan.onclick = function () {
                editDocModal.style.display = "none";
            };

            window.onclick = function (event) {
                if (event.target == addCustomerModal) {
                    addCustomerModal.style.display = "none";
                }
                if (event.target == addDoctorModal) {
                    addDoctorModal.style.display = "none";
                }
                if (event.target == editAccModal) {
                    editAccModal.style.display = "none";
                }
                if (event.target == editDocModal) {
                    editDocModal.style.display = "none";
                }
            };

            //Del Btns
            var delBtns = document.querySelectorAll(".delAccBtn");
            var delDocBtns = document.querySelectorAll(".delDocBtn");

            for (var i = 0; i < delBtns.length; i++) {
                delBtns[i].onclick = function () {
                    var accountID = this.getAttribute("data-id");
                    if (confirm("Are you sure you want to delete this account?")) {
                        var form = document.createElement("form");
                        form.method = "post";
                        form.action = "admin-page.php";

                        var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "deleteAccountID";
                        input.value = accountID;
                        form.appendChild(input);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }

            for (var i = 0; i < delDocBtns.length; i++) {
                delDocBtns[i].onclick = function () {
                    var accountID = this.getAttribute("data-account-id");
                    var employeeID = this.getAttribute("data-doctor-id");
                    if (confirm("Are you sure you want to delete this doctor and their account?")) {
                        var form = document.createElement("form");
                        form.method = "post";
                        form.action = "admin-page.php";

                        var inputAccount = document.createElement("input");
                        inputAccount.type = "hidden";
                        inputAccount.name = "deleteAccountID";
                        inputAccount.value = accountID;
                        form.appendChild(inputAccount);

                        var inputDoctor = document.createElement("input");
                        inputDoctor.type = "hidden";
                        inputDoctor.name = "deleteDoctorID";
                        inputDoctor.value = employeeID;
                        form.appendChild(inputDoctor);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        });

        function filterAccounts() {
            var accountFilter = document.getElementById("accountFilter").value;
            var allAccountsTable = document.querySelector(".all-accounts");
            var doctorsTable = document.querySelector(".doctors");
            var customersTable = document.querySelector(".customers");

            allAccountsTable.style.display = "none";
            doctorsTable.style.display = "none";
            customersTable.style.display = "none";

            switch (accountFilter) {
                case "all":
                    allAccountsTable.style.display = "block";
                    break;
                case "doctor":
                    doctorsTable.style.display = "block";
                    break;
                case "customer":
                    customersTable.style.display = "block";
                    break;
                default:
                    break;
            }
        }

        function filterAccountsDoctor() {
            var accountFilter = document.getElementById("doctorFilter").value;
            var allAccountsTable = document.querySelector(".all-accounts");
            var doctorsTable = document.querySelector(".doctors");
            var customersTable = document.querySelector(".customers");

            allAccountsTable.style.display = "none";
            doctorsTable.style.display = "none";
            customersTable.style.display = "none";

            switch (accountFilter) {
                case "all":
                    allAccountsTable.style.display = "block";
                    break;
                case "doctor":
                    doctorsTable.style.display = "block";
                    break;
                case "customer":
                    customersTable.style.display = "block";
                    break;
                default:
                    break;
            }
        }

        function filterAccountsCustomer() {
            var accountFilter = document.getElementById("customerFilter").value;
            var allAccountsTable = document.querySelector(".all-accounts");
            var doctorsTable = document.querySelector(".doctors");
            var customersTable = document.querySelector(".customers");

            allAccountsTable.style.display = "none";
            doctorsTable.style.display = "none";
            customersTable.style.display = "none";

            switch (accountFilter) {
                case "all":
                    allAccountsTable.style.display = "block";
                    break;
                case "doctor":
                    doctorsTable.style.display = "block";
                    break;
                case "customer":
                    customersTable.style.display = "block";
                    break;
                default:
                    break;
            }
        }


        function refreshAllAccountsTable() {

            location.reload();
        }

    </script>


</head>

<body>
    <div class="navigation-bar">
        <div class="logo-text">
            <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="">
            <h3>AnchorMed</h3>
        </div>

    </div>

    <div class="container">
        <div class="table-container">
            <div class="all-accounts">
                <div class="title-container">
                    <h2>All Accounts</h2>
                    <div class="dropdown-container">
                        <label for="accountFilter">Filter:</label>
                        <select id="accountFilter" class="sort" onchange="filterAccounts()">
                            <option value="all">All Accounts</option>
                            <option value="doctor">Doctors</option>
                            <option value="customer">Customers</option>
                        </select>
                    </div>

                </div>
                <div class="scrollable-table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Account ID</th>
                                <th>Account Name</th>
                                <th>Account Type</th>
                                <th>Account Email</th>
                                <th>Account Username</th>
                                <th>Account Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con = mysqli_connect("localhost", "root", "", "clinic_website");
                            if (!$con) {
                                die("Connection Failed: " . mysqli_connect_error());
                            }

                            $result = getAllAccounts($con);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['AccountID'] . "</td>";
                                    echo "<td>" . $row['AccountName'] . "</td>";
                                    echo "<td>" . $row['AccountType'] . "</td>";
                                    echo "<td>" . $row['AccountEmail'] . "</td>";
                                    echo "<td>" . $row['AccountUsername'] . "</td>";
                                    echo "<td>" . $row['AccountPass'] . "</td>";
                                    echo "<td>
                                <button class ='editBtn' data-id='" . $row['AccountID'] . "' data-name='" . $row['AccountName'] . "' data-type='" . $row['AccountType'] . "' data-email='" . $row['AccountEmail'] . "' data-username='" . $row['AccountUsername'] . "' data-password='" . $row['AccountPass'] . "'>Edit</button>
                                <button class='delAccBtn' data-id='" . $row['AccountID'] . "'>Delete</button>
                            </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No accounts found</td></tr>";
                            }

                            mysqli_close($con);
                            ?>
                        </tbody>

                    </table>
                </div>

                <div class="button-container">
                    <button class="addCustomerBtn">Add Customer</button>
                    <button class="addDoctorBtn">Add Doctor</button>

                </div>
            </div><!--End of All Accounts table-->

            <div class="doctors">
                <div class="title-container">
                    <h2>Doctors</h2>
                    <div class="dropdown-container">
                        <label for="doctorFilter">Filter:</label>
                        <select id="doctorFilter" class="sort" onchange="filterAccountsDoctor()">
                            <option value="doctor">Doctors</option>
                            <option value="all">All Accounts</option>
                            <option value="customer">Customers</option>
                        </select>
                    </div>
                </div>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Doctor Name</th>
                            <th>Doctor Specialty</th>
                            <th>Room Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $con = mysqli_connect("localhost", "root", "", "clinic_website");
                        if (!$con) {
                            die("Connection Failed: " . mysqli_connect_error());
                        }

                        $result = getDoctors($con);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['EmployeeID'] . "</td>";
                                echo "<td>" . $row['AccountName'] . "</td>";
                                echo "<td>" . $row['EmployeeSpecialty'] . "</td>";
                                echo "<td>" . $row['RoomNumber'] . "</td>";
                                echo "<td>
                                <button class='editDocBtn' data-id='" . $row['EmployeeID'] . "' data-name='" . $row['AccountName'] . "' data-specialty='" . $row['EmployeeSpecialty'] . "' data-room='" . $row['RoomNumber'] . "'>Edit</button>
                                <button class='delDocBtn' data-account-id='" . $row['AccountID'] . "' data-doctor-id='" . $row['EmployeeID'] . "'>Delete</button>
                             </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No accounts found</td></tr>";
                        }

                        mysqli_close($con);
                        ?>
                    </tbody>
                </table>

                <div class="button-container">
                    <button class="addDoctorBtn">Add Doctor</button>

                </div>
            </div><!--End of Doctors table-->

            <div class="customers">
                <div class="title-container">
                    <h2>Customers</h2>
                    <div class="dropdown-container">
                        <label for="customerFilter">Filter:</label>
                        <select id="customerFilter" class="sort" onchange="filterAccountsCustomer()">
                            <option value="customer">Customers</option>
                            <option value="all">All Accounts</option>
                            <option value="doctor">Doctors</option>
                        </select>
                    </div>
                </div>
                <div class="scrollable-table">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Customer Email</th>
                                <th>Account Username</th>
                                <th>Account Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con = mysqli_connect("localhost", "root", "", "clinic_website");
                            if (!$con) {
                                die("Connection Failed: " . mysqli_connect_error());
                            }

                            $result = getCustomers($con);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['CustomerID'] . "</td>";
                                    echo "<td>" . $row['AccountName'] . "</td>";
                                    echo "<td>" . $row['AccountEmail'] . "</td>";
                                    echo "<td>" . $row['AccountUsername'] . "</td>";
                                    echo "<td>" . $row['AccountPass'] . "</td>";
                                    echo "<td>
                                <button class ='editBtn' data-id='" . $row['AccountID'] . "' data-name='" . $row['AccountName'] . "' data-type='" . $row['AccountType'] . "' data-email='" . $row['AccountEmail'] . "' data-username='" . $row['AccountUsername'] . "' data-password='" . $row['AccountPass'] . "'>Edit</button>
                                <button class='delAccBtn' data-id='" . $row['AccountID'] . "'>Delete</button>
                            </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No accounts found</td></tr>";
                            }

                            mysqli_close($con);
                            ?>
                        </tbody>

                    </table>
                </div>

                <div class="button-container">
                    <button class="addCustomerBtn">Add Customer</button>
                </div>
            </div>
            <!-- End of Customers Table -->
        </div>
    </div>


    <!-- Edit Account Modal -->
    <div id="editAccModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Account</h2>
            <form method="post" action="admin-page.php">
                <input type="hidden" name="accountID" id="editAccountID">
                <input type="text" name="accountName" id="editAccountName" placeholder="Account Name" required>
                <input type="hidden" name="accountType" id="editAccountType" placeholder="Account Type" required>
                <input type="email" name="accountEmail" id="editAccountEmail" placeholder="Account Email" required>
                <input type="text" name="accountUsername" id="editAccountUsername" placeholder="Account Username"
                    required>
                <input type="text" name="accountPassword" id="editAccountPassword" placeholder="Account Password"
                    required>
                <input type="submit" name="editAccount" value="Save Changes">
            </form>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div id="editDocModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Doctor</h2>
            <form method="post" action="admin-page.php">
                <input type="hidden" name="employeeID" id="editEmployeeID">
                <input type="text" name="accountName" id="editDoctorName" placeholder="Doctor Name" required>
                <input type="text" name="specialty" id="editDoctorSpecialty" placeholder="Specialty" required>
                <input type="number" name="roomNumber" id="editDoctorRoomNumber" placeholder="Room Number" required>
                <input type="submit" name="editDoctor" value="Save Changes">
            </form>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div id="addCustomerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Customer</h2>
            <form method="post" action="admin-page.php">
                <input type="text" name="customerName" placeholder="Customer Name" required>
                <input type="email" name="customerEmail" placeholder="Customer Email" required>
                <input type="text" name="customerUsername" placeholder="Customer Username" required>
                <input type="password" name="customerPassword" placeholder="Customer Password" required>
                <input type="submit" name="addCustomer" value="Add Customer">
            </form>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div id="addDoctorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Doctor</h2>
            <form method="post" action="admin-page.php">
                <input type="text" name="accountName" placeholder="Account Name" required>
                <input type="text" name="specialty" placeholder="Specialty" required>
                <input type="number" name="roomNumber" placeholder="Room Number" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="employee_username" placeholder="Username" required>
                <input type="password" name="employee_password" placeholder="Password" required>
                <input type="submit" name="addDoctor" value="Add Doctor">
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $con = mysqli_connect("localhost", "root", "", "clinic_website");
        if (!$con) {
            die("Connection Failed: " . mysqli_connect_error());
        }

        if (isset($_POST['addCustomer'])) {
            $customerName = $_POST['customerName'];
            $customerEmail = $_POST['customerEmail'];
            $customerUsername = $_POST['customerUsername'];
            $customerPassword = $_POST['customerPassword'];
            addCustomer($con, $customerName, $customerEmail, $customerUsername, $customerPassword);

        }

        if (isset($_POST['addDoctor'])) {
            $accountName = $_POST['accountName'];
            $specialty = $_POST['specialty'];
            $roomNumber = $_POST['roomNumber'];
            $email = $_POST['email'];
            $employee_username = $_POST['employee_username'];
            $employee_password = $_POST['employee_password'];
            addDoctor($con, $accountName, $specialty, $roomNumber, $email, $employee_username, $employee_password);
        }

        if (isset($_POST['editAccount'])) {
            $accountID = $_POST['accountID'];
            $accountName = $_POST['accountName'];
            $accountType = $_POST['accountType'];
            $accountEmail = $_POST['accountEmail'];
            $accountUsername = $_POST['accountUsername'];
            $accountPassword = $_POST['accountPassword'];
            editAccount($con, $accountID, $accountName, $accountType, $accountEmail, $accountUsername, $accountPassword);
        }

        if (isset($_POST['editDoctor'])) {
            $employeeID = $_POST['employeeID'];
            $accountName = $_POST['accountName'];
            $specialty = $_POST['specialty'];
            $roomNumber = $_POST['roomNumber'];
            editDoctor($con, $employeeID, $accountName, $specialty, $roomNumber);
        }

        if (isset($_POST['deleteAccountID'])) {
            $accountID = $_POST['deleteAccountID'];
            deleteAccount($con, $accountID);
        }

        if (isset($_POST['deleteAccountID']) && isset($_POST['deleteDoctorID'])) {
            $accountID = $_POST['deleteAccountID'];
            $employeeID = $_POST['deleteDoctorID'];
            deleteAccountAndDoctor($con, $accountID, $employeeID);
        }


        mysqli_close($con);

    }



    function getAllAccounts($con)
    {
        $sql = "SELECT Accounts.AccountID, Accounts.AccountName, Accounts.AccountType, AccData.AccountEmail, AccData.AccountUsername, AccData.AccountPass 
                FROM Accounts 
                INNER JOIN AccData ON Accounts.AccountID = AccData.AccountID
                ORDER BY Accounts.AccountID ASC";
        $result = mysqli_query($con, $sql);
        return $result;
    }

    function getDoctors($con)
    {
        $sql = "SELECT Employee.EmployeeID, Accounts.AccountName, Accounts.AccountID, Employee_Details.EmployeeSpecialty, Employee_Details.RoomNumber
               FROM Employee
               INNER JOIN Employee_Details ON Employee.EmployeeID = Employee_Details.EmployeeID
               INNER JOIN Accounts ON Employee.AccountID = Accounts.AccountID
               ORDER BY Accounts.AccountID ASC";
        $result = mysqli_query($con, $sql);
        return $result;
    }

    function getCustomers($con)
    {
        $sql = "SELECT Customer.CustomerID, Accounts.AccountName, Accounts.AccountType, Accounts.AccountID, AccData.AccountEmail, AccData.AccountUsername, AccData.AccountPass 
               FROM Customer
               INNER JOIN Accounts ON Customer.AccountID = Accounts.AccountID
               INNER JOIN AccData ON Customer.AccountID = AccData.AccountID
               ORDER BY Accounts.AccountID ASC";
        $result = mysqli_query($con, $sql);
        return $result;
    }

    function addCustomer($con, $customerName, $customerEmail, $customerUsername, $customerPassword)
    {
        $sql_accounts = "INSERT INTO Accounts (AccountName, AccountType) VALUES ('$customerName', 'customer')";
        if (mysqli_query($con, $sql_accounts)) {
            $accountID = mysqli_insert_id($con);
            $sql_customer = "INSERT INTO Customer (AccountID) VALUES ($accountID)";
            if (mysqli_query($con, $sql_customer)) {
                // Insert into AccData table
                $sql_accdata = "INSERT INTO AccData (AccountID, AccountEmail, AccountUsername, AccountPass) VALUES ('$accountID', '$customerEmail', '$customerUsername', '$customerPassword')";
                if (mysqli_query($con, $sql_accdata)) {
                    echo "New customer added successfully";
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
    function addDoctor($con, $accountName, $specialty, $roomNumber, $email, $employee_username, $employee_password)
    {
        $sql_accounts = "INSERT INTO Accounts (AccountName, AccountType) VALUES ('$accountName', 'employee')";
        if (mysqli_query($con, $sql_accounts)) {
            $accountID = mysqli_insert_id($con);
            $sql_employee = "INSERT INTO Employee (AccountID) VALUES ('$accountID')";
            if (mysqli_query($con, $sql_employee)) {
                $employeeID = mysqli_insert_id($con);
                // Insert into Employee_Details table
                $sql_employee_details = "INSERT INTO Employee_Details (EmployeeID, EmployeeSpecialty, RoomNumber) VALUES ('$employeeID', '$specialty', '$roomNumber')";
                if (mysqli_query($con, $sql_employee_details)) {
                    $sql_accdata = "INSERT INTO AccData (AccountID, AccountEmail, AccountUsername, AccountPass) VALUES ('$accountID', '$email', '$employee_username', '$employee_password')";
                    if (mysqli_query($con, $sql_accdata)) {
                        echo "New doctor added successfully";
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

    function deleteAccount($con, $accountID)
    {

        mysqli_begin_transaction($con);

        try {
            // Check if the account is associated with an employee
            $sql = "SELECT COUNT(*) AS EmployeeCount FROM Employee WHERE AccountID = $accountID";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $employeeCount = $row['EmployeeCount'];

            if ($employeeCount > 0) {

                $sql = "DELETE FROM Employee_Details WHERE EmployeeID IN (SELECT EmployeeID FROM Employee WHERE AccountID = $accountID)";
                mysqli_query($con, $sql);

                $sql = "DELETE FROM Employee WHERE AccountID = $accountID";
                mysqli_query($con, $sql);
            }

            $sql = "DELETE FROM AccData WHERE AccountID = $accountID";
            mysqli_query($con, $sql);

            $sql = "DELETE FROM Accounts WHERE AccountID = $accountID";
            mysqli_query($con, $sql);

            mysqli_commit($con);
            echo "Account deleted successfully";
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($con);
            echo "Error: " . $exception->getMessage();
        }
    }

    function deleteAccountAndDoctor($con, $accountID, $employeeID)
    {
        mysqli_begin_transaction($con);

        try {
            // Delete the doctor
            $sql_delete_doctor = "DELETE FROM Employee_Details WHERE EmployeeID = $employeeID";
            mysqli_query($con, $sql_delete_doctor);

            // Delete the account
            $sql_delete_account = "DELETE FROM Accounts WHERE AccountID = $accountID";
            mysqli_query($con, $sql_delete_account);

            mysqli_commit($con);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($con);
            echo "Error: " . $exception->getMessage();
        }
    }

    function editAccount($con, $accountID, $accountName, $accountType, $accountEmail, $accountUsername, $accountPassword)
    {
        $sql = "UPDATE Accounts SET AccountName='$accountName', AccountType='$accountType' WHERE AccountID='$accountID'";
        if (mysqli_query($con, $sql)) {

            $sql_update_accdata = "UPDATE AccData SET AccountEmail='$accountEmail', AccountUsername='$accountUsername', AccountPass='$accountPassword' WHERE AccountID='$accountID'";
            if (mysqli_query($con, $sql_update_accdata)) {
                echo "Account updated successfully";
            } else {
                echo "Error updating account: " . mysqli_error($con);
            }
        } else {
            echo "Error updating account: " . mysqli_error($con);
        }
    }

    function editDoctor($con, $employeeID, $accountName, $specialty, $roomNumber)
    {
        // Update the AccountName in the Accounts table
        $sql = "UPDATE Accounts 
                SET AccountName='$accountName' 
                WHERE AccountID = (SELECT AccountID FROM Employee WHERE EmployeeID='$employeeID')";

        if (mysqli_query($con, $sql)) {
            // Update the Employee_Details table
            $sql_update_employee_details = "UPDATE Employee_Details 
                                            SET EmployeeSpecialty='$specialty', RoomNumber='$roomNumber' 
                                            WHERE EmployeeID='$employeeID'";

            if (mysqli_query($con, $sql_update_employee_details)) {
                echo "Doctor details updated successfully";
            } else {
                echo "Error updating doctor details: " . mysqli_error($con);
            }
        } else {
            echo "Error updating account: " . mysqli_error($con);
        }
    }


    ?>
</body>

</html>