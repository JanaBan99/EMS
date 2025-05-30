<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - Site EMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 260px;
            background-color: #2d3b45;
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .logo img {
            width: 24px;
            margin-right: 10px;
        }
        
        .logo h1 {
            font-size: 24px;
            color: #ff9934;
            font-weight: 600;
        }
        
        .menu {
            list-style: none;
        }
        
        .menu li {
            margin-bottom: 5px;
        }
        
        .menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu a:hover, .menu a.active {
            background-color: rgba(255,255,255,0.1);
        }
        
        .menu a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        /* Main content styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .search-container {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 400px;
        }
        
        .search-box {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 50px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .user-actions {
            display: flex;
            align-items: center;
        }
        
        .notification {
            position: relative;
            margin-right: 20px;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff3b30;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Stats cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card h3 {
            color: #8b97a3;
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 32px;
            font-weight: 600;
            color: #2d3b45;
        }
        
        .stat-card .icon {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background-color: rgba(255, 153, 52, 0.2);
            color: #ff9934;
            font-size: 20px;
        }
        
        /* Attendance table */
        .content-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #2d3b45;
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #ff9934;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #e68a2e;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid #d1d9e6;
            color: #6c757d;
        }
        
        .btn-outline:hover {
            background-color: #f8f9fa;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        th {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-present {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .status-absent {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .status-half-day {
            background-color: rgba(255, 153, 0, 0.1);
            color: #fd7e14;
        }
        
        .status-holiday {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .status-weekend {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .table-actions {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid #e9ecef;
            background-color: transparent;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background-color: #f8f9fa;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination button {
            width: 35px;
            height: 35px;
            margin: 0 5px;
            border-radius: 5px;
            border: 1px solid #d1d9e6;
            background-color: white;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .pagination button.active {
            background-color: #ff9934;
            color: white;
            border-color: #ff9934;
        }
        
        .pagination button:hover:not(.active) {
            background-color: #f8f9fa;
        }
        
        /* Filter and date controls */
        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .date-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .date-filter input {
            padding: 8px 12px;
            border: 1px solid #d1d9e6;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-dropdown {
            padding: 8px 12px;
            border: 1px solid #d1d9e6;
            border-radius: 5px;
            font-size: 14px;
            min-width: 150px;
        }
        
        /* Summary boxes */
        .summary-boxes {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .summary-box {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px;
            flex: 1;
            min-width: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .summary-box h4 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .summary-box .value {
            font-size: 24px;
            font-weight: 600;
            color: #2d3b45;
        }
        
        .present-box .value {
            color: #28a745;
        }
        
        .absent-box .value {
            color: #dc3545;
        }
        
        .half-day-box .value {
            color: #fd7e14;
        }
        
        .leave-box .value {
            color: #0d6efd;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            position: relative;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-col {
            flex: 1;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #6c757d;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #d1d9e6;
            border-radius: 5px;
            font-size: 14px;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #ff9934;
        }
        
        .modal-footer {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        /* Calendar styling */
        .calendar-container {
            margin-top: 30px;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .calendar-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3b45;
        }
        
        .calendar-nav {
            display: flex;
            gap: 10px;
        }
        
        .calendar-nav button {
            background: none;
            border: 1px solid #d1d9e6;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            color: #6c757d;
        }
        
        .calendar-nav button:hover {
            background-color: #f8f9fa;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .calendar-day-header {
            font-weight: 600;
            color: #6c757d;
            text-align: center;
            padding: 10px 0;
        }
        
        .calendar-day {
            background-color: white;
            border-radius: 5px;
            padding: 10px;
            min-height: 80px;
            border: 1px solid #e9ecef;
            position: relative;
        }
        
        .calendar-day.weekend {
            background-color: #f8f9fa;
        }
        
        .calendar-day.other-month {
            opacity: 0.5;
        }
        
        .day-number {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .day-status {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            font-size: 12px;
            text-align: center;
        }
        
        .day-status.present {
            color: #28a745;
        }
        
        .day-status.absent {
            color: #dc3545;
        }
        
        .day-status.half-day {
            color: #fd7e14;
        }
        
        .day-status.holiday {
            color: #0d6efd;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark Attendance button functionality
            const markAttendanceBtn = document.getElementById('markAttendanceBtn');
            const markAttendanceModal = document.getElementById('markAttendanceModal');
            const closeBtn = markAttendanceModal.querySelector('.close-btn');
            const cancelBtn = markAttendanceModal.querySelector('.cancel-btn');
            
            // Open modal
            markAttendanceBtn.addEventListener('click', function() {
                markAttendanceModal.style.display = 'block';
            });
            
            // Close modal
            closeBtn.addEventListener('click', function() {
                markAttendanceModal.style.display = 'none';
            });
            
            cancelBtn.addEventListener('click', function() {
                markAttendanceModal.style.display = 'none';
            });
            
            // Close modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === markAttendanceModal) {
                    markAttendanceModal.style.display = 'none';
                }
            });

            // Status dependent fields
            const statusSelect = document.getElementById('status');
            const checkInField = document.getElementById('checkIn');
            const checkOutField = document.getElementById('checkOut');
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'absent' || this.value === 'leave') {
                    checkInField.disabled = true;
                    checkOutField.disabled = true;
                    checkInField.value = '';
                    checkOutField.value = '';
                } else {
                    checkInField.disabled = false;
                    checkOutField.disabled = false;
                }
            });
            
            // Form submission with AJAX
            const attendanceForm = document.getElementById('attendanceForm');
            
            attendanceForm.addEventListener('submit', function(event) {
                event.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('add_attendance.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    if (data.includes('✅')) {
                        markAttendanceModal.style.display = 'none';
                        // Reload the page to show the updated attendance
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to record attendance. Please try again.');
                });
            });
        });
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="C:\Users\tilee\Desktop\Employee Management System\1000_F_185230215_OzA9rm9yEADL7VjI9SgjWOCb6JABx8bC.jpg" alt="Construction icon">
            <h1>Site EMS</h1>
        </div>
        <ul class="menu">            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/Dashboard_new.html"><i>🏠</i> Dashboard</a></li>
            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/Employee.html"><i>👥</i> Employees</a></li>
            <li><a href="Attendance.php" class="active"><i>📅</i> Attendance</a></li>
            <li><a href="Payrol.php"><i>💰</i> Payroll</a></li>
            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/Departments.html"><i>🏢</i> Departments</a></li>
            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/Report.html"><i>📊</i> Reports</a></li>
            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/Settings.html"><i>⚙️</i> Settings</a></li>
            <li><a href="file:///C:/Users/tilee/Desktop/Employee%20Management%20System/logout.html"><i>🚪</i> Logout</a></li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="search-container">
                <input type="text" class="search-box" placeholder="Search employee attendance...">
            </div>
            <div class="user-actions">
                <div class="notification">
                    <span>🔔</span>
                    <div class="notification-count">3</div>
                </div>
                <img src="E:\TCP NOTES\Notes\Semester 4\Sem 4\Advanced Qunatitative & Qualitative\Anthima Assignment eka\pngtree-avatar-icon-profile-icon-member-login-vector-isolated-png-image_1978396.jpg" alt="User Avatar" class="user-avatar">
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Present Today</h3>
                <div class="value">122</div>
                <div class="icon">✓</div>
            </div>
            <div class="stat-card">
                <h3>Absent Today</h3>
                <div class="value">18</div>
                <div class="icon">✗</div>
            </div>
            <div class="stat-card">
                <h3>Half Day</h3>
                <div class="value">10</div>
                <div class="icon">⌛</div>
            </div>
            <div class="stat-card">
                <h3>Average Attendance</h3>
                <div class="value">91%</div>
                <div class="icon">📊</div>
            </div>
        </div>

        <!-- Attendance Content -->
        <div class="content-card">            <div class="card-header">
                <h2>Daily Attendance</h2>
                <div class="card-actions">
                    <a href="export_attendance.php?date=<?php echo $selected_date = $_GET['date'] ?? date('Y-m-d'); ?><?php echo isset($_GET['department']) ? '&department=' . $_GET['department'] : ''; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" class="btn btn-outline">Export</a>
                    <button class="btn btn-primary" id="markAttendanceBtn">Mark Attendance</button>
                </div>
            </div>
              <form id="filterForm" method="get" action="">
                <div class="filter-container">
                    <div class="date-filter">
                        <label>Date:</label>
                        <input type="date" id="attendanceDate" name="date" value="<?php echo $selected_date = $_GET['date'] ?? date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <select class="filter-dropdown" id="departmentFilter" name="department">
                            <option value="">All Departments</option>
                            <option value="construction" <?php echo isset($_GET['department']) && $_GET['department'] == 'construction' ? 'selected' : ''; ?>>Construction</option>
                            <option value="electrical" <?php echo isset($_GET['department']) && $_GET['department'] == 'electrical' ? 'selected' : ''; ?>>Electrical</option>
                            <option value="plumbing" <?php echo isset($_GET['department']) && $_GET['department'] == 'plumbing' ? 'selected' : ''; ?>>Plumbing</option>
                            <option value="administration" <?php echo isset($_GET['department']) && $_GET['department'] == 'administration' ? 'selected' : ''; ?>>Administration</option>
                            <option value="safety" <?php echo isset($_GET['department']) && $_GET['department'] == 'safety' ? 'selected' : ''; ?>>Safety</option>
                            <option value="logistics" <?php echo isset($_GET['department']) && $_GET['department'] == 'logistics' ? 'selected' : ''; ?>>Logistics</option>
                        </select>
                    </div>
                    <div>
                        <select class="filter-dropdown" id="statusFilter" name="status">
                            <option value="">All Status</option>
                            <option value="present" <?php echo isset($_GET['status']) && $_GET['status'] == 'present' ? 'selected' : ''; ?>>Present</option>
                            <option value="absent" <?php echo isset($_GET['status']) && $_GET['status'] == 'absent' ? 'selected' : ''; ?>>Absent</option>
                            <option value="half-day" <?php echo isset($_GET['status']) && $_GET['status'] == 'half-day' ? 'selected' : ''; ?>>Half Day</option>
                            <option value="leave" <?php echo isset($_GET['status']) && $_GET['status'] == 'leave' ? 'selected' : ''; ?>>On Leave</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline">Apply Filters</button>
                </div>
            </form>
            
            <div class="summary-boxes">
                <div class="summary-box present-box">
                    <h4>Present</h4>
                    <div class="value">122</div>
                </div>
                <div class="summary-box absent-box">
                    <h4>Absent</h4>
                    <div class="value">18</div>
                </div>
                <div class="summary-box half-day-box">
                    <h4>Half Day</h4>
                    <div class="value">10</div>
                </div>
                <div class="summary-box leave-box">
                    <h4>On Leave</h4>
                    <div class="value">8</div>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Work Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $selected_date = $_GET['date'] ?? date('Y-m-d');
    include 'attendance_list.php';
    ?>
