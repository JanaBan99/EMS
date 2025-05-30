<?php
$conn = new mysqli("localhost", "root", "", "ems");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['employee_id']}</td>
            <td>{$row['first_name']} {$row['last_name']}</td>
            <td>{$row['department']}</td>
            <td>{$row['role']}</td>
            <td>{$row['hire_date']}</td>
            <td><span class='status status-{$row['status']}'>{$row['status']}</span></td>
            <td>
                <div class='table-actions'>
                    <button class='action-btn view-btn'>👁️</button>
                    <button class='action-btn edit-btn'>✏️</button>
                    <button class='action-btn delete-btn'>🗑️</button>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No employees found</td></tr>";
}

$conn->close();
?>
