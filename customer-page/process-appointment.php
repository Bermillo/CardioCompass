<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "clinic_website";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if (isset($_POST['first_name']) && isset($_POST['EmployeeID'])) {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $age = (int)$_POST['age'];
    $sex = $_POST['sex'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $EmployeeID = (int)$_POST['EmployeeID'];
    
    // Insert patient data
    $patient_stmt = $conn->prepare("INSERT INTO patient_form (first_name, middle_name, last_name, age, sex, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $patient_stmt->bind_param("sssisss", $first_name, $middle_name, $last_name, $age, $sex, $phone, $email);
    
    if ($patient_stmt->execute()) {
        $patient_id = $patient_stmt->insert_id;
        
        // Insert appointment data into the appointment_details table
        $appointment_stmt = $conn->prepare("INSERT INTO appointment_details (patient_id, EmployeeID, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $appointment_stmt->bind_param("iiss", $patient_id, $EmployeeID, $appointment_date, $appointment_time);
        
        if ($appointment_stmt->execute()) {
            $message = "Thank You!";
        } else {
            $message = "Error inserting into appointment_details: " . htmlspecialchars($appointment_stmt->error);
        }
        
        $appointment_stmt->close();
    } else {
        $message = "Error inserting into patient_form: " . htmlspecialchars($patient_stmt->error);
    }
    
    $patient_stmt->close();
} else {
    $message = "Please provide all required fields, including EmployeeID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            background-image: url('/CardioCompass/Media/CheckUp_bg.svg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .container img {
            width: 100px;
            margin-top: -80px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: black;
            margin-top: 10px;
            margin-bottom: -10px;
        }
        .container p {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: rgb(1, 45, 97);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: rgb(10, 66, 138);
        }
    </style>
</head>
<body>

<div class="container">
    <img src="\CardioCompass\Media\check.svg">
    <h1><?php echo htmlspecialchars($message); ?></h1>
    <p>Your appointment has been successfully scheduled.</p>
    <a href="/CardioCompass/home-page.php" class="btn">Go back to Home page</a>
</div>

</body>
</html>
