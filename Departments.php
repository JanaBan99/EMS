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
    <title>Department Management - Site EMS</title>
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
            margin-top: 30px;
        }
        
        .menu ul {
            list-style: none;
        }
        
        .menu ul li {
            margin-bottom: 5px;
        }
        
        .menu ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .menu ul li a:hover, .menu ul li a.active {
            background-color: #ff9934;
            color: white;
        }
        
        .menu ul li a i {
            margin-right: 15px;
            font-size: 20px;
        }
        
        /* Main content styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            position: relative;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e5ea;
        }
        
        .page-title h1 {
            font-size: 28px;
            color: #333;
            font-weight: 600;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .user-name {
            font-weight: 600;
        }
        
        .user-role {
            font-size: 12px;
            color: #777;
        }
        
        .search-container {
            position: relative;
            margin-right: 20px;
        }
        
        .search-container input {
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
            font-size: 14px;
        }
        
        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .notification {
            position: relative;
            margin-right: 15px;
        }
        
        .notification i {
            font-size: 20px;
            color: #777;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff9934;
            color: white;
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .more-menu {
            position: relative;
        }
        
        .more-menu i {
            font-size: 20px;
            color: #777;
            cursor: pointer;
        }
        
        /* Department list styles */
        .department-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .department-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .department-header {
            padding: 20px;
            background-color: #2d3b45;
            color: white;
            position: relative;
        }
        
        .department-header h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .department-header p {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .department-menu {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
        }
        
        .department-body {
            padding: 20px;
        }
        
        .department-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #777;
        }
        
        .department-manager {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .department-manager img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .manager-info {
            flex: 1;
        }
        
        .manager-name {
            font-weight: 600;
            font-size: 14px;
        }
        
        .manager-role {
            font-size: 12px;
            color: #777;
        }
        
        .department-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        
        .department-footer a {
            color: #2d3b45;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .department-footer a:hover {
            color: #ff9934;
        }
        
        /* Add department button */
        .add-department {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #ff9934;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        
        .add-department:hover {
            transform: scale(1.1);
            background-color: #e88a2a;
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
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            width: 500px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            padding: 20px;
            background-color: #2d3b45;
            color: white;
        }
        
        .modal-header h2 {
            font-size: 20px;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            height: 100px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-cancel {
            background-color: #e1e5ea;
            color: #333;
        }
        
        .btn-cancel:hover {
            background-color: #d1d7dd;
        }
        
        .btn-primary {
            background-color: #ff9934;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #e88a2a;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-hard-hat"></i>
            <h1>Site EMS</h1>
        </div>
        <div class="menu">
            <ul>
                <li><a href="Dashboard_new.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="Employee.php"><i class="fas fa-users"></i> Employees</a></li>
                <li><a href="Attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li><a href="Payrol.php"><i class="fas fa-money-bill-wave"></i> Payroll</a></li>
                <li><a href="Departments.php" class="active"><i class="fas fa-building"></i> Departments</a></li>
                <li><a href="Report.php"><i class="fas fa-chart-line"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="page-title">
                <h1>Departments</h1>
            </div>
            <div class="header-actions">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search departments...">
                </div>
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                </div>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User Avatar">
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($user_role); ?></div>
                    </div>
                </div>
                <div class="more-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
        </div>

        <!-- Department Grid -->
        <div class="department-grid">
            <!-- Department Card 1 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>Construction Team</h3>
                    <p>Main Building Division</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">24</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">92%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">4</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">John Doe</div>
                            <div class="manager-role">Construction Manager</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>

            <!-- Department Card 2 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>Electrical Division</h3>
                    <p>Power & Wiring Team</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">18</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">88%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">6</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">Jane Smith</div>
                            <div class="manager-role">Electrical Lead</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>

            <!-- Department Card 3 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>Plumbing Unit</h3>
                    <p>Water Systems Division</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">12</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">95%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">3</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">Mike Johnson</div>
                            <div class="manager-role">Plumbing Supervisor</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>

            <!-- Department Card 4 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>HVAC Team</h3>
                    <p>Climate Control Division</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">15</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">91%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">5</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/men/36.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">Robert Williams</div>
                            <div class="manager-role">HVAC Specialist</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>

            <!-- Department Card 5 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>Masonry Department</h3>
                    <p>Brick & Stone Division</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">20</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">87%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">2</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">James Brown</div>
                            <div class="manager-role">Masonry Lead</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>

            <!-- Department Card 6 -->
            <div class="department-card">
                <div class="department-header">
                    <h3>Carpentry Team</h3>
                    <p>Woodwork Division</p>
                    <div class="department-menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                </div>
                <div class="department-body">
                    <div class="department-stats">
                        <div class="stat-item">
                            <div class="stat-value">16</div>
                            <div class="stat-label">Employees</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">94%</div>
                            <div class="stat-label">Attendance</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">4</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="department-manager">
                        <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Manager">
                        <div class="manager-info">
                            <div class="manager-name">Emily Davis</div>
                            <div class="manager-role">Carpentry Supervisor</div>
                        </div>
                    </div>
                </div>
                <div class="department-footer">
                    <a href="#"><i class="fas fa-users"></i> View Employees</a>
                    <a href="#"><i class="fas fa-edit"></i> Edit</a>
                </div>
            </div>
        </div>

        <!-- Add Department Button -->
        <div class="add-department" id="addDepartmentBtn">
            <i class="fas fa-plus"></i>
        </div>

        <!-- Add Department Modal -->
        <div class="modal" id="departmentModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Department</h2>
                    <span class="close" id="closeModal">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="departmentForm">
                        <div class="form-group">
                            <label for="departmentName">Department Name</label>
                            <input type="text" id="departmentName" name="departmentName" required>
                        </div>
                        <div class="form-group">
                            <label for="departmentDesc">Description</label>
                            <input type="text" id="departmentDesc" name="departmentDesc">
                        </div>
                        <div class="form-group">
                            <label for="departmentManager">Department Manager</label>
                            <select id="departmentManager" name="departmentManager">
                                <option value="">Select Manager</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Mike Johnson</option>
                                <option value="4">Emily Davis</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departmentLocation">Location</label>
                            <input type="text" id="departmentLocation" name="departmentLocation">
                        </div>
                        <div class="form-group">
                            <label for="departmentNotes">Additional Notes</label>
                            <textarea id="departmentNotes" name="departmentNotes"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-cancel" id="cancelBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('departmentModal');
        const addBtn = document.getElementById('addDepartmentBtn');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');

        addBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Form submission (for demo purposes)
        document.getElementById('departmentForm').addEventListener('submit', (e) => {
            e.preventDefault();
            // Here you would typically send form data to server
            alert('Department saved successfully!');
            modal.style.display = 'none';
        });
    </script>
</body>
</html>
