
# Employee Management System (EMS) for Construction Sites

## Table of Contents
- [Project Overview](#project-overview)
- [Features](#features)
- [Installation Guide](#installation-guide)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [File Structure](#file-structure)
- [API Endpoints](#api-endpoints)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Project Overview
This Employee Management System is designed specifically for construction sites to manage employees, departments, attendance, payroll, and generate reports. The system features role-based access control with three user roles: Admin, Manager, and Employee.

## Features
- Employee management (CRUD operations)
- Department management
- Attendance tracking with check-in/out
- Payroll processing
- Reporting and analytics
- Activity logging
- Role-based access control
- Responsive dashboard

## Installation Guide

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Step-by-Step Installation
Clone the repository:
```bash
git clone https://github.com/yourusername/ems-construction.git
cd ems-construction
```



Configure environment variables in `.env`:
```ini
DB_HOST=localhost
DB_NAME=ems
DB_USER=root
DB_PASS=
APP_ENV=production
APP_KEY=your_secret_key_here
```

Set permissions:
```bash
chmod -R 775 storage
chown -R www-data:www-data public
```

## Database Setup

Create database:
```sql
CREATE DATABASE ems CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import SQL schema:
```bash
mysql -u root -p ems < database/ems.sql
```

Create admin user:
```sql
INSERT INTO employees (employee_id, first_name, last_name, email, password_hash, system_role)
VALUES ('ADMIN01', 'System', 'Admin', 'admin@ems.com', '$2y$10$4JcVM5qO.7yzVcE5W2cB.eg5z.8Gt5fF3L7kUu1nY9dX1VZsS7JdK', 'admin');
```
Default password: `admin123`

## Configuration

### Session Configuration (`php.ini`):
```ini
session.save_path = "/var/www/ems-construction/sessions"
session.gc_maxlifetime = 1440
session.cookie_secure = 1
session.cookie_httponly = 1
session.use_strict_mode = 1
```

### Apache Virtual Host (example):
```apache
<VirtualHost *:80>
    ServerName ems.local
    DocumentRoot /var/www/ems-construction/public
    <Directory /var/www/ems-construction/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/ems-error.log
    CustomLog ${APACHE_LOG_DIR}/ems-access.log combined
</VirtualHost>
```

## Usage

Access the application: `http://localhost/ems-construction`

Login credentials:
- Admin: `admin@ems.com` / `admin123`
- Employee: `employee@ems.com` / `password123`

Key features:
- **Dashboard**: Overview of system statistics
- **Employees**: Manage employee records
- **Attendance**: Track daily attendance
- **Payroll**: Process monthly payments
- **Reports**: Generate various reports
- **Settings**: Configure system parameters

## File Structure

```
ems-construction/
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── includes/
│   ├── auth_utils.php
│   ├── db_connection.php
│   ├── header.php
│   └── footer.php
├── modules/
│   ├── dashboard/
│   │   └── Dashboard_new.php
│   ├── employees/
│   │   ├── Employee.php
│   │   ├── employee_list.php
│   │   └── add_employee.php
│   ├── attendance/
│   │   ├── Attendance.php
│   │   ├── attendance_list.php
│   │   └── add_attendance.php
│   ├── departments/
│   │   └── Departments.php
│   ├── payroll/
│   │   └── Payrol.php
│   ├── reports/
│   │   └── Report.php
│   └── settings/
│       └── settings.php
├── database/
│   └── ems.sql
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── .htaccess
├── .env
├── .htaccess
└── index.php
```

## API Endpoints

| Endpoint                | Method | Description              | Required Role     |
|------------------------|--------|--------------------------|-------------------|
| /api/employees         | GET    | List all employees       | Admin, Manager    |
| /api/employees         | POST   | Create new employee      | Admin             |
| /api/employees/{id}    | PUT    | Update employee          | Admin, Manager    |
| /api/attendance        | GET    | Get attendance records   | Admin, Manager    |
| /api/attendance        | POST   | Record attendance        | Manager           |
| /api/payroll           | GET    | Get payroll data         | Admin             |
| /api/payroll/generate  | POST   | Generate payroll         | Admin             |

## Troubleshooting

- **Issue**: Database connection fails  
  **Solution**: Verify DB credentials in `.env` and ensure MySQL is running

- **Issue**: Session not persisting  
  **Solution**: Check `session.save_path` in `php.ini` has proper permissions

- **Issue**: Page styling broken  
  **Solution**: Check Apache rewrite rules in `.htaccess` and asset paths

- **Issue**: "Access Denied" errors  
  **Solution**: Verify user role has required permissions for the action

## Contributing

- Fork the repository
- Create your feature branch (`git checkout -b feature/your-feature`)
- Commit your changes (`git commit -am 'Add some feature'`)
- Push to the branch (`git push origin feature/your-feature`)
- Create a new Pull Request

## License
This project is licensed under the MIT License - see the LICENSE file for details.
