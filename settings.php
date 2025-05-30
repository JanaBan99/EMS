<?php
session_start();
require_once 'auth_utils.php';
require_login();
require_role('admin');

// Page title
$title = "System Settings";

// Database connection
require_once 'db_connection.php';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        // Update system settings
        $company_name = $_POST['company_name'] ?? '';
        $default_attendance_hours = $_POST['default_attendance_hours'] ?? 8;
        $payroll_tax_rate = $_POST['payroll_tax_rate'] ?? 0.15;
        
        // In a real system, you'd save these to a settings table
        $success = "System settings updated successfully!";
        log_activity("Settings updated", "Company name: $company_name");
    }
    elseif (isset($_POST['add_department'])) {
        // Add new department
        $dept_name = $_POST['dept_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $manager_id = $_POST['manager_id'] ?? null;
        
        try {
            $stmt = $conn->prepare("INSERT INTO departments (dept_name, description, manager_id)
                                  VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $dept_name, $description, $manager_id);
            
            if ($stmt->execute()) {
                $success = "Department added successfully!";
                log_activity("Department added", "Name: $dept_name");
            } else {
                throw new Exception("Error adding department: " . $stmt->error);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get departments
$departments = [];
$result = $conn->query("SELECT * FROM departments");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Get employees for manager dropdown
$managers = [];
$result = $conn->query("SELECT employee_id, CONCAT(first_name, ' ', last_name) AS name 
                      FROM employees 
                      WHERE system_role IN ('admin', 'manager')");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Site EMS</title>
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
        <h1 class="mb-4">System Settings</h1>
        
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
                        <h5 class="mb-0">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="update_settings" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="Construction Solutions Ltd.">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Default Work Hours</label>
                                <input type="number" name="default_attendance_hours" class="form-control" 
                                       value="8" min="4" max="12">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Payroll Tax Rate (%)</label>
                                <input type="number" name="payroll_tax_rate" class="form-control" 
                                       value="15" min="0" max="50" step="0.1">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">System Timezone</label>
                                <select class="form-select" name="timezone">
                                    <option value="Asia/Colombo" selected>Asia/Colombo (UTC+5:30)</option>
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">America/New York (UTC-5)</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Settings</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Manage Departments</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="add_department" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label">Department Name</label>
                                <input type="text" name="dept_name" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Department Manager</label>
                                <select name="manager_id" class="form-select">
                                    <option value="">-- Select Manager --</option>
                                    <?php foreach ($managers as $manager): ?>
                                        <option value="<?= $manager['employee_id'] ?>">
                                            <?= $manager['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Add Department</button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Existing Departments</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Manager</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($departments) > 0): ?>
                                        <?php foreach ($departments as $dept): ?>
                                            <tr>
                                                <td><?= $dept['dept_name'] ?></td>
                                                <td>
                                                    <?php 
                                                    if ($dept['manager_id']) {
                                                        $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS name 
                                                                              FROM employees 
                                                                              WHERE employee_id = ?");
                                                        $stmt->bind_param("s", $dept['manager_id']);
                                                        $stmt->execute();
                                                        $manager = $stmt->get_result()->fetch_assoc();
                                                        echo $manager['name'] ?? 'N/A';
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary me-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">No departments found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>PHP Version</span>
                                <span><?= phpversion() ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Database Version</span>
                                <span>MySQL 8.0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Server Software</span>
                                <span><?= $_SERVER['SERVER_SOFTWARE'] ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Application Version</span>
                                <span>1.2.0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Last Maintenance</span>
                                <span>2023-06-15 14:30</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Next Scheduled Backup</span>
                                <span>2023-07-01 02:00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>