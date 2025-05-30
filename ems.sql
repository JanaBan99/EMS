-- Drop existing tables in reverse dependency order
DROP TABLE IF EXISTS `remember_tokens`;
DROP TABLE IF EXISTS `payroll`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `employees`;

-- Create employees table with system_role for authentication
CREATE TABLE `employees` (
  `employee_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `job_role` varchar(50) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('active','onLeave','inactive') DEFAULT 'active',
  `system_role` enum('admin','manager','employee') DEFAULT 'employee',
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data including admin user
INSERT INTO `employees` 
  (`employee_id`, `first_name`, `last_name`, `email`, `phone`, `department`, `job_role`, `hire_date`, `salary`, `address`, `password_hash`, `status`, `system_role`) 
VALUES 
  ('ADMIN01', 'System', 'Admin', 'admin@ems.com', NULL, NULL, 'Administrator', CURDATE(), NULL, NULL, '$2y$10$4JcVM5qO.7yzVcE5W2cB.eg5z.8Gt5fF3L7kUu1nY9dX1VZsS7JdK', 'active', 'admin'),
  ('EMP01', 'John', 'Doe', 'john.doe@ems.com', '123-456-7890', 'Construction', 'Site Engineer', '2023-01-15', 80000.00, '123 Main St', '$2y$10$wqK8yqZp6b0FiDWSQ9LIIOL.toJ1Z9hgJgFuISbFikxCzlTOujJe6', 'active', 'employee'),
  ('EMP02', 'Jane', 'Smith', 'jane.smith@ems.com', '098-765-4321', 'Electrical', 'Technician', '2023-02-20', 75000.00, '456 Oak Ave', '$2y$10$WkO7GDZPdPrM29h7I.UMgOHDCyGyBmhfbd7yX20ITZlVUYFIcZmTG', 'active', 'employee');

-- Create attendance table with half-day status
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','half-day','leave') NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) 
    REFERENCES `employees` (`employee_id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create payroll table
CREATE TABLE `payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(10) NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `overtime_rate` decimal(10,2) DEFAULT 0.00,
  `bonuses` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) 
    REFERENCES `employees` (`employee_id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create activity_log table for auditing
CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create departments table
CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`dept_id`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`manager_id`) 
    REFERENCES `employees` (`employee_id`) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create remember_tokens table for persistent logins
CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(10) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`employee_id`) 
    REFERENCES `employees` (`employee_id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample department data
INSERT INTO `departments` (`dept_name`, `description`, `manager_id`)
VALUES 
  ('Construction', 'Main building division', 'EMP01'),
  ('Electrical', 'Power and wiring team', NULL),
  ('Plumbing', 'Water systems division', NULL);

-- Insert sample attendance record
INSERT INTO `attendance` (`employee_id`, `date`, `status`, `check_in`, `check_out`)
VALUES 
  ('EMP01', CURDATE(), 'present', '08:00:00', '17:00:00');

-- Insert sample payroll record
INSERT INTO `payroll` (
  `employee_id`, `pay_period_start`, `pay_period_end`, 
  `basic_salary`, `overtime_hours`, `overtime_rate`, 
  `bonuses`, `deductions`, `net_salary`, `payment_date`
) VALUES (
  'EMP01', '2023-06-01', '2023-06-30', 
  80000.00, 5.0, 1500.00, 
  5000.00, 12000.00, 80000.00 + (5*1500) + 5000 - 12000, 
  '2023-07-05'
);