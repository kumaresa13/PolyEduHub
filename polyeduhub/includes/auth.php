<?php
/**
 * Authentication helper functions
 * Place this file in: polyeduhub/includes/auth.php
 */

/**
 * Check if student is logged in, redirect to login page if not
 * @param string $redirect_path Optional custom redirect path
 * @return void
 */
function checkStudentLogin($redirect_path = null) {
    // Check if session has started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Determine base path for redirect
    $base_path = '';
    $current_path = $_SERVER['SCRIPT_NAME'];
    $path_parts = explode('/', $current_path);
    
    // Count directory depth to build proper path to login
    $depth = 0;
    foreach ($path_parts as $part) {
        if ($part === 'student' || $part === 'admin') {
            $depth++;
        } elseif ($depth > 0 && !empty($part)) {
            $depth++;
        }
    }
    
    // Build path based on depth
    for ($i = 0; $i < $depth; $i++) {
        $base_path .= '../';
    }
    
    // Default redirect path
    if ($redirect_path === null) {
        $redirect_path = $base_path . 'login.php';
    }
    
    // Check if user is logged in as student
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
        $_SESSION['login_error'] = "Please log in to access this page.";
        header("Location: " . $redirect_path);
        exit();
        // Check if session is expired (if last_activity is set and older than SESSION_LIFETIME)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        // Session has expired, destroy it and redirect
        session_unset();
        session_destroy();
        $_SESSION['login_error'] = "Your session has expired. Please log in again.";
        header("Location: " . $redirect_path);
        exit();
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Check if admin is logged in, redirect to admin login page if not
 * @param string $redirect_path Optional custom redirect path
 * @return void
 */
function checkAdminLogin($redirect_path = null) {
    // Check if session has started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Determine base path for redirect
    $base_path = '';
    $current_path = $_SERVER['SCRIPT_NAME'];
    $path_parts = explode('/', $current_path);
    
    // Count directory depth to build proper path to login
    $depth = 0;
    foreach ($path_parts as $part) {
        if ($part === 'admin' || $part === 'student') {
            $depth++;
        } elseif ($depth > 0 && !empty($part)) {
            $depth++;
        }
    }
    
    // Build path based on depth
    for ($i = 0; $i < $depth; $i++) {
        $base_path .= '../';
    }
    
    // Default redirect path
    if ($redirect_path === null) {
        $redirect_path = $base_path . 'admin-login.php';
    }
    
    // Check if user is logged in as admin
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['login_error'] = "Please log in to access the admin area.";
        header("Location: " . $redirect_path);
        exit();
    }
    
    // Check if session is expired (if last_activity is set and older than SESSION_LIFETIME)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        // Session has expired, destroy it and redirect
        session_unset();
        session_destroy();
        $_SESSION['login_error'] = "Your session has expired. Please log in again.";
        header("Location: " . $redirect_path);
        exit();
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Check if user is logged in
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['id']);
}

/**
 * Check if current session is for an admin user
 * @return bool True if admin, false otherwise
 */
function isAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>