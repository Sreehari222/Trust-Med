<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .details {
            margin-top: 20px;
        }

        .details p {
            margin: 10px 0;
        }

        #downloadBtn,
        #backBtn {
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #downloadBtn {
            background-color: #4CAF50;
            color: white;
        }

        #backBtn {
            background-color: #2196F3;
            color: white;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Appointment Details</h1>

    <div class="details">
        <?php
        // Database connection details
        $host = "localhost";
        $username = "root";
        $password = "root";
        $database = "krishna";

        // Create a connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the current ID (assuming it's passed through the URL)
        $currentID = isset($_GET['id']) ? $_GET['id'] : 1;

        // Fetch data from the database based on the current ID
        $sql = "SELECT * FROM appoinmentd WHERE id = $currentID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $appointmentDetails = $result->fetch_assoc();

            echo "<p><strong>Full Name:</strong> {$appointmentDetails['fullname']}</p>";
            echo "<p><strong>Phone:</strong> {$appointmentDetails['phone']}</p>";
            echo "<p><strong>Address:</strong> {$appointmentDetails['address']}</p>";
            echo "<p><strong>Department:</strong> {$appointmentDetails['department']}</p>";
            echo "<p><strong>Date:</strong> {$appointmentDetails['date']}</p>";
            echo "<p><strong>Age:</strong> {$appointmentDetails['age']}</p>";
            echo "<p><strong>Token ID:</strong> {$appointmentDetails['token']}</p>";
        } else {
            echo "No records found";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>

    <button id="downloadBtn">Download as PDF</button>
    <button id="backBtn" onclick="goBack()">Back</button>
</div>

<footer>
    <p>&copy; 2023 Your Clinic Name. All rights reserved.</p>
</footer>

<!-- Include html2pdf.js -->
<script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

<script>
    // Add event listener to the download button
    document.getElementById('downloadBtn').addEventListener('click', function () {
        // Use html2pdf library to generate and download the PDF
        html2pdf(document.body);
    });

    // JavaScript function to go back
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
