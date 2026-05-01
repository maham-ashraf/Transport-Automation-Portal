<?php
/**
 * Database Connection File
 * University Transport Automation Portal
 * 
 * This file establishes connection to MySQL database using MySQLi
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'transport_portal');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle special characters
$conn->set_charset("utf8mb4");

/**
 * Helper function to sanitize input
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $conn->real_escape_string($data);
}

/**
 * Helper function to display error message
 * @param string $message Error message
 * @return string Formatted error HTML
 */
function showError($message) {
    return '<div class="alert alert-error">' . $message . '</div>';
}

/**
 * Helper function to display success message
 * @param string $message Success message
 * @return string Formatted success HTML
 */
function showSuccess($message) {
    return '<div class="alert alert-success">' . $message . '</div>';
}

/**
 * Helper function to redirect with message
 * @param string $location URL to redirect to
 * @param string $message Message to display
 * @param string $type Message type (success/error)
 */
function redirectWithMessage($location, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: " . $location);
    exit();
}

/**
 * Check if user is logged in
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 * @param string $role Role to check
 * @return bool True if user has role, false otherwise
 */
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Require authentication
 * Redirects to login page if not logged in
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header("Location: /Transport%20Automation%20Portal%20old/backend/login.php");
        exit();
    }
}

/**
 * Require specific role
 * Redirects to dashboard if user doesn't have required role
 * @param string $role Required role
 */
function requireRole($role) {
    requireAuth();
    if (!hasRole($role)) {
        header("Location: /Transport%20Automation%20Portal%20old/backend/unauthorized.php");
        exit();
    }
}

/**
 * Get current user ID
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current user role
 * @return string|null User role or null if not logged in
 */
function getCurrentUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

/**
 * Log activity (for audit trail)
 * @param int $user_id User ID performing the action
 * @param string $action Action performed
 * @param string $details Additional details
 */
function logActivity($conn, $user_id, $action, $details = '') {
    // You can create an activity_log table if needed
    // For now, this is a placeholder function
    $sql = "INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iss", $user_id, $action, $details);
        $stmt->execute();
        $stmt->close();
    }
}
?>
