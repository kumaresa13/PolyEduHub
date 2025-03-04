<?php
// Place this file in: polyeduhub/includes/config.php
// Application settings
define('APP_NAME', 'PolyEduHub');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/polyeduhub');
// Database settings are in db-connection.php
// File upload settings
define('UPLOAD_MAX_SIZE', 10485760); // 10MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png']);
// Define upload paths (create directories if they don't exist)
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
define('RESOURCE_PATH', dirname(__DIR__) . '/resources/');
if (!is_dir(RESOURCE_PATH)) {
    mkdir(RESOURCE_PATH, 0755, true);
}
// Points system configuration
define('POINTS_UPLOAD', 10);
define('POINTS_DOWNLOAD', 1);
define('POINTS_COMMENT', 2);
define('POINTS_RATING', 5);
// Security settings
define('SESSION_LIFETIME', 86400); // 24 hours
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);
// Error handling
if (true) { // Change to false in production
    // Development environment
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Production environment
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    
    // Log errors instead of displaying them
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__) . '/logs/error.log');
    
    // Create logs directory if it doesn't exist
    if (!is_dir(dirname(__DIR__) . '/logs')) {
        mkdir(dirname(__DIR__) . '/logs', 0755, true);
    }
}

// Time settings
date_default_timezone_set('Asia/Kuala_Lumpur'); // Malaysia timezone

// Chat settings
define('CHAT_MESSAGE_LIMIT', 100); // Number of messages to load in chat history
define('CHAT_REFRESH_RATE', 5000); // Refresh rate in milliseconds for chat updates

// Gamification settings
define('BADGE_LEVELS', [
    'bronze' => 100,   // Points needed for bronze badge
    'silver' => 500,   // Points needed for silver badge
    'gold' => 1000,    // Points needed for gold badge
    'platinum' => 5000 // Points needed for platinum badge
]);

// Session settings
session_start();
?>