<?php
// doctor-page.php

// Include database connection
include_once '../config/database.php';

// Fetch doctor data from the database
$query = "SELECT * FROM doctors";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Page</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Doctor Page</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['DoctorID'] . "</td>";
                    echo "<td>" . $row['DoctorName'] . "</td>";
                    echo "<td>" . $row['DoctorSpecialization'] . "</td>";
                    echo "<td>" . $row['DoctorContact'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No doctors found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>