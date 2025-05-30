<?php
$conn = new mysqli("localhost", "root", "", "ems");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_date = $_GET['date'] ?? date('Y-m-d');
$department_filter = isset($_GET['department']) && !empty($_GET['department']) ? $_GET['department'] : null;
$status_filter = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;

$sql = "
SELECT a.*, 
       e.first_name, 
       e.last_name, 
       e.department 
FROM attendance a
JOIN employees e ON a.employee_id = e.employee_id
WHERE a.date = ?
";

$params = array($selected_date);
$types = "s";

// Add department filter if specified
if ($department_filter) {
    $sql .= " AND e.department = ?";
    $params[] = $department_filter;
    $types .= "s";
}

// Add status filter if specified
if ($status_filter) {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $check_in = $row['check_in'] ?? '-';
    $check_out = $row['check_out'] ?? '-';

    $work_hours = ($check_in != '-' && $check_out != '-') 
        ? round((strtotime($check_out) - strtotime($check_in)) / 3600, 2)
        : '0.0';

    echo "<tr>
        <td>{$row['employee_id']}</td>
        <td>{$row['first_name']} {$row['last_name']}</td>
        <td>{$row['department']}</td>
        <td>{$check_in}</td>
        <td>{$check_out}</td>
        <td>{$work_hours}</td>
        <td><span class='status status-{$row['status']}'>{$row['status']}</span></td>
        <td>
            <div class='table-actions'>
                <button class='action-btn edit-btn'>✏️</button>
                <button class='action-btn view-btn'>👁️</button>
            </div>
        </td>
    </tr>";
}

$conn->close();
?>
