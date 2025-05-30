<?php
session_start();
require_once 'auth_utils.php';
require_login();
require_role('admin,manager');

// Page title
$title = "Reports";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Site EMS</title>
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
        <h1 class="mb-4">Reports</h1>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center p-4 mb-4">
                    <h2 class="display-4">152</h2>
                    <p class="lead">Total Employees</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center p-4 mb-4">
                    <h2 class="display-4">94%</h2>
                    <p class="lead">Average Attendance</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white text-center p-4 mb-4">
                    <h2 class="display-4">Rs. 8.2M</h2>
                    <p class="lead">Monthly Payroll</p>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Generate Reports</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Report Type</label>
                            <select class="form-select">
                                <option>Attendance Report</option>
                                <option>Payroll Summary</option>
                                <option>Department Performance</option>
                                <option>Employee Productivity</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select class="form-select">
                            <option value="">All Departments</option>
                            <option>Construction</option>
                            <option>Electrical</option>
                            <option>Plumbing</option>
                            <option>Administration</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>Generate Report
                    </button>
                    <button type="button" class="btn btn-success ms-2">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Attendance Report</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>On Leave</th>
                                <th>Half Days</th>
                                <th>Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Construction</td>
                                <td>125</td>
                                <td>5</td>
                                <td>8</td>
                                <td>12</td>
                                <td>92%</td>
                            </tr>
                            <tr>
                                <td>Electrical</td>
                                <td>86</td>
                                <td>3</td>
                                <td>4</td>
                                <td>7</td>
                                <td>94%</td>
                            </tr>
                            <tr>
                                <td>Plumbing</td>
                                <td>45</td>
                                <td>2</td>
                                <td>1</td>
                                <td>2</td>
                                <td>96%</td>
                            </tr>
                            <tr>
                                <td>Administration</td>
                                <td>22</td>
                                <td>0</td>
                                <td>1</td>
                                <td>0</td>
                                <td>98%</td>
                            </tr>
                            <tr class="table-success fw-bold">
                                <td>Total</td>
                                <td>278</td>
                                <td>10</td>
                                <td>14</td>
                                <td>21</td>
                                <td>94%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>