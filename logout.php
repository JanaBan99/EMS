<?php
session_start();
require_once 'auth_utils.php';

// Log activity
log_activity('Logout', 'User logged out');

// Destroy session
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
?>