<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "trustmed";


// Establish database connection
$connect = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve form data
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$department = $_POST['department'];
$date = $_POST['date'];
$age = $_POST['age'];

// Check if the maximum appointments for the department on the specified date have been reached
$maxAppointments = 8;

$sqlCountAppointments = "SELECT COUNT(*) AS appointmentCount FROM appointment WHERE department = ? AND date = ?";
$stmtCountAppointments = $connect->prepare($sqlCountAppointments);
$stmtCountAppointments->bind_param("ss", $department, $date);
$stmtCountAppointments->execute();
$resultCountAppointments = $stmtCountAppointments->get_result();
$countAppointments = $resultCountAppointments->fetch_assoc()['appointmentCount'];

if ($countAppointments >= $maxAppointments) {
    // Maximum appointments reached, display a popup message
    echo "<script>
            alert('Appointments for $department on $date are full. Appointment registration is closed for the day.');
            window.location.href = 'appointmentform.html'; // Redirect back to the original page
          </script>";
} else {
    // Generate a unique token for the appointment
    $randomNumber = sprintf("%04d", mt_rand(1, 9999));
    $token = $fullname . $randomNumber;

    // Prepare and execute SQL statement to insert data into the table
    $stmt = $connect->prepare("INSERT INTO appointment (token, fullname, phone, address, department, date, age) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $token, $fullname, $phone, $address, $department, $date, $age);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        // Get the ID of the last inserted appointment
        $appointmentId = $stmt->insert_id;

        // Redirect to the appointment details page with the generated token
        header("Location: appointment_details.php?id=$appointmentId&token=$token");
        exit();
    } else {
        // Display an error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close statements and connection
$stmtCountAppointments->close();
$connect->close();
?>
