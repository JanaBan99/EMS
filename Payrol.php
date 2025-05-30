<?php
session_start();
require_once 'auth_utils.php';
require_login();
require_role('admin');

// Page title
$title = "Payroll Management";

// Database connection
require_once 'db_connection.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? '';
    $pay_period_start = $_POST['pay_period_start'] ?? '';
    $pay_period_end = $_POST['pay_period_end'] ?? '';
    $bonuses = $_POST['bonuses'] ?? 0;
    $deductions = $_POST['deductions'] ?? 0;
    
    try {
        // Get employee details
        $stmt = $conn->prepare("SELECT salary FROM employees WHERE employee_id = ?");
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Employee not found");
        }
        
        $employee = $result->fetch_assoc();
        $basic_salary = $employee['salary'];
        $net_salary = $basic_salary + $bonuses - $deductions;
        
        // Insert payroll record
        $stmt = $conn->prepare("INSERT INTO payroll (employee_id, pay_period_start, pay_period_end, 
                              basic_salary, bonuses, deductions, net_salary, payment_date)
                              VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("sssdddd", $employee_id, $pay_period_start, $pay_period_end, 
                         $basic_salary, $bonuses, $deductions, $net_salary);
        
        if ($stmt->execute()) {
            $success = "Payroll processed successfully!";
            log_activity("Payroll processed", "Employee: $employee_id, Net Salary: $net_salary");
        } else {
            throw new Exception("Error processing payroll: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get employees for dropdown
$employees = [];
$result = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS name FROM employees");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management - Site EMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        .table th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Payroll Management</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Process Payroll</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Employee</label>
                                <select name="employee_id" class="form-select" required>
                                    <option value="">Select Employee</option>
                                    <?php foreach ($employees as $emp): ?>
                                        <option value="<?= $emp['employee_id'] ?>">
                                            <?= $emp['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pay Period Start</label>
                                    <input type="date" name="pay_period_start" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pay Period End</label>
                                    <input type="date" name="pay_period_end" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bonuses (Rs.)</label>
                                    <input type="number" name="bonuses" class="form-control" min="0" step="0.01" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Deductions (Rs.)</label>
                                    <input type="number" name="deductions" class="form-control" min="0" step="0.01" value="0">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Process Payroll</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Recent Payroll Records</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Pay Period</th>
                                        <th>Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT p.*, CONCAT(e.first_name, ' ', e.last_name) AS employee_name 
                                                          FROM payroll p
                                                          JOIN employees e ON p.employee_id = e.employee_id
                                                          ORDER BY p.payment_date DESC
                                                          LIMIT 5");
                                    
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['employee_name']}</td>
                                                    <td>{$row['pay_period_start']} to {$row['pay_period_end']}</td>
                                                    <td>Rs. " . number_format($row['net_salary'], 2) . "</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>No payroll records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>