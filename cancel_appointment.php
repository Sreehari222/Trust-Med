<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "krishna";

// Establish database connection
$connect = mysqli_connect($host, $username, $password, $database) or die("Connection Failed");

// Check if the action and token are set in the POST data
if (isset($_POST['action']) && isset($_POST['rescheduleToken'])) {
    $action = $_POST['action'];
    $token = $_POST['rescheduleToken'];

    // Check if the token exists in the database
    $checkTokenQuery = "SELECT * FROM appoinmentd WHERE token = ?";
    $checkTokenStmt = $connect->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("s", $token);
    $checkTokenStmt->execute();
    $checkTokenResult = $checkTokenStmt->get_result();

    if ($checkTokenResult->num_rows > 0) {
        // Token found, process the action
        if ($action === 'cancel') {
            // Delete the appointment
            $deleteAppointmentQuery = "DELETE FROM appoinmentd WHERE token = ?";
            $deleteAppointmentStmt = $connect->prepare($deleteAppointmentQuery);
            $deleteAppointmentStmt->bind_param("s", $token);
            $deleteAppointmentStmt->execute();

            if ($deleteAppointmentStmt->affected_rows > 0) {
                // Appointment canceled successfully
                echo "<script>alert('Appointment canceled successfully.'); window.location.href = document.referrer;</script>";
            } else {
                // Failed to cancel appointment
                echo "<script>alert('Failed to cancel appointment.'); window.location.href = document.referrer;</script>";
            }

            $deleteAppointmentStmt->close();
        } elseif ($action === 'reschedule') {
            // Retrieve form data for rescheduling
            $newDate = $_POST['newDate'];
            $newDepartment = $_POST['newDepartment'];

            // Check if the new date is not empty
            if (!empty($newDate)) {
                // Update the department and date
                $updateDepartmentQuery = "UPDATE appoinmentd SET department = ?, date = ? WHERE token = ?";
                $updateDepartmentStmt = $connect->prepare($updateDepartmentQuery);
                $updateDepartmentStmt->bind_param("sss", $newDepartment, $newDate, $token);
                $updateDepartmentStmt->execute();

                if ($updateDepartmentStmt->affected_rows > 0) {
                    // Department changed successfully
                    echo "<script>alert('Appointment rescheduled successfully.'); window.location.href = document.referrer;</script>";
                } else {
                    // Failed to reschedule appointment
                    echo "<script>alert('Failed to reschedule appointment.'); window.location.href = document.referrer;</script>";
                }

                $updateDepartmentStmt->close();
            } else {
                // New date is empty
                echo "<script>alert('Please enter a valid date for rescheduling.'); window.history.back();</script>";
            }
        } else {
            // Invalid action
            echo "<script>alert('Invalid action.'); window.history.back();</script>";
        }
    } else {
        // Token not found
        echo "<script>alert('Token not found. Please check the token and try again.'); window.history.back();</script>";
    }

    $checkTokenStmt->close();
} else {
    // Action or rescheduleToken not set in the POST data
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}

$connect->close();
?>
