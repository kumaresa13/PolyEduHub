<?php
// Place this file in: polyeduhub/includes/config.php

// Application settings
define('APP_NAME', 'PolyEduHub');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/polyeduhub');

// Environment setting
define('ENVIRONMENT', 'development'); // Change to 'production' for live site

// Security settings - Set these BEFORE session_start()
// Session lifetime settings
define('SESSION_LIFETIME', 86400); // 24 hours

// Only set session parameters if session hasn't started yet
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
    ini_set('session.cookie_lifetime', SESSION_LIFETIME);
    session_start();
}

// Database settings are in db-connection.php

// File upload settings
define('UPLOAD_MAX_SIZE', 10485760); // 10MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png']);

// Define upload paths (create directories if they don't exist)
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
try {
    if (!is_dir(UPLOAD_PATH)) {
        if (!mkdir(UPLOAD_PATH, 0755, true)) {
            error_log('Failed to create uploads directory');
        }
    }
} catch (Exception $e) {
    error_log('Error creating uploads directory: ' . $e->getMessage());
}

define('RESOURCE_PATH', dirname(__DIR__) . '/resources/');
try {
    if (!is_dir(RESOURCE_PATH)) {
        if (!mkdir(RESOURCE_PATH, 0755, true)) {
            error_log('Failed to create resources directory');
        }
    }
} catch (Exception $e) {
    error_log('Error creating resources directory: ' . $e->getMessage());
}

// Points system configuration
define('POINTS_UPLOAD', 10);
define('POINTS_DOWNLOAD', 1);
define('POINTS_COMMENT', 2);
define('POINTS_RATING', 5);
define('POINTS_ANSWER', 10); // Adding missing constant

// Error handling based on environment
if (ENVIRONMENT === 'development') {
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
    try {
        if (!is_dir(dirname(__DIR__) . '/logs')) {
            if (!mkdir(dirname(__DIR__) . '/logs', 0755, true)) {
                error_log('Failed to create logs directory');
            }
        }
    } catch (Exception $e) {
        error_log('Error creating logs directory: ' . $e->getMessage());
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