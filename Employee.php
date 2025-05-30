<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_name = $_SESSION['user_name'] ?? 'User';
$user_role = $_SESSION['user_role'] ?? 'Employee';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - Site EMS</title>
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
        
        /* Employees table */
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
        
        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .status-leave {
            background-color: rgba(255, 153, 0, 0.1);
            color: #fd7e14;
        }
        
        .status-inactive {
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="C:\Users\tilee\Desktop\Employee Management System\1000_F_185230215_OzA9rm9yEADL7VjI9SgjWOCb6JABx8bC.jpg" alt="Construction icon">
            <h1>Site EMS</h1>
        </div>        <ul class="menu">
            <li><a href="Dashboard_new.php"><i>🏠</i> Dashboard</a></li>
            <li><a href="Employee.php" class="active"><i>👥</i> Employees</a></li>
            <li><a href="Attendance.php"><i>📅</i> Attendance</a></li>
            <li><a href="Payrol.php"><i>💰</i> Payroll</a></li>
            <li><a href="Departments.php"><i>🏢</i> Departments</a></li>
            <li><a href="Report.php"><i>📊</i> Reports</a></li>
            <li><a href="settings.php"><i>⚙️</i> Settings</a></li>
            <li><a href="logout.php"><i>🚪</i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->        <div class="header">
            <div class="search-container">
                <input type="text" class="search-box" placeholder="Search employees or tasks...">
            </div>
            <div class="user-actions">
                <div class="notification">
                    <span>🔔</span>
                    <div class="notification-count">3</div>
                </div>
                <div class="user-info" style="display: flex; align-items: center; margin-left: 15px;">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User Avatar" class="user-avatar">
                    <div style="margin-left: 10px;">
                        <div style="font-weight: bold;"><?php echo htmlspecialchars($user_name); ?></div>
                        <div style="font-size: 12px; color: #777;"><?php echo htmlspecialchars($user_role); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Employees</h3>
                <div class="value">150</div>
                <div class="icon">👥</div>
            </div>
            <div class="stat-card">
                <h3>Active Employees</h3>
                <div class="value">132</div>
                <div class="icon">✓</div>
            </div>
            <div class="stat-card">
                <h3>On Leave</h3>
                <div class="value">10</div>
                <div class="icon">✗</div>
            </div>
            <div class="stat-card">
                <h3>Departments</h3>
                <div class="value">6</div>
                <div class="icon">🏢</div>
            </div>
        </div>

        <!-- Employee List -->
        <div class="content-card">
            <div class="card-header">
                <h2>Employee Management</h2>
                <div class="card-actions">
                    <button class="btn btn-outline">Filter</button>
                    <button class="btn btn-primary" id="addEmployeeBtn">+ Add Employee</button>
                </div>
            </div>
            
            <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Role</th>
            <th>Hire Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php include 'employee_list.php'; ?>
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
    </div>
    
    <!-- Add/Edit Employee Modal -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" id="closeModal">×</button>
            <h2 id="modalTitle">Add New Employee</h2>
            <form id="employeeForm" action="add_employee.php" method="POST">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="employeeId">Employee ID</label>
                            <input type="text" id="employeeId" name="employeeId" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="onLeave">On Leave</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="construction">Construction</option>
                                <option value="electrical">Electrical</option>
                                <option value="plumbing">Plumbing</option>
                                <option value="administration">Administration</option>
                                <option value="safety">Safety</option>
                                <option value="logistics">Logistics</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="siteEngineer">Site Engineer</option>
                                <option value="electrician">Electrician</option>
                                <option value="plumber">Plumber</option>
                                <option value="hrManager">HR Manager</option>
                                <option value="foreman">Foreman</option>
                                <option value="laborer">Laborer</option>
                                <option value="technician">Technician</option>
                                <option value="safetyOfficer">Safety Officer</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="hireDate">Hire Date</label>
                            <input type="date" id="hireDate" name="hireDate" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="salary">Basic Salary</label>
                            <input type="number" id="salary" name="salary" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Employee</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript for modal functionality
        const addEmployeeBtn = document.getElementById('addEmployeeBtn');
        const employeeModal = document.getElementById('employeeModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const editBtns = document.querySelectorAll('.edit-btn');
        const viewBtns = document.querySelectorAll('.view-btn');
        const deleteBtns = document.querySelectorAll('.delete-btn');
        
        // Open modal for new employee
        addEmployeeBtn.addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = 'Add New Employee';
            document.getElementById('employeeForm').reset();
            employeeModal.style.display = 'block';
        });
        
        // Close modal
        closeModal.addEventListener('click', () => {
            employeeModal.style.display = 'none';
        });
        
        cancelBtn.addEventListener('click', () => {
            employeeModal.style.display = 'none';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === employeeModal) {
                employeeModal.style.display = 'none';
            }
        });
        
        // Edit employee
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalTitle').textContent = 'Edit Employee';
                employeeModal.style.display = 'block';
                // Here you would normally populate the form with employee data
            });
        });
        
        // View employee
        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                alert('View employee details');
            });
        });
        
        // Delete employee
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if(confirm('Are you sure you want to delete this employee?')) {
                    alert('Employee deleted successfully');
                }
            });
        });
    </script>
</body>
</html>