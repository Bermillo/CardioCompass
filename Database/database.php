<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "clinic_website";

$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
    /* $sql = "CREATE DATABASE clinic_website";
    if (mysqli_query($con, $sql)) {
        echo "Database created successfully with the name CardioCompass";
    } else {
        echo "Error creating database: " . mysqli_error($con);
    }*/
    $sql_acc = "CREATE TABLE Accounts (
        AccountID INT(10) AUTO_INCREMENT PRIMARY KEY,
        AccountName VARCHAR(100) NOT NULL,
        AccountType VARCHAR(50) NOT NULL,
        UNIQUE (AccountName, AccountType)
    )";

    // Create AccData table
    $sql_accdata = "CREATE TABLE AccData (
        AccountID INT(10) PRIMARY KEY,
        AccountEmail VARCHAR(100) NOT NULL,
        AccountUsername VARCHAR(100) NOT NULL,
        AccountPass VARCHAR(100) NOT NULL,
        FOREIGN KEY (AccountID) REFERENCES Accounts (AccountID)
    )";


    if (mysqli_query($con, $sql_acc) && mysqli_query($con, $sql_accdata)) {
        echo "Tables for Accounts and AccData successfully created <br>";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }

    // Create Admins table
    $sql_admin = "CREATE TABLE Admins  (
        AdminID INT(10) AUTO_INCREMENT PRIMARY KEY,
        AccountID INT(10),
        FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID) ON DELETE CASCADE
    )";

    // Create Employee table
    $sql_employee = "CREATE TABLE Employee  (
        EmployeeID INT(10) AUTO_INCREMENT PRIMARY KEY,
        AccountID INT(10),
        FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID) ON DELETE CASCADE
    )";

    // Create Customer table
    $sql_customer = "CREATE TABLE Customer  (
        CustomerID INT(10) AUTO_INCREMENT PRIMARY KEY,
        AccountID INT(10),
        FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID) ON DELETE CASCADE
    )";

    if (mysqli_query($con, $sql_admin) && mysqli_query($con, $sql_employee) && mysqli_query($con, $sql_customer)) {
        echo "Tables for Admins, Employee, and Customer successfully created<br>";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }

    // Create Employee_Details table
    $sql_employeedetails = "CREATE TABLE Employee_Details  (
        EmployeeID INT(10) PRIMARY KEY,
        EmployeeSpecialty VARCHAR(100) NOT NULL,
        RoomNumber INT NOT NULL,
        EmployeePicture VARCHAR(100),
        FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE
    )";

    // Create Employee_Schedule table
    $sql_employeeschedule = "CREATE TABLE Employee_Schedule  (
        EmployeeID INT(10),
        DayofWeek VARCHAR(100) NOT NULL,
        StartTime VARCHAR (100) NOT NULL,
        EndTime VARCHAR (100) NOT NULL,
        PRIMARY KEY (EmployeeID, DayofWeek),
        FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE
    )";

    if (mysqli_query($con, $sql_employeedetails) && mysqli_query($con, $sql_employeeschedule)) {
        echo "Tables for Employee_Details and Employee_Schedule successfully created<br>";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }

    // Create Patient_Form table
    $sql_patientform = "CREATE TABLE patient_form (
        patient_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        middle_name VARCHAR(20),
        last_name VARCHAR(100) NOT NULL,
        age INT(11) NOT NULL,
        sex ENUM('male', 'female', 'other') NOT NULL,
        birthday DATE NOT NULL,
        phone VARCHAR(11) NOT NULL,
        email VARCHAR(50) NOT NULL
    )";

    if (mysqli_query($con, $sql_patientform)) {
        echo "Table for patient_form successfully created<br>";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }

    // Create Appointment_Details Table
    $sql_appointmentdetails = "CREATE TABLE appointment_details (
        appointment_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        EmployeeID INT NOT NULL,
        appointment_date DATE NOT NULL,
        appointment_time TIME NOT NULL,
        appointment_status ENUM('pending', 'approved' ,'complete', 'cancelled') DEFAULT 'pending' NOT NULL,
        FOREIGN KEY (patient_id) REFERENCES patient_form(patient_id) ON DELETE CASCADE,
        FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE
    )";
    if (mysqli_query($con, $sql_appointmentdetails)) {
        echo "Table for appointment_details successfully created<br>";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }
    
}
mysqli_close($con);