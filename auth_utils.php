<?php
// auth_utils.php - Authentication and authorization utilities

// Database connection details (should be in a separate config file)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ems');

/**
 * Get database connection
 */
function get_db_connection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            return null;
        }
    }
    
    return $conn;
}

/**
 * Ensure activity_log table exists
 */
function ensure_activity_log_table() {
    $conn = get_db_connection();
    if (!$conn) return false;

    $sql = "CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(10) NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        action VARCHAR(255) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    return $conn->query($sql);
}

// Create table if not exists when file is included
ensure_activity_log_table();

/**
 * Check if the current user has a specific role
 */
function has_role($required_roles) {
    if (session_status() === PHP_SESSION_NONE) {
        return false;
    }
    
    if (!isset($_SESSION['user_id'], $_SESSION['user_role'])) {
        return false;
    }
    
    if (!is_array($required_roles)) {
        $required_roles = [$required_roles];
    }
    
    return in_array($_SESSION['user_role'], $required_roles);
}

/**
 * Ensure user is logged in or redirect to login page
 */
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Ensure user has required role or show access denied
 */
function require_role($required_roles) {
    require_login();
    
    if (!has_role($required_roles)) {
        http_response_code(403);
        $dashboard = isset($_SESSION['user_role']) ? 'Dashboard_new.php' : 'login.php';
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Access Denied</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { color: #d9534f; }
                .container { max-width: 600px; margin: 0 auto; }
                .btn { display: inline-block; padding: 10px 20px; background: #337ab7; 
                       color: white; text-decoration: none; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Access Denied</h1>
                <p>You do not have permission to access this page.</p>
                <p><a href="$dashboard" class="btn">Return to Dashboard</a></p>
            </div>
        </body>
        </html>
        HTML;
        exit();
    }
}

/**
 * Get current user information
 */
function get_session_user() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? 'Unknown',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'employee'
    ];
}

/**
 * Log activity for auditing purposes
 */
function log_activity($action, $details = '') {
    $user =  get_session_user();
    $user_id = $user ? $user['id'] : 'system';
    $user_name = $user ? $user['name'] : 'System Process';
    
    $conn = get_db_connection();
    if (!$conn) {
        error_log("Failed to log activity: No database connection");
        return false;
    }

    $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $details = substr($details, 0, 1000); // Limit details length

    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_name, action, details, ip_address) 
                           VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("sssss", $user_id, $user_name, $action, $details, $ip);
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    $stmt->close();
    return true;
}

/**
 * Get client IP address securely
 */
function get_client_ip() {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip_list = explode(',', $_SERVER[$key]);
            foreach ($ip_list as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }
    
    return 'unknown';
}