<?php
/**
 * Configuration file for PolyEduHub
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Application settings
define('APP_NAME', 'PolyEduHub');
define('APP_URL', 'http://localhost/polyeduhub');
define('APP_VERSION', '1.0.0');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Change to your database username
define('DB_PASS', '');               // Change to your database password
define('DB_NAME', 'polyeduhub');     // Change to your database name

// File upload settings
define('UPLOAD_MAX_SIZE', 10485760); // 10MB in bytes
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
define('RESOURCE_PATH', dirname(__DIR__) . '/resources/');

// Session settings
define('SESSION_LIFETIME', 86400); // 24 hours in seconds

// Security settings
define('SALT', 'polyeduhub_salt_string'); // Change this to a random string for security

// Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Social media links
define('FACEBOOK_URL', '#');
define('TWITTER_URL', '#');
define('INSTAGRAM_URL', '#');
define('LINKEDIN_URL', '#');

// Contact information
define('CONTACT_EMAIL', 'info@polyeduhub.edu.my');
define('CONTACT_PHONE', '+60 12 345 6789');
define('CONTACT_ADDRESS', 'Polytechnic Malaysia, Kuala Lumpur');

// Points system
define('POINTS_UPLOAD', 10); // Points for uploading a resource
define('POINTS_DOWNLOAD', 1); // Points for downloading a resource
define('POINTS_COMMENT', 2); // Points for leaving a comment
define('POINTS_ANSWER', 5); // Points for answering a question
?>