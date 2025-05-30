<?php
// 1. Connect to MySQL
$conn = new mysqli("localhost", "root", "", "ems");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Get data from the form
$employee_id = $_POST['employeeId'];
$first_name = $_POST['firstName'];
$last_name = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$department = $_POST['department'];
$role = $_POST['role'];
$hire_date = $_POST['hireDate'];
$salary = $_POST['salary'];
$address = $_POST['address'];
$password = $_POST['password'];
$status = $_POST['status'];

// 3. Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 4. Insert into the database
$stmt = $conn->prepare("INSERT INTO employees 
(employee_id, first_name, last_name, email, phone, department, role, hire_date, salary, address, password_hash, status) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssdsss", $employee_id, $first_name, $last_name, $email, $phone, $department, $role, $hire_date, $salary, $address, $password_hash, $status);

if ($stmt->execute()) {
    echo "✅ Employee added successfully!";
} else {
    echo "❌ Error: " . $stmt->error;
}

$conn->close();
?>
