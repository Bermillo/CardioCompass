<?php
session_start();

if (isset($_POST['download_csv_with_prediction'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="submissions_with_prediction.csv"');

    $output = fopen('php://output', 'w');

    // CSV headers
    $headers = [
        'Age', 'Sex', 'Chest Pain Type', 'Resting Blood Pressure', 'Cholesterol',
        'Fasting Blood Sugar', 'Resting ECG', 'Max Heart Rate', 'Exercise Angina',
        'ST Depression', 'ST Slope', 'Prediction'
    ];
    fputcsv($output, $headers);

    // Write each submission data including prediction to the CSV
    foreach ($_SESSION['submissions'] as $submission) {
        $data = [
            $submission['age'],
            $submission['sex'],
            $submission['chest_pain_type'],
            $submission['resting_blood_pressure'],
            $submission['cholesterol'],
            $submission['fasting_blood_sugar'],
            $submission['rest_ecg'],
            $submission['max_heart_rate'],
            $submission['exercise_angina'],
            $submission['ST_depression'],
            $submission['ST_slope'],
            isset($submission['prediction']) ? $submission['prediction'] : 'No prediction'
        ];
        fputcsv($output, $data);
    }

    fclose($output);
    exit; 
}

if (isset($_POST['download_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="submissions_data.csv"');

    $output = fopen('php://output', 'w');

    $headers = [
        'Name', 'Age', 'Sex', 'Chest Pain Type', 'Resting Blood Pressure',
        'Cholesterol', 'Fasting Blood Sugar', 'Resting ECG', 'Max Heart Rate',
        'Exercise Angina', 'Oldpeak', 'ST Slope', 'Prediction'
    ];

    fputcsv($output, $headers);

    foreach ($_SESSION['submissions'] as $submission) {
        $data = [
            $submission['name'],
            $submission['age'],
            $submission['sex'],
            $submission['chest_pain_type'],
            $submission['resting_blood_pressure'],
            $submission['cholesterol'],
            $submission['fasting_blood_sugar'],
            $submission['rest_ecg'],
            $submission['max_heart_rate'],
            $submission['exercise_angina'],
            $submission['ST_depression'],
            $submission['ST_slope'],
            isset($submission['prediction']) ? $submission['prediction'] : 'No prediction'
        ];
        fputcsv($output, $data);
    }

    fclose($output);
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
    }
}

$name = $age = $sex = $cp = $trestbps = $chol = $fbs = $restecg = $thalach = $exang = $oldpeak = $slope = "";
$output = $prediction = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['name'], $_POST['age'], $_POST['sex'], $_POST['cp'], $_POST['trestbps'], $_POST['chol'], $_POST['fbs'], $_POST['restecg'], $_POST['thalach'], $_POST['exang'], $_POST['oldpeak'], $_POST['slope'])) {

        $name = $_POST['name'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $cp = $_POST['cp'];
        $trestbps = $_POST['trestbps'];
        $chol = $_POST['chol'];
        $fbs = $_POST['fbs'];
        $restecg = $_POST['restecg'];
        $thalach = $_POST['thalach'];
        $exang = $_POST['exang'];
        $oldpeak = $_POST['oldpeak'];
        $slope = $_POST['slope'];

        $data = [
            'age' => $age,
            'sex' => $sex,
            'chest_pain_type' => $cp,
            'resting_blood_pressure' => $trestbps,
            'cholesterol' => $chol,
            'fasting_blood_sugar' => $fbs,
            'rest_ecg' => $restecg,
            'max_heart_rate' => $thalach,
            'exercise_angina' => $exang,
            'ST_depression' => $oldpeak,
            'ST_slope' => $slope
        ];

        $ch = curl_init('http://127.0.0.1:5000/predict');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
    
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $prediction = json_decode($response, true);
            $prediction = isset($prediction['prediction']) ? $prediction['prediction'] : 'No prediction available';
        }
        curl_close($ch);
        
        $_SESSION['submissions'][] = [
            'name' => $name,
            'age' => $age,
            'sex' => $sex,
            'chest_pain_type' => $cp,
            'resting_blood_pressure' => $trestbps,
            'cholesterol' => $chol,
            'fasting_blood_sugar' => $fbs,
            'rest_ecg' => $restecg,
            'max_heart_rate' => $thalach,
            'exercise_angina' => $exang,
            'ST_depression' => $oldpeak,
            'ST_slope' => $slope,
            'prediction' => $prediction
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['csv_file'])) {
    // Check if file is uploaded
    if ($_FILES['csv_file']['error'] == 0) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileSize = $_FILES['csv_file']['size'];
        $fileType = $_FILES['csv_file']['type'];

        // Read file content or send it to the Flask API for prediction
        $fileContents = file_get_contents($fileTmpPath);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/predict-from-file");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $data = array('file' => new CURLFile($fileTmpPath, 'text/csv', $fileName));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            echo "Error: Unable to get prediction from Flask API.";
        } else {
            // Process and display predictions in a table format
            $predictions = json_decode($response, true);
            $tableRows = '';
            foreach ($predictions as $row) {
                $tableRows .= '<tr>';
                $tableRows .= '<td>' . htmlspecialchars($row['age']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['sex']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['chest_pain_type']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['resting_blood_pressure']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['cholesterol']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['fasting_blood_sugar']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['rest_ecg']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['max_heart_rate']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['exercise_angina']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['ST_depression']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['ST_slope']) . '</td>';
                $tableRows .= '<td>' . htmlspecialchars($row['prediction']) . '</td>';
                $tableRows .= '</tr>';
            }
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Disease Screening</title>
    <link rel="stylesheet" href="/CardioCompass/Styles/employee-page.css">
    <link rel="stylesheet" href="/CardioCompass/Styles/heart-disease-screening.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400" rel="stylesheet">
    <link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <section>
        <div class="navigation-bar">
            <div class="logo-text">
                <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="Logo">
                <a href="/CardioCompass/employees-page/employee-page-backup.php">CardioCompass</a>
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
    <section class="'rtn-back">
    <button onclick="window.location.href='/CardioCompass/employees-page/employee-page-backup.php'" class='back'>Return to Home</button> 
    </section>
    <div class="form-container">
        <h2 class="HF">Heart Disease Screening Form</h2>
        <div class="form-scrollable">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="age">Age (years):</label>
                    <input type="number" id="age" name="age" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <input type="radio" id="male" name="sex" value="1" required>
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="sex" value="0" required>
                    <label for="female">Female</label>
                </div>

                <div class="form-group">
                    <label for="cp">Chest Pain Type:</label>
                    <input type="radio" id="typical_angina" name="cp" value="0" required>
                    <label for="typical_angina">Typical Angina</label>
                    <input type="radio" id="atypical_angina" name="cp" value="1" required>
                    <label for="atypical_angina">Atypical Angina</label>
                    <input type="radio" id="non_anginal_pain" name="cp" value="2" required>
                    <label for="non_anginal_pain">Non-Anginal Pain</label>
                    <input type="radio" id="asymptomatic" name="cp" value="3" required>
                    <label for="asymptomatic">Asymptomatic</label>
                </div>

                <div class="form-group">
                    <label for="trestbps">Resting Blood Pressure (mm Hg):</label>
                    <input type="number" id="trestbps" name="trestbps" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="chol">Cholesterol (mg/dl):</label>
                    <input type="number" id="chol" name="chol" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="fbs">Fasting Blood Sugar > 120 mg/dl:</label>
                    <input type="radio" id="fbs_true" name="fbs" value="1" required>
                    <label for="fbs_true">True</label>
                    <input type="radio" id="fbs_false" name="fbs" value="0" required>
                    <label for="fbs_false">False</label>
                </div>

                <div class="form-group">
                    <label for="restecg">Resting Electrocardiographic Results:</label>
                    <input type="radio" id="normal" name="restecg" value="1" required>
                    <label for="normal">Normal</label>
                    <input type="radio" id="abnormal" name="restecg" value="2" required>
                    <label for="abnormal">Abnormal</label>
                    <input type="radio" id="probable" name="restecg" value="3" required>
                    <label for="probable">Probable</label>
                </div>

                <div class="form-group">
                    <label for="thalach">Maximum Heart Rate Achieved (60 to 202):</label>
                    <input type="number" id="thalach" name="thalach" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="exang">Exercise Induced Angina:</label>
                    <input type="radio" id="exang_yes" name="exang" value="1" required>
                    <label for="exang_yes">Yes</label>
                    <input type="radio" id="exang_no" name="exang" value="0" required>
                    <label for="exang_no">No</label>
                </div>

                <div class="form-group">
                    <label for="oldpeak">ST Depression Induced by Exercise Relative to Rest:</label>
                    <input type="number" step="0.01" id="oldpeak" name="oldpeak" required>
                </div>

                <div class="form-group">
                    <label for="slope">Slope of the Peak Exercise ST Segment (-2.6 to 6.2):</label>
                    <input type="number" id="slope" name="slope" step="0.01" required>
                </div>
                <input type="submit" value="Submit"> 
            </form>
            <section>
                <div class="form-container">
                    <div class="form-scrollable">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="file-upload">Upload File:</label>
                                <div id="drop-area" class="file-upload-area">
                                    <input type="file" id="file-upload" name="csv_file" accept =".csv" style="display: none;" onchange="handleFileSelect(event)">
                                    <p>Drag & Drop a file here or click to select</p>
                                </div>
                                <p id="file-name"></p>
                            </div>
                            <input type="submit" value="Upload">
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php if (isset($tableRows)): ?>
        <form action="" method="POST">
            <div class="csv-table-container">
                <h2>CSV Data with Predictions</h2>
                <table id="resultTable">
                    <thead>
                        <tr>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Chest Pain Type</th>
                            <th>Resting BP</th>
                            <th>Cholesterol</th>
                            <th>Fasting Blood Sugar</th>
                            <th>Rest ECG</th>
                            <th>Max Heart Rate</th>
                            <th>Exercise Angina</th>
                            <th>ST Depression</th>
                            <th>ST Slope</th>
                            <th>Prediction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tableRows; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php endif; ?>
    <?php if (isset($_SESSION['csv_data']) && !empty($_SESSION['csv_data']['rows'])): ?>
        <div class="csv-table-container">
            <h2>Uploaded CSV Data</h2>
            <table class="csv-table">
                <thead>
                    <tr>
                        <?php foreach ($_SESSION['csv_data']['headers'] as $header): ?>
                            <th><?php echo htmlspecialchars($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['csv_data']['rows'] as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['submissions'])): ?>
        <div class="table-container">
            <h2>Submitted Data</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Chest Pain Type</th>
                    <th>Resting Blood Pressure</th>
                    <th>Cholesterol</th>
                    <th>Fasting Blood Sugar</th>
                    <th>Resting ECG</th>
                    <th>Max Heart Rate</th>
                    <th>Exercise Angina</th>
                    <th>Oldpeak</th>
                    <th>ST Slope</th>
                    <th>Prediction</th>
                </tr>
                <?php foreach ($_SESSION['submissions'] as $submission): ?>
                <tr>
                    <td><?php echo htmlspecialchars($submission['name']); ?></td>
                    <td><?php echo htmlspecialchars($submission['age']); ?></td>
                    <td><?php echo htmlspecialchars($submission['sex']); ?></td>
                    <td><?php echo htmlspecialchars($submission['chest_pain_type']); ?></td>
                    <td><?php echo htmlspecialchars($submission['resting_blood_pressure']); ?></td>
                    <td><?php echo htmlspecialchars($submission['cholesterol']); ?></td>
                    <td><?php echo htmlspecialchars($submission['fasting_blood_sugar']); ?></td>
                    <td><?php echo htmlspecialchars($submission['rest_ecg']); ?></td>
                    <td><?php echo htmlspecialchars($submission['max_heart_rate']); ?></td>
                    <td><?php echo htmlspecialchars($submission['exercise_angina']); ?></td>
                    <td><?php echo htmlspecialchars($submission['ST_depression']); ?></td>
                    <td><?php echo htmlspecialchars($submission['ST_slope']); ?></td>
                    <td>
                        <?php 
                            echo isset($submission['prediction']) ? htmlspecialchars($submission['prediction']) : 'No prediction';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <form method="post" action="">
                <input type="submit" name="download_csv" value="Download as CSV">
            </form>
        </div>
    <?php endif; ?>
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-upload');
        const fileNameDisplay = document.getElementById('file-name');

        dropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropArea.style.backgroundColor = '#e0e0e0';
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.style.backgroundColor = '#f9f9f9';
        });

        // Handle file drop
        dropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            const file = event.dataTransfer.files[0]; 
            fileInput.files = event.dataTransfer.files;
            updateFileName(file); 
            dropArea.style.backgroundColor = '#f9f9f9'; 
        });

        // Handle file selection via clicking
        function handleFileSelect(event) {
            const file = event.target.files[0];
            updateFileName(file);
        }

        // Update the displayed file name
        function updateFileName(file) {
            if (file) {
                fileNameDisplay.textContent = `Selected file: ${file.name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        }

        // Make the drop area clickable as well
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });
    </script>
</body>
</html>