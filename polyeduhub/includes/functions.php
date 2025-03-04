<?php
// Place this file in: polyeduhub/includes/functions.php

/**
 * Format a timestamp into a readable date
 * @param string $timestamp The timestamp to format
 * @param string $format Output format (default: 'M d, Y')
 * @return string Formatted date
 */
function formatDate($timestamp, $format = 'M d, Y') {
    return date($format, strtotime($timestamp));
}

/**
 * Calculate time elapsed since given timestamp
 * @param string $datetime Timestamp
 * @return string Formatted time ago string
 */
function time_elapsed_string($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    } elseif ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    } elseif ($diff->d > 0) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'just now';
    }
}

/**
 * Format file size to human readable format
 * @param int $bytes File size in bytes
 * @param int $precision Decimal precision
 * @return string Formatted file size
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Generate a secure random token
 * @param int $length Token length
 * @return string Random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Hash a password securely
 * @param string $password Plain password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify a password against a hash
 * @param string $password Plain password to verify
 * @param string $hash Stored hash to verify against
 * @return bool Whether the password matches the hash
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is logged in
 * @return bool Whether the user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['id']);
}

/**
 * Check if the current user is an admin
 * @return bool Whether the user is an admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirect to a URL
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Set a flash message to be displayed on the next page load
 * @param string $type Message type (success, error, info, warning)
 * @param string $message Message content
 * @return void
 */
function setFlashMessage($type, $message) {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear all flash messages
 * @return array Flash messages
 */
function getFlashMessages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    $_SESSION['flash_messages'] = [];
    return $messages;
}

/**
 * Display flash messages HTML
 * @return string HTML for flash messages
 */
function displayFlashMessages() {
    $messages = getFlashMessages();
    $html = '';
    
    foreach ($messages as $message) {
        $type = $message['type'];
        $content = $message['message'];
        
        // Map message type to Bootstrap alert class
        $class = 'alert-info';
        if ($type === 'success') $class = 'alert-success';
        if ($type === 'error') $class = 'alert-danger';
        if ($type === 'warning') $class = 'alert-warning';
        
        $html .= "<div class='alert $class alert-dismissible fade show' role='alert'>
                    $content
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                 </div>";
    }
    
    return $html;
}

/**
 * Sanitize input data to prevent XSS
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Get file extension from filename
 * @param string $filename Filename
 * @return string File extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Award points to a user
 * @param int $user_id User ID
 * @param int $points Points to award
 * @param string $action Action description
 * @param string $description Detailed description
 * @return bool Whether points were successfully awarded
 */
function awardPoints($user_id, $points, $action, $description = '') {
    try {
        // Update user points
        dbUpdate('user_points', 
            ['points' => ['points + ?', $points]],
            'user_id = ?', 
            [$user_id]
        );
        
        // Record points history
        dbInsert('points_history', [
            'user_id' => $user_id,
            'points' => $points,
            'action' => $action,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return true;
    } catch (Exception $e) {
        error_log("Error awarding points: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if a string starts with a specific substring
 * @param string $haystack String to search in
 * @param string $needle String to search for
 * @return bool Whether $haystack starts with $needle
 */
function startsWith($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

/**
 * Check if a string ends with a specific substring
 * @param string $haystack String to search in
 * @param string $needle String to search for
 * @return bool Whether $haystack ends with $needle
 */
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

/**
 * Truncate a string to a maximum length
 * @param string $string String to truncate
 * @param int $length Maximum length
 * @param string $append String to append if truncated
 * @return string Truncated string
 */
function truncateString($string, $length = 100, $append = '...') {
    if (strlen($string) <= $length) {
        return $string;
    }
    
    return substr($string, 0, $length) . $append;
}

/**
 * Get a random greeting message
 * @param string $name Person's name
 * @return string Greeting message
 */
function getRandomGreeting($name) {
    $greetings = [
        "Hello, $name!",
        "Welcome back, $name!",
        "Good to see you, $name!",
        "Hi there, $name!",
        "Greetings, $name!"
    ];
    
    return $greetings[array_rand($greetings)];
}

/**
 * Create a notification for a user
 * @param int $user_id User ID
 * @param string $message Notification message
 * @param string $link Optional link
 * @return int|bool Notification ID or false on failure
 */
function createNotification($user_id, $message, $link = '') {
    return dbInsert('notifications', [
        'user_id' => $user_id,
        'message' => $message,
        'link' => $link,
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

/**
 * Get unread notification count for a user
 * @param int $user_id User ID
 * @return int Number of unread notifications
 */
function getUnreadNotificationCount($user_id) {
    $result = dbSelect("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0", [$user_id]);
    return $result[0]['count'] ?? 0;
}

/**
 * Get recent notifications for a user
 * @param int $user_id User ID
 * @param int $limit Maximum number of notifications
 * @return array Notifications
 */
function getRecentNotifications($user_id, $limit = 5) {
    return dbSelect(
        "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
        [$user_id, $limit]
    );
}

/**
 * Mark notifications as read
 * @param int $user_id User ID
 * @param int|null $notification_id Specific notification ID or null for all
 * @return bool Whether notifications were marked as read
 */
function markNotificationsAsRead($user_id, $notification_id = null) {
    if ($notification_id) {
        return dbUpdate('notifications',
            ['is_read' => 1],
            'id = ? AND user_id = ?',
            [$notification_id, $user_id]
        ) > 0;
    } else {
        return dbUpdate('notifications',
            ['is_read' => 1],
            'user_id = ? AND is_read = 0',
            [$user_id]
        ) > 0;
    }
}