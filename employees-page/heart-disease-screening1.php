<?php
session_start();

if (isset($_POST['download_csv_with_prediction'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="submissions_with_prediction.csv"');

    $output = fopen('php://output', 'w');

    // Updated CSV headers to reflect new features
    $headers = [
        'Age', 'Sex', 'BMI', 'Smoking', 'Alcohol Drinking', 'Stroke', 'Physical Health',
        'Mental Health', 'Difficulty Walking', 'Age Category', 'Race', 'Diabetic', 'Physical Activity',
        'General Health', 'Sleep Time', 'Asthma', 'Kidney Disease', 'Skin Cancer', 'Prediction'
    ];
    fputcsv($output, $headers);

    // Write each submission data including prediction to the CSV
    foreach ($_SESSION['submissions'] as $submission) {
        $data = [
            $submission['age'],
            $submission['sex'],
            $submission['BMI'],
            $submission['Smoking'],
            $submission['AlcoholDrinking'],
            $submission['Stroke'],
            $submission['PhysicalHealth'],
            $submission['MentalHealth'],
            $submission['DiffWalking'],
            $submission['AgeCategory'],
            $submission['Race'],
            $submission['Diabetic'],
            $submission['PhysicalActivity'],
            $submission['GenHealth'],
            $submission['SleepTime'],
            $submission['Asthma'],
            $submission['KidneyDisease'],
            $submission['SkinCancer'],
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

    // Updated headers for submissions data CSV
    $headers = [
        'Age', 'Sex', 'BMI', 'Smoking', 'Alcohol Drinking', 'Stroke', 'Physical Health',
        'Mental Health', 'Difficulty Walking', 'Age Category', 'Race', 'Diabetic', 'Physical Activity',
        'General Health', 'Sleep Time', 'Asthma', 'Kidney Disease', 'Skin Cancer', 'Prediction'
    ];

    fputcsv($output, $headers);

    // Write submission data without prediction
    foreach ($_SESSION['submissions'] as $submission) {
        $data = [
            $submission['age'],
            $submission['sex'],
            $submission['BMI'],
            $submission['Smoking'],
            $submission['AlcoholDrinking'],
            $submission['Stroke'],
            $submission['PhysicalHealth'],
            $submission['MentalHealth'],
            $submission['DiffWalking'],
            $submission['AgeCategory'],
            $submission['Race'],
            $submission['Diabetic'],
            $submission['PhysicalActivity'],
            $submission['GenHealth'],
            $submission['SleepTime'],
            $submission['Asthma'],
            $submission['KidneyDisease'],
            $submission['SkinCancer'],
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

$bmi = $smoking = $alcoholdrinking = $stroke = $physicalhealth = $mentalhealth = $diffwalking = $sex = $agecategory = $race = $diabetic = $physicalactivity = $genhealth = $sleeptime = $asthma = $kidneydisease = $skincancer = "";
$output = $prediction = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['name'],$_POST['bmi'], $_POST['smoking'], $_POST['alcoholdrinking'], $_POST['stroke'], $_POST['physicalhealth'], $_POST['mentalhealth'], $_POST['diffwalking'], $_POST['sex'], $_POST['agecategory'], $_POST['race'], $_POST['diabetic'], $_POST['physicalactivity'], $_POST['genhealth'], $_POST['sleeptime'], $_POST['asthma'], $_POST['kidneydisease'], $_POST['skincancer'])) {
        $name = $_POST['name'];
        $bmi = $_POST['bmi'];
        $smoking = $_POST['smoking'];
        $alcoholdrinking = $_POST['alcoholdrinking'];
        $stroke = $_POST['stroke'];
        $physicalhealth = $_POST['physicalhealth'];
        $mentalhealth = $_POST['mentalhealth'];
        $diffwalking = $_POST['diffwalking'];
        $sex = $_POST['sex'];
        $agecategory = $_POST['agecategory'];
        $race = $_POST['race'];
        $diabetic = $_POST['diabetic'];
        $physicalactivity = $_POST['physicalactivity'];
        $genhealth = $_POST['genhealth'];
        $sleeptime = $_POST['sleeptime'];
        $asthma = $_POST['asthma'];
        $kidneydisease = $_POST['kidneydisease'];
        $skincancer = $_POST['skincancer'];

        $data = [
            'bmi' => $bmi,
            'smoking' => $smoking,
            'alcoholdrinking' => $alcoholdrinking,
            'stroke' => $stroke,
            'physicalhealth' => $physicalhealth,
            'mentalhealth' => $mentalhealth,
            'diffwalking' => $diffwalking,
            'sex' => $sex,
            'agecategory' => $agecategory,
            'race' => $race,
            'diabetic' => $diabetic,
            'physicalactivity' => $physicalactivity,
            'genhealth' => $genhealth,
            'sleeptime' => $sleeptime,
            'asthma' => $asthma,
            'kidneydisease' => $kidneydisease,
            'skincancer' => $skincancer
        ];

        $ch = curl_init('http://127.0.0.1:5000/predict1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);
        $echo = $response;

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $prediction = json_decode($response, true);
            $prediction = isset($prediction['prediction']) ? $prediction['prediction'] : 'No prediction available';
        }

        // Store the submitted data in the session
        $_SESSION['submissions'][] = [
            'name' => $name,
            'bmi' => $bmi,
            'smoking' => $smoking,
            'alcoholdrinking' => $alcoholdrinking,
            'stroke' => $stroke,
            'physicalhealth' => $physicalhealth,
            'mentalhealth' => $mentalhealth,
            'diffwalking' => $diffwalking,
            'sex' => $sex,
            'agecategory' => $agecategory,
            'race' => $race,
            'diabetic' => $diabetic,
            'physicalactivity' => $physicalactivity,
            'genhealth' => $genhealth,
            'sleeptime' => $sleeptime,
            'asthma' => $asthma,
            'kidneydisease' => $kidneydisease,
            'skincancer' => $skincancer,
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
        curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/predict1-from-file");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $data = array('file' => new CURLFile($fileTmpPath, 'text/csv', $fileName));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Adding headers to the request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: multipart/form-data',
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        
        // Initialize predictions to an empty array or null
        $predictions = null;

        if ($response === false) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Decode the JSON response to an array
            $responseDecoded = json_decode($response, true);

            // Check if the decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo 'Error: Invalid JSON response from API';
            } else {
                // Check if the response contains an error
                if (isset($responseDecoded['error'])) {
                    echo 'Error: ' . htmlspecialchars($responseDecoded['error']);
                } else {
                    // If predictions is an array, loop through and create table rows
                    if (is_array($responseDecoded)) {
                        $tableRows = '';
                        foreach ($responseDecoded as $row) {
                            // Ensure $row is an array and contains the expected keys
                            if (is_array($row) && isset($row['bmi'], $row['smoking'], $row['alcoholdrinking'], $row['stroke'], $row['physicalhealth'], $row['mentalhealth'], $row['diffwalking'], $row['sex'], $row['agecategory'], $row['race'], $row['diabetic'], $row['physicalactivity'], $row['genhealth'], $row['sleeptime'], $row['asthma'], $row['kidneydisease'], $row['skincancer'], $row['prediction'])) {
                                $tableRows .= '<tr>';
                                $tableRows .= '<td>' . htmlspecialchars($row['bmi']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['smoking']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['alcoholdrinking']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['stroke']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['physicalhealth']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['mentalhealth']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['diffwalking']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['sex']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['agecategory']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['race']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['diabetic']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['physicalactivity']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['genhealth']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['sleeptime']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['asthma']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['kidneydisease']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['skincancer']) . '</td>';
                                $tableRows .= '<td>' . htmlspecialchars($row['prediction']) . '</td>';
                                $tableRows .= '</tr>';
                            } else {
                                // Handle invalid row format
                                $tableRows .= '<tr><td colspan="19">Error: Invalid row format</td></tr>';
                            }
                        }
                    } else {
                        echo 'Error: Invalid response format';
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Screening Form</title>
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
    <div class="form-container">
        <h2>Health Screening Form</h2>
        <div class="form-scrollable">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="bmi">BMI:</label>
                    <input type="number" id="bmi" name="bmi" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="smoking">Smoking:</label>
                    <input type="radio" id="smoking_no" name="smoking" value="0" required>
                    <label for="smoking_no">No</label>
                    <input type="radio" id="smoking_yes" name="smoking" value="1" required>
                    <label for="smoking_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="alcoholdrinking">Alcohol Drinking:</label>
                    <input type="radio" id="alcoholdrinking_no" name="alcoholdrinking" value="0" required>
                    <label for="alcoholdrinking_no">No</label>
                    <input type="radio" id="alcoholdrinking_yes" name="alcoholdrinking" value="1" required>
                    <label for="alcoholdrinking_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="stroke">Stroke:</label>
                    <input type="radio" id="stroke_no" name="stroke" value="0" required>
                    <label for="stroke_no">No</label>
                    <input type="radio" id="stroke_yes" name="stroke" value="1" required>
                    <label for="stroke_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="physicalhealth">Physical Health (days):</label>
                    <input type="number" id="physicalhealth" name="physicalhealth" required>
                </div>

                <div class="form-group">
                    <label for="mentalhealth">Mental Health (days):</label>
                    <input type="number" id="mentalhealth" name="mentalhealth" required>
                </div>

                <div class="form-group">
                    <label for="diffwalking">Difficulty Walking:</label>
                    <input type="radio" id="diffwalking_no" name="diffwalking" value="0" required>
                    <label for="diffwalking_no">No</label>
                    <input type="radio" id="diffwalking_yes" name="diffwalking" value="1" required>
                    <label for="diffwalking_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <input type="radio" id="female" name="sex" value="1" required>
                    <label for="female">Female</label>
                    <input type="radio" id="male" name="sex" value="0" required>
                    <label for="male">Male</label>
                </div>

                <div class="form-group">
                    <label for="agecategory">Age Category:</label>
                    <select id="agecategory" name="agecategory" required>
                        <option value="0">18-24</option>
                        <option value="1">25-29</option>
                        <option value="2">30-34</option>
                        <option value="3">35-39</option>
                        <option value="4">40-44</option>
                        <option value="5">45-49</option>
                        <option value="6">50-54</option>
                        <option value="7">55-59</option>
                        <option value="8">60-64</option>
                        <option value="9">65-69</option>
                        <option value="10">70-74</option>
                        <option value="11">75-79</option>
                        <option value="12">80 or older</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="race">Race:</label>
                    <select id="race" name="race" required>
                        <option value="5">White</option>
                        <option value="2">Black</option>
                        <option value="1">Asian</option>
                        <option value="0">American Indian/Alaskan Native</option>
                        <option value="4">Other</option>
                        <option value="3">Hispanic</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="diabetic">Diabetic:</label>
                    <select id="diabetic" name="diabetic" required>
                        <option value="0">No</option>
                        <option value="2">Yes</option>
                        <option value="1">No, borderline diabetes</option>
                        <option value="3">Yes (during pregnancy)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="physicalactivity">Physical Activity:</label>
                    <input type="radio" id="physicalactivity_no" name="physicalactivity" value="0" required>
                    <label for="physicalactivity_no">No</label>
                    <input type="radio" id="physicalactivity_yes" name="physicalactivity" value="1" required>
                    <label for="physicalactivity_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="genhealth">General Health:</label>
                    <select id="genhealth" name="genhealth" required>
                        <option value="4">Very good</option>
                        <option value="1">Fair</option>
                        <option value="2">Good</option>
                        <option value="3">Poor</option>
                        <option value="0">Excellent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sleeptime">Sleep Time (hours):</label>
                    <select id="sleeptime" name="sleeptime" required>
                        <?php for ($i = 1; $i <= 24; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="asthma">Asthma:</label>
                    <input type="radio" id="asthma_no" name="asthma" value="0" required>
                    <label for="asthma_no">No</label>
                    <input type="radio" id="asthma_yes" name="asthma" value="1" required>
                    <label for="asthma_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="kidneydisease">Kidney Disease:</label>
                    <input type="radio" id="kidneydisease_no" name="kidneydisease" value="0" required>
                    <label for="kidneydisease_no">No</label>
                    <input type="radio" id="kidneydisease_yes" name="kidneydisease" value="1" required>
                    <label for="kidneydisease_yes">Yes</label>
                </div>

                <div class="form-group">
                    <label for="skincancer">Skin Cancer:</label>
                    <input type="radio" id="skincancer_no" name="skincancer" value="0" required>
                    <label for="skincancer_no">No</label>
                    <input type="radio" id="skincancer_yes" name="skincancer" value="1" required>
                    <label for="skincancer_yes">Yes</label>
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
                            <th>BMI</th>
                            <th>Smoking</th>
                            <th>Alcohol Drinking</th>
                            <th>Stroke</th>
                            <th>Physical Health</th>
                            <th>Mental Health</th>
                            <th>Difficulty Walking</th>
                            <th>Sex</th>
                            <th>Age Category</th>
                            <th>Race</th>
                            <th>Diabetic</th>
                            <th>Physical Activity</th>
                            <th>General Health</th>
                            <th>Sleep Time</th>
                            <th>Asthma</th>
                            <th>Kidney Disease</th>
                            <th>Skin Cancer</th>
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
                    <th>BMI</th>
                    <th>Smoking</th>
                    <th>Alcohol Drinking</th>
                    <th>Stroke</th>
                    <th>Physical Health</th>
                    <th>Mental Health</th>
                    <th>Difficulty Walking</th>
                    <th>Sex</th>
                    <th>Age Category</th>
                    <th>Race</th>
                    <th>Diabetic</th>
                    <th>Physical Activity</th>
                    <th>General Health</th>
                    <th>Sleep Time</th>
                    <th>Asthma</th>
                    <th>Kidney Disease</th>
                    <th>Skin Cancer</th>
                    <th>Prediction</th>
                </tr>
                <?php foreach ($_SESSION['submissions'] as $submission): ?>
                <tr>
                    <td><?php echo htmlspecialchars($submission['name']); ?></td>
                    <td><?php echo htmlspecialchars($submission['bmi']); ?></td>
                    <td><?php echo htmlspecialchars($submission['smoking']); ?></td>
                    <td><?php echo htmlspecialchars($submission['alcoholdrinking']); ?></td>
                    <td><?php echo htmlspecialchars($submission['stroke']); ?></td>
                    <td><?php echo htmlspecialchars($submission['physicalhealth']); ?></td>
                    <td><?php echo htmlspecialchars($submission['mentalhealth']); ?></td>
                    <td><?php echo htmlspecialchars($submission['diffwalking']); ?></td>
                    <td><?php echo htmlspecialchars($submission['sex']); ?></td>
                    <td><?php echo htmlspecialchars($submission['agecategory']); ?></td>
                    <td><?php echo htmlspecialchars($submission['race']); ?></td>
                    <td><?php echo htmlspecialchars($submission['diabetic']); ?></td>
                    <td><?php echo htmlspecialchars($submission['physicalactivity']); ?></td>
                    <td><?php echo htmlspecialchars($submission['genhealth']); ?></td>
                    <td><?php echo htmlspecialchars($submission['sleeptime']); ?></td>
                    <td><?php echo htmlspecialchars($submission['asthma']); ?></td>
                    <td><?php echo htmlspecialchars($submission['kidneydisease']); ?></td>
                    <td><?php echo htmlspecialchars($submission['skincancer']); ?></td>
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
</html></script>