</tbody>

            </table>
            
            <div class="pagination">
                <button>⟨</button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>⟩</button>
            </div>
        </div>
        
        <!-- Mark Attendance Modal -->
        <div class="modal" id="markAttendanceModal">
            <div class="modal-content">
                <button class="close-btn">&times;</button>
                <h2>Mark Attendance</h2>
                <form id="attendanceForm" action="add_attendance.php" method="post">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="employeeSelect">Employee</label>
                                <select id="employeeSelect" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "ems");
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    
                                    $emp_sql = "SELECT employee_id, first_name, last_name FROM employees ORDER BY first_name";
                                    $emp_result = $conn->query($emp_sql);
                                    
                                    if ($emp_result->num_rows > 0) {
                                        while($emp = $emp_result->fetch_assoc()) {
                                            echo "<option value='{$emp['employee_id']}'>{$emp['first_name']} {$emp['last_name']}</option>";
                                        }
                                    }
                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="attendanceDate">Date</label>
                                <input type="date" id="attendanceDate" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="half-day">Half Day</option>
                                    <option value="leave">On Leave</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="checkIn">Check-in Time</label>
                                <input type="time" id="checkIn" name="check_in">
                            </div>
                            <div class="form-group">
                                <label for="checkOut">Check-out Time</label>
                                <input type="time" id="checkOut" name="check_out">
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea id="remarks" name="remarks" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Monthly Calendar View -->
        <div class="content-card calendar-container">
            <div class="calendar-header">
                <h2 class="calendar-title">April 2025</h2>
                <div class="calendar-nav">
                    <button class="prev-month">← Previous</button>
                    <button class="next-month">Next →</button>
                </div>
            </div>
            
            <div class="calendar-grid">
                <!-- Day headers -->
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
                
                <!-- March days (previous month) -->
                <div class="calendar-day other-month weekend">
                    <div class="day-number">30</div>
                </div>
                <div class="calendar-day other-month">
                    <div class="day-number">31</div>
                </div>
                
                <!-- April days -->
                <div class="calendar-day">
                    <div class="day-number">1</div>
                    <div class="day-status present">Present: 120</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">2</div>
                    <div class="day-status present">Present: 125</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">3</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">4</div>
                    <div class="day-status present">Present: 128</div>
                </div>
                <div class="calendar-day weekend">
                    <div class="day-number">5</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                
                <!-- Second week -->
                <div class="calendar-day weekend">
                    <div class="day-number">6</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">7</div>
                    <div class="day-status present">Present: 132</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">8</div>
                    <div class="day-status present">Present: 135</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">9</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">10</div>
                    <div class="day-status present">Present: 129</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">11</div>
                    <div class="day-status present">Present: 131</div>
                </div>
                <div class="calendar-day weekend">
                    <div class="day-number">12</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                
                <!-- Third week -->
                <div class="calendar-day weekend">
                    <div class="day-number">13</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">14</div>
                    <div class="day-status holiday">Holiday</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">15</div>
                    <div class="day-status present">Present: 128</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">16</div>
                    <div class="day-status present">Present: 132</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">17</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">18</div>
                    <div class="day-status present">Present: 127</div>
                </div>
                <div class="calendar-day weekend">
                    <div class="day-number">19</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                
                <!-- Fourth week -->
                <div class="calendar-day weekend">
                    <div class="day-number">20</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">21</div>
                    <div class="day-status present">Present: 125</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">22</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">23</div>
                    <div class="day-status present">Present: 128</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">24</div>
                    <div class="day-status present">Present: 132</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">25</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day weekend">
                    <div class="day-number">26</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                
                <!-- Fifth week -->
                <div class="calendar-day weekend">
                    <div class="day-number">27</div>
                    <div class="day-status holiday">Weekend</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">28</div>
                    <div class="day-status present">Present: 128</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">29</div>
                    <div class="day-status present">Present: 130</div>
                </div>
                <div class="calendar-day">
                    <div class="day-number">30</div>
                    <div class="day-status present">Present: 127</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mark Attendance button click
        document.getElementById('markAttendanceBtn').addEventListener('click', function() {
            document.getElementById('markAttendanceModal').style.display = 'block';
        });
        
        // Close modal when clicking on close button
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.getElementById('markAttendanceModal').style.display = 'none';
        });
        
        // Close modal when clicking outside of the modal content
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('markAttendanceModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
        
        // Cancel button in modal
        document.querySelector('.cancel-btn').addEventListener('click', function() {
            document.getElementById('markAttendanceModal').style.display = 'none';
        });
    </script>
</body>
</html>