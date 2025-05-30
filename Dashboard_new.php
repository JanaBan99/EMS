<?php
session_start();
require_once 'auth_utils.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_name = $_SESSION['user_name'] ?? 'User';
$user_role = $_SESSION['user_role'] ?? 'Employee';

// Log the dashboard access
log_activity('Dashboard Access', 'User accessed the dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Construction Site EMS Dashboard</title>
  <style>
    :root {
      --primary: #ff9800;
      --secondary: #37474f;
      --bg: #f5f5f5;
      --white: #fff;
      --text-dark: #263238;
      --text-light: #90a4ae;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: var(--bg);
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar styles */
    .sidebar {
      background: var(--secondary);
      width: 250px;
      padding: 20px 0;
      color: var(--white);
      position: fixed;
      height: 100%;
      overflow-y: auto;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      margin-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header i {
      font-size: 24px;
      margin-right: 10px;
      color: var(--primary);
    }

    .sidebar-header h1 {
      font-size: 20px;
      font-weight: 600;
      color: var(--primary);
    }

    .sidebar-menu {
      padding: 0 10px;
    }

    .sidebar-menu a {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      color: var(--white);
      text-decoration: none;
      border-radius: 5px;
      margin-bottom: 5px;
      transition: 0.3s;
    }

    .sidebar-menu a i {
      margin-right: 15px;
      font-size: 20px;
    }

    .sidebar-menu a:hover, .sidebar-menu a.active {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu a.active {
      border-left: 3px solid var(--primary);
    }

    /* Main content styles */
    .main-content {
      flex: 1;
      margin-left: 250px;
      padding: 20px;
    }

    /* Header styles */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--text-light);
    }

    .page-title h1 {
      font-size: 24px;
      color: var(--text-dark);
    }

    .page-title p {
      font-size: 14px;
      color: var(--text-light);
    }

    .user-info {
      display: flex;
      align-items: center;
    }

    .notification {
      margin-right: 20px;
      position: relative;
    }

    .notification i {
      font-size: 22px;
      color: var(--text-dark);
      cursor: pointer;
    }

    .notification-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--primary);
      color: var(--white);
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
    }

    .user-profile {
      display: flex;
      align-items: center;
      cursor: pointer;
    }

    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .user-profile-info h4 {
      font-size: 16px;
      color: var(--text-dark);
    }

    .user-profile-info p {
      font-size: 12px;
      color: var(--text-light);
    }

    /* Card styles */
    .row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -15px;
      margin-bottom: 30px;
    }

    .col {
      flex: 1;
      padding: 0 15px;
      min-width: 250px;
    }

    .card {
      background: var(--white);
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 20px;
      margin-bottom: 20px;
    }

    .stat-card {
      display: flex;
      align-items: center;
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      background: rgba(255, 152, 0, 0.1);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .stat-icon i {
      font-size: 30px;
      color: var(--primary);
    }

    .stat-info h3 {
      font-size: 14px;
      color: var(--text-light);
      margin-bottom: 5px;
    }

    .stat-info h2 {
      font-size: 24px;
      color: var(--text-dark);
    }

    .stat-info p {
      font-size: 12px;
      color: var(--text-light);
      margin-top: 5px;
    }

    .stat-info p.positive {
      color: #2ecc71;
    }

    .stat-info p.negative {
      color: #e74c3c;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .card-header h3 {
      font-size: 18px;
      color: var(--text-dark);
    }

    .card-actions button {
      background: none;
      border: none;
      color: var(--text-light);
      cursor: pointer;
      font-size: 14px;
    }

    /* Table styles */
    .table-responsive {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      color: var(--text-light);
      font-weight: 500;
      font-size: 14px;
    }

    td {
      color: var(--text-dark);
    }

    tr:last-child td {
      border-bottom: none;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 30px;
      font-size: 12px;
      font-weight: 500;
    }

    .status-badge.present {
      background: rgba(46, 204, 113, 0.1);
      color: #2ecc71;
    }

    .status-badge.absent {
      background: rgba(231, 76, 60, 0.1);
      color: #e74c3c;
    }

    .status-badge.late {
      background: rgba(241, 196, 15, 0.1);
      color: #f1c40f;
    }

    .status-badge.holiday {
      background: rgba(52, 152, 219, 0.1);
      color: #3498db;
    }

    /* Project card styles */
    .project-card {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .project-card:last-child {
      margin-bottom: 0;
      padding-bottom: 0;
      border-bottom: none;
    }

    .project-icon {
      width: 50px;
      height: 50px;
      background: rgba(255, 152, 0, 0.1);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .project-icon i {
      font-size: 24px;
      color: var(--primary);
    }

    .project-info {
      flex: 1;
    }

    .project-info h4 {
      font-size: 16px;
      color: var(--text-dark);
      margin-bottom: 5px;
    }

    .project-info p {
      font-size: 12px;
      color: var(--text-light);
    }

    .project-progress {
      width: 80px;
    }

    .progress-bar {
      height: 8px;
      background: #eee;
      border-radius: 10px;
      margin-bottom: 5px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: var(--primary);
      border-radius: 10px;
    }

    .progress-text {
      font-size: 12px;
      color: var(--text-light);
      text-align: right;
    }

    /* Calendar styles */
    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .calendar-title {
      font-size: 16px;
      color: var(--text-dark);
    }

    .calendar-nav button {
      background: none;
      border: none;
      color: var(--text-light);
      cursor: pointer;
      font-size: 14px;
    }

    .calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
    }

    .calendar-day-header {
      text-align: center;
      font-size: 12px;
      color: var(--text-light);
      margin-bottom: 5px;
    }

    .calendar-day {
      background: rgba(255, 255, 255, 0.7);
      border: 1px solid #eee;
      border-radius: 5px;
      padding: 5px;
      height: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .calendar-day.today {
      background: rgba(255, 152, 0, 0.1);
      border-color: var(--primary);
    }

    .calendar-day.other-month {
      opacity: 0.5;
    }

    .calendar-day-number {
      font-size: 14px;
      color: var(--text-dark);
      font-weight: 500;
    }

    .calendar-day-event {
      width: 5px;
      height: 5px;
      background: var(--primary);
      border-radius: 50%;
      margin-top: 3px;
    }

    /* Media queries for responsiveness */
    @media (max-width: 1024px) {
      .col {
        min-width: 300px;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
        padding: 20px 0;
      }
      
      .sidebar-header h1, .sidebar-menu a span {
        display: none;
      }
      
      .sidebar-header {
        justify-content: center;
      }
      
      .sidebar-menu a {
        justify-content: center;
      }
      
      .sidebar-menu a i {
        margin-right: 0;
      }
      
      .main-content {
        margin-left: 70px;
      }
    }

    @media (max-width: 480px) {
      .header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .user-info {
        margin-top: 15px;
      }
      
      .stat-card {
        flex-direction: column;
        text-align: center;
      }
      
      .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-hard-hat"></i>
      <h1>Site EMS</h1>
    </div>
    <div class="sidebar-menu">
      <a href="Dashboard_new.php" class="active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
      <a href="Employee.php">
        <i class="fas fa-users"></i>
        <span>Employees</span>
      </a>
      <a href="Attendance.php">
        <i class="fas fa-calendar-check"></i>
        <span>Attendance</span>
      </a>
      <a href="Payrol.php">
        <i class="fas fa-money-bill-wave"></i>
        <span>Payroll</span>
      </a>
      <a href="Departments.php">
        <i class="fas fa-building"></i>
        <span>Departments</span>
      </a>
      <a href="Report.php">
        <i class="fas fa-chart-line"></i>
        <span>Reports</span>
      </a>
      <a href="settings.php">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </a>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <div class="page-title">
        <h1>Dashboard</h1>
        <p>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</p>
      </div>
      <div class="user-info">
        <div class="notification">
          <i class="fas fa-bell"></i>
          <div class="notification-count">3</div>
        </div>
        <div class="user-profile">
          <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User Profile">
          <div class="user-profile-info">
            <h4><?php echo htmlspecialchars($user_name); ?></h4>
            <p><?php echo htmlspecialchars($user_role); ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
      <div class="col">
        <div class="card stat-card">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-info">
            <h3>Total Employees</h3>
            <h2>150</h2>
            <p class="positive">+5 this month</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card stat-card">
          <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
          </div>
          <div class="stat-info">
            <h3>Present Today</h3>
            <h2>132</h2>
            <p>88% attendance</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card stat-card">
          <div class="stat-icon">
            <i class="fas fa-hard-hat"></i>
          </div>
          <div class="stat-info">
            <h3>Active Projects</h3>
            <h2>8</h2>
            <p class="positive">2 completed this month</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card stat-card">
          <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <div class="stat-info">
            <h3>Payroll</h3>
            <h2>$285,750</h2>
            <p class="negative">+2.5% from last month</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity & Attendance Row -->
    <div class="row">
      <div class="col" style="flex: 2;">
        <div class="card">
          <div class="card-header">
            <h3>Recent Attendance</h3>
            <div class="card-actions">
              <button>View All</button>
            </div>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Department</th>
                  <th>Check In</th>
                  <th>Check Out</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>John Smith</td>
                  <td>Construction</td>
                  <td>08:02 AM</td>
                  <td>05:15 PM</td>
                  <td><span class="status-badge present">Present</span></td>
                </tr>
                <tr>
                  <td>Sarah Johnson</td>
                  <td>Electrical</td>
                  <td>08:15 AM</td>
                  <td>05:00 PM</td>
                  <td><span class="status-badge present">Present</span></td>
                </tr>
                <tr>
                  <td>Mike Donovan</td>
                  <td>Plumbing</td>
                  <td>08:45 AM</td>
                  <td>05:30 PM</td>
                  <td><span class="status-badge late">Late</span></td>
                </tr>
                <tr>
                  <td>Lisa Wong</td>
                  <td>Administration</td>
                  <td>--</td>
                  <td>--</td>
                  <td><span class="status-badge absent">Absent</span></td>
                </tr>
                <tr>
                  <td>David Chen</td>
                  <td>Construction</td>
                  <td>08:05 AM</td>
                  <td>05:10 PM</td>
                  <td><span class="status-badge present">Present</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h3>Active Projects</h3>
            <div class="card-actions">
              <button>View All</button>
            </div>
          </div>
          <div class="project-card">
            <div class="project-icon">
              <i class="fas fa-building"></i>
            </div>
            <div class="project-info">
              <h4>Downtown Office Tower</h4>
              <p>45 workers assigned</p>
            </div>
            <div class="project-progress">
              <div class="progress-bar">
                <div class="progress-fill" style="width: 75%;"></div>
              </div>
              <div class="progress-text">75%</div>
            </div>
          </div>
          <div class="project-card">
            <div class="project-icon">
              <i class="fas fa-road"></i>
            </div>
            <div class="project-info">
              <h4>Highway Extension</h4>
              <p>32 workers assigned</p>
            </div>
            <div class="project-progress">
              <div class="progress-bar">
                <div class="progress-fill" style="width: 45%;"></div>
              </div>
              <div class="progress-text">45%</div>
            </div>
          </div>
          <div class="project-card">
            <div class="project-icon">
              <i class="fas fa-home"></i>
            </div>
            <div class="project-info">
              <h4>Residential Complex</h4>
              <p>28 workers assigned</p>
            </div>
            <div class="project-progress">
              <div class="progress-bar">
                <div class="progress-fill" style="width: 90%;"></div>
              </div>
              <div class="progress-text">90%</div>
            </div>
          </div>
          <div class="project-card">
            <div class="project-icon">
              <i class="fas fa-bridge"></i>
            </div>
            <div class="project-info">
              <h4>River Bridge</h4>
              <p>15 workers assigned</p>
            </div>
            <div class="project-progress">
              <div class="progress-bar">
                <div class="progress-fill" style="width: 30%;"></div>
              </div>
              <div class="progress-text">30%</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar Row -->
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="calendar-header">
            <div class="calendar-title">May 2025</div>
            <div class="calendar-nav">
              <button><i class="fas fa-chevron-left"></i></button>
              <button><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
          <div class="calendar-days">
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
            
            <div class="calendar-day other-month">
              <div class="calendar-day-number">27</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">28</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">29</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">30</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">1</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">2</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">3</div>
            </div>
            
            <div class="calendar-day">
              <div class="calendar-day-number">4</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">5</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">6</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">7</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">8</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">9</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">10</div>
            </div>
            
            <div class="calendar-day">
              <div class="calendar-day-number">11</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">12</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">13</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">14</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">15</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">16</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">17</div>
            </div>
            
            <div class="calendar-day">
              <div class="calendar-day-number">18</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">19</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">20</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">21</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">22</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">23</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">24</div>
            </div>
            
            <div class="calendar-day">
              <div class="calendar-day-number">25</div>
            </div>
            <div class="calendar-day today">
              <div class="calendar-day-number">26</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">27</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">28</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">29</div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">30</div>
              <div class="calendar-day-event"></div>
            </div>
            <div class="calendar-day">
              <div class="calendar-day-number">31</div>
            </div>
            
            <div class="calendar-day other-month">
              <div class="calendar-day-number">1</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">2</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">3</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">4</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">5</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">6</div>
            </div>
            <div class="calendar-day other-month">
              <div class="calendar-day-number">7</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Add your JavaScript here
  </script>
</body>
</html>
