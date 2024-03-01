<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "krishna";

// Establish database connection
$connect = mysqli_connect($host, $username, $password, $database) or die("Connection Failed");

// Retrieve form data
$fullname = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$department = $_POST['department'];
$date = $_POST['date'];
$age = $_POST['age'];

// Check if the maximum appointments for the department on the specified date have been reached
$maxAppointments = 8;

$sqlCountAppointments = "SELECT COUNT(*) AS appointmentCount FROM appoinmentd WHERE department = ? AND date = ?";
$stmtCountAppointments = $connect->prepare($sqlCountAppointments);
$stmtCountAppointments->bind_param("ss", $department, $date);
$stmtCountAppointments->execute();
$resultCountAppointments = $stmtCountAppointments->get_result();
$rowCountAppointments = $resultCountAppointments->fetch_assoc();
$countAppointments = $rowCountAppointments['appointmentCount'];

if ($countAppointments >= $maxAppointments) {
    // Maximum appointments reached, display a message
    echo "<script>alert('Appointments for $department on $date are full. Appointment registration is closed for the day.'); window.history.back();</script>";
} else {
    // Generate a unique token for the appointment
    $randomNumber = sprintf("%04d", mt_rand(1, 9999));
    $token = $username . $randomNumber;

    // Prepare and execute SQL statement to insert data into the table
    $stmt = $connect->prepare("INSERT INTO appoinmentd (token, fullname, phone, address, department, date, age) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }
}

// Close statements and connection
$stmt->close();
$stmtCountAppointments->close();
$connect->close();
?>
