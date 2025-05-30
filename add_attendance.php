<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ems");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data
$employee_id = $_POST['employee_id'];
$date = $_POST['date'];
$status = $_POST['status'];
$check_in = !empty($_POST['check_in']) ? $_POST['check_in'] : null;
$check_out = !empty($_POST['check_out']) ? $_POST['check_out'] : null;
$remarks = $_POST['remarks'] ?? null;

// Validate required fields
if (empty($employee_id) || empty($date) || empty($status)) {
    die("❌ Error: employee_id, date, and status are required.");
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, status, check_in, check_out, remarks) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $employee_id, $date, $status, $check_in, $check_out, $remarks);

// Execute
if ($stmt->execute()) {
    echo "✅ Attendance recorded successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}

// Close connection
$conn->close();
?>